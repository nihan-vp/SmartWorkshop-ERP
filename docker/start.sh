#!/usr/bin/env bash

# Run migrations
php artisan migrate --force

# Start php-fpm in background
php-fpm -D

# Start Nginx in foreground
nginx -g "daemon off;"
