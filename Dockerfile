# syntax=docker/dockerfile:1

# -----------------------------------------------------------------------------
# Stage 1: Install Composer dependencies & publish Filament assets
# -----------------------------------------------------------------------------
FROM php:8.4-cli-bookworm AS vendor

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libicu-dev \
        libzip-dev \
    && docker-php-ext-install -j"$(nproc)" intl zip pdo_mysql bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --no-autoloader \
        --no-scripts

COPY . .

RUN mkdir -p \
        storage/app/public \
        storage/app/private \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && composer dump-autoload --optimize --no-dev --no-scripts \
    && CACHE_STORE=array php artisan package:discover --ansi \
    && CACHE_STORE=array php artisan filament:assets --ansi

# -----------------------------------------------------------------------------
# Stage 2: Build frontend assets with Vite (Tailwind CSS v4)
# -----------------------------------------------------------------------------
FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js ./
COPY app ./app
COPY resources ./resources
COPY public ./public
COPY --from=vendor /app/vendor/laravel/framework/src/Illuminate/Pagination/resources/views ./vendor/laravel/framework/src/Illuminate/Pagination/resources/views

RUN npm run build

# -----------------------------------------------------------------------------
# Stage 3: Production runtime (PHP 8.4 + Apache)
# -----------------------------------------------------------------------------
FROM php:8.4-apache-bookworm AS production

RUN apt-get update && apt-get install -y --no-install-recommends \
        ca-certificates \
        libicu-dev \
        libzip-dev \
    && update-ca-certificates \
    && docker-php-ext-install -j"$(nproc)" intl pdo_mysql zip bcmath opcache exif \
    && a2enmod rewrite headers \
    && sed -i 's/^Listen 80$/Listen 10000/' /etc/apache2/ports.conf \
    && rm -rf /var/lib/apt/lists/*

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/mpm_prefork.conf /etc/apache2/mods-available/mpm_prefork.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/99-production.ini

WORKDIR /var/www/html

# Copy application with Composer vendor & published Filament assets
COPY --from=vendor /app /var/www/html

# Copy compiled Vite assets
COPY --from=assets /app/public/build /var/www/html/public/build

# Ensure writable directories have correct permissions
RUN mkdir -p \
        storage/app/public \
        storage/app/private \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 10000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
