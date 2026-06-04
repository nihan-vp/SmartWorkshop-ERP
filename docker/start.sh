#!/usr/bin/env bash
set -e

echo "=== Starting Suhaim Soft Workshop ==="

# 1. Ensure .env file exists (Laravel needs it for artisan commands)
if [ ! -f .env ]; then
    echo "APP_KEY=" > .env
    echo "✓ Created .env file"
fi

# 2. Generate a proper APP_KEY if the current one isn't in Laravel's base64 format
if [[ ! "$APP_KEY" == base64:* ]]; then
    NEW_KEY=$(php artisan key:generate --show --no-ansi)
    export APP_KEY="$NEW_KEY"
    echo "APP_KEY=$NEW_KEY" > .env
    echo "✓ Generated proper APP_KEY"
else
    echo "APP_KEY=$APP_KEY" > .env
    echo "✓ APP_KEY is valid"
fi

# 3. Write all critical env vars to .env so Laravel can read them
# (env vars from Render are available, but .env ensures artisan commands work)
{
    echo "APP_ENV=${APP_ENV:-production}"
    echo "APP_DEBUG=${APP_DEBUG:-false}"
    echo "APP_KEY=$APP_KEY"
    echo "APP_URL=${APP_URL:-http://localhost}"
    echo "DB_CONNECTION=${DB_CONNECTION:-pgsql}"
    echo "DB_HOST=${DB_HOST:-localhost}"
    echo "DB_PORT=${DB_PORT:-5432}"
    echo "DB_DATABASE=${DB_DATABASE:-suhaimsoft}"
    echo "DB_USERNAME=${DB_USERNAME:-suhaimsoft_user}"
    echo "DB_PASSWORD=${DB_PASSWORD:-}"
    echo "SESSION_DRIVER=file"
    echo "CACHE_STORE=file"
    echo "LOG_CHANNEL=stderr"
} > .env

echo "✓ Environment configured"

# 4. Ensure storage directories exist with correct permissions
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
echo "✓ Storage directories ready"

# 5. Run database migrations
echo "Running migrations..."
php artisan migrate --force
echo "✓ Migrations complete"

# 6. Seed admin user
echo "Seeding admin user..."
php create_admin.php
echo "✓ Admin user ready"

# 7. Clear old caches and optimize for production
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo "✓ Caches cleared"

echo "=== App Ready! Starting Apache... ==="

# Start Apache in the foreground
apache2-foreground
