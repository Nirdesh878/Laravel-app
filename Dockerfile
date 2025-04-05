FROM richarvey/nginx-php-fpm:latest

# Set the document root to Laravel's public directory
ENV WEBROOT /var/www/html/public

# Copy application files
COPY . /var/www/html

# Set appropriate permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Clear and cache configuration
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache
