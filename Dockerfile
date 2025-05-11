# Use the official PHP image with extensions for Laravel
FROM php:8.3-fpm

# Set the working directory inside the container
WORKDIR /var/www/html

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    nano \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    && docker-php-ext-install \
    gd \
    pdo_mysql \
    mbstring \
    zip \
    opcache \
    intl \
    pcntl \
    bcmath

# Install Composer globally
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Install Node.js (for Vite/Vue)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && apt-get install -y nodejs

# Copy application source code
COPY . .

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Expose the PHP-FPM port
EXPOSE 9000

# Start the PHP-FPM server
CMD ["php-fpm"]
