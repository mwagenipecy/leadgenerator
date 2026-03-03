# Stage 1: Build frontend assets
FROM node:20-alpine AS frontend
WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

# Stage 2: PHP application
FROM php:8.2-fpm-alpine AS app
WORKDIR /var/www/html

# Install system deps + PHP extensions Laravel needs (PostgreSQL + SQLite)
RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    sqlite-dev \
    icu-dev \
    postgresql-dev \
    linux-headers \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        exif \
        intl \
        opcache \
        pcntl \
        pdo_pgsql \
        pdo_sqlite \
        zip \
        gd \
        dom \
        xml \
        mbstring

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Application code
COPY . .
COPY --from=frontend /app/public/build ./public/build

# Composer install (no dev for production)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Entrypoint to sync public assets to shared volume for nginx
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Permissions for Laravel (php-fpm runs as www-data internally)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm", "-F"]
