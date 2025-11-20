# Multi-stage Dockerfile to create and run a Laravel app

# Builder stage: create project with composer
FROM composer:2 as builder
WORKDIR /build
ARG LARAVEL_VERSION=10.*
RUN composer create-project --prefer-dist laravel/laravel="${LARAVEL_VERSION}" app

# Runtime stage
FROM php:8.1-fpm

# System deps
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Copy app from builder
COPY --from=builder /build/app /var/www/html

WORKDIR /var/www/html

# Install composer dependencies (will use vendor cache if any)
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-interaction --optimize-autoloader || true

# Copy Laravel snippets (models, migrations, seeders, controllers, routes) into the app
COPY ../laravel-snippets/app/Models /var/www/html/app/Models
COPY ../laravel-snippets/app/Http/Controllers /var/www/html/app/Http/Controllers
COPY ../laravel-snippets/app/Console /var/www/html/app/Console
COPY ../laravel-snippets/database/migrations /var/www/html/database/migrations
COPY ../laravel-snippets/database/seeders /var/www/html/database/seeders
COPY ../laravel-snippets/routes-api-sample.php /var/www/html/routes/api.php

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

EXPOSE 9000
CMD ["php-fpm"]
