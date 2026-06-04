FROM php:8.3-apache

# Suppress interactive apt prompts during build
ENV DEBIAN_FRONTEND=noninteractive

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    ca-certificates \
    gnupg \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    cron \
    supervisor \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install Node.js 20 via official NodeSource repository (no apt-script warnings)
RUN mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key \
       | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" \
       > /etc/apt/sources.list.d/nodesource.list \
    && apt-get update \
    && apt-get install -y --no-install-recommends nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install PHP extensions (MySQL only - no pgsql)
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    && docker-php-ext-enable opcache

# Configure OPcache for production
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.memory_consumption=192'; \
    echo 'opcache.max_wasted_percentage=10'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.fast_shutdown=1'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Enable Apache modules
RUN a2enmod rewrite headers

# Set Apache document root to Laravel public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (layer caching)
COPY composer.json composer.lock ./
COPY package.json package-lock.json ./

# Install PHP & Node dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts
RUN npm ci --prefer-offline

# Copy application files
COPY . .

# Build Vite assets
RUN npm run build

# Run post-install scripts
RUN composer dump-autoload --optimize \
    && php artisan package:discover --ansi

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html/storage -type d -exec chmod 775 {} \; \
    && find /var/www/html/storage -type f -exec chmod 664 {} \; \
    && find /var/www/html/bootstrap/cache -type d -exec chmod 775 {} \; \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy supervisor config (manages Apache + cron)
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy cron job for Laravel scheduler
COPY docker/scheduler.cron /etc/cron.d/laravel-scheduler
RUN chmod 0644 /etc/cron.d/laravel-scheduler \
    && crontab /etc/cron.d/laravel-scheduler

# Expose port 80
EXPOSE 80

# Copy startup script
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
