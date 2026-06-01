#!/usr/bin/env bash

# Run database migrations and seed admin user
php artisan migrate --force
php create_admin.php

# Start Apache in the foreground
apache2-foreground
