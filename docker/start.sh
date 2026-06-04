#!/usr/bin/env bash
set -e

echo "============================================"
echo "  Suhaim Soft Work Shop - Starting App"
echo "============================================"

# ── 1. Create .env if missing ──────────────────
if [ ! -f /var/www/html/.env ]; then
    touch /var/www/html/.env
    echo "Created empty .env file"
fi

cd /var/www/html

# ── 2. Validate & set APP_KEY ──────────────────
if [[ "$APP_KEY" == base64:* ]]; then
    echo "APP_KEY=$APP_KEY" > .env
    echo "APP_KEY is valid (base64 format)"
elif [ -n "$APP_KEY" ]; then
    # Key exists but not in base64 format - generate a new one
    echo "APP_KEY=" > .env
    NEW_KEY=$(php artisan key:generate --show --no-ansi 2>/dev/null || echo "")
    if [ -n "$NEW_KEY" ]; then
        export APP_KEY="$NEW_KEY"
        echo "Generated new APP_KEY"
    fi
else
    echo "APP_KEY=" > .env
    NEW_KEY=$(php artisan key:generate --show --no-ansi 2>/dev/null || echo "")
    if [ -n "$NEW_KEY" ]; then
        export APP_KEY="$NEW_KEY"
        echo "Generated APP_KEY (was missing)"
    fi
fi

# ── 3. Initialize default database variables ──
DB_CONNECTION=${DB_CONNECTION:-mysql}
DB_HOST=${DB_HOST:-216.151.17.91}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-suhaim_workshop}
DB_USERNAME=${DB_USERNAME:-root}
DB_PASSWORD=${DB_PASSWORD:-12345678}

# ── 4. Write all env vars to .env ─────────────
cat > /var/www/html/.env << EOF
APP_NAME="${APP_NAME:-Suhaim Soft Work Shop}"
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-https://suhaimsoftwokrhsop.onrender.com}
APP_LOCALE=en

LOG_CHANNEL=stderr
LOG_LEVEL=${LOG_LEVEL:-error}

# ── MySQL Database ─────────────────────────────
DB_CONNECTION=${DB_CONNECTION}
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

# ── Session & Cache (file-based for Docker) ───
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local

# ── Mail ──────────────────────────────────────
MAIL_MAILER=${MAIL_MAILER:-log}
MAIL_HOST=${MAIL_HOST:-localhost}
MAIL_PORT=${MAIL_PORT:-1025}
MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS:-noreply@example.com}
MAIL_FROM_NAME="${APP_NAME:-Suhaim Soft Work Shop}"
EOF

echo "Environment configured"

# ── 5. Ensure writable storage directories ────
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
echo "Storage directories ready"

# ── 6. Wait for MySQL to be ready ─────────────
echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT}..."
MAX_RETRIES=10
RETRY=0
until php -r "
    try {
        \$pdo = new PDO(
            'mysql:host=${DB_HOST};port=${DB_PORT}',
            '${DB_USERNAME}',
            '${DB_PASSWORD}',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 2
            ]
        );
        echo 'connected';
    } catch (Exception \$e) {
        // failed
    }
" 2>/dev/null | grep -q "connected"; do
    RETRY=$((RETRY + 1))
    if [ $RETRY -ge $MAX_RETRIES ]; then
        echo "WARNING: MySQL at ${DB_HOST}:${DB_PORT} is not reachable after ${MAX_RETRIES} attempts. Continuing boot anyway..."
        break
    fi
    echo "  MySQL not ready yet (attempt $RETRY/$MAX_RETRIES)..."
    sleep 2
done
echo "MySQL boot check completed"

# ── 7. Run database migrations ────────────────
echo "Running migrations..."
php artisan migrate --force || echo "WARNING: Migration failed, continuing anyway..."

# ── 8. Seed admin user & default workshop ─────
echo "Seeding admin user..."
php create_admin.php || echo "WARNING: Seeding failed, continuing anyway..."

# ── 9. Laravel production optimizations ───────
echo "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "Laravel optimizations applied"

# ── 10. Link storage ───────────────────────────
php artisan storage:link --force 2>/dev/null || true
echo "Storage linked"

# ── 11. Configure Apache Port for Render ────────
# Render assigns a dynamic port via the PORT environment variable. 
# Apache must listen on this port to pass the Render health checks.
APACHE_PORT=${PORT:-80}
sed -i "s/Listen 80/Listen ${APACHE_PORT}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${APACHE_PORT}/g" /etc/apache2/sites-available/*.conf
echo "Apache configured to listen on port ${APACHE_PORT}"

echo ""
echo "============================================"
echo "  App Ready! Starting Apache via Supervisor"
echo "============================================"

# Start supervisor (manages Apache + cron)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
