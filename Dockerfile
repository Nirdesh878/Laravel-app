FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    vim \
    libzip-dev \
    libmcrypt-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

# Generate key (optional)
RUN php artisan key:generate || true

# Clear config cache — DO NOT CACHE INSIDE BUILD
RUN php artisan config:clear && php artisan route:clear && php artisan view:clear

# Expose port
EXPOSE 8000

RUN php artisan migrate --force || true

# Start app
CMD php artisan serve --host=0.0.0.0 --port=8000
