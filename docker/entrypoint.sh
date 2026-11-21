#!/bin/bash
set -e

# Cache configuration & routes for production speed
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run database migrations automatically on boot
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || true
fi

# Ensure storage permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

echo "Starting Nginx and PHP-FPM via Supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
