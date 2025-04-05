#!/usr/bin/env bash

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Set permissions
chmod -R 775 storage bootstrap/cache

# Laravel config and cache commands
php artisan config:cache
php artisan route:cache
php artisan view:cache
