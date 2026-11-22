# Multi-stage build for Laravel 12 on PHP 8.4 + Nginx
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

FROM php:8.4-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1

# Install system dependencies & PHP extensions
RUN apk add --no-cache \
    nginx \
    curl \
    git \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    postgresql-dev \
    sqlite-dev \
    oniguruma-dev \
    linux-headers \
    supervisor \
    bash \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd opcache zip

# Configure PHP-FPM to preserve environment variables and stream worker output to Docker logs
RUN { \
    echo '[www]'; \
    echo 'clear_env = no'; \
    echo 'catch_workers_output = yes'; \
    echo 'decorate_workers_output = no'; \
} >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP dependencies first to take advantage of Docker layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --ignore-platform-reqs --no-interaction -v

# Copy application files (excluding those in .dockerignore)
COPY . .
COPY --from=frontend /app/public/build ./public/build

# Generate optimized autoloader for production
RUN composer dump-autoload --optimize --no-scripts

# Set permissions
RUN mkdir -p /var/www/html/public/avatars \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/avatars \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/avatars

# Copy Nginx & Supervisor configuration
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
