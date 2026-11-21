#!/bin/sh
set -e

# Support dynamic PORT assigned by hosting platforms (Render defaults to 10000 or 80)
PORT="${PORT:-80}"
sed -i "s/listen [0-9]\+;/listen ${PORT};/g" /etc/nginx/http.d/default.conf

# Discover packages with runtime environment variables
php artisan package:discover --ansi || true

# Run database migrations automatically on boot
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || true
fi

# Cache routes and views for performance
php artisan route:cache || true
php artisan view:cache || true

# Ensure proper storage permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true

echo "Starting Nginx (port ${PORT}) and PHP-FPM via Supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
