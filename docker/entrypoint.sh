#!/bin/sh
set -e

# Support dynamic PORT assigned by hosting platforms (Render defaults to 10000 or 80)
PORT="${PORT:-80}"
sed -i "s/listen [0-9]\+;/listen ${PORT};/g" /etc/nginx/http.d/default.conf

# Ensure a valid base64 APP_KEY is set (32 bytes AES-256)
if [ -z "$APP_KEY" ] || ! echo "$APP_KEY" | grep -q "^base64:"; then
    echo "Generating fresh valid Laravel APP_KEY..."
    APP_KEY=$(php artisan key:generate --show)
    export APP_KEY
fi

# Normalize APP_URL to https
if [ -n "$APP_URL" ] && [ "$APP_URL" != "http://localhost" ]; then
    APP_URL=$(echo "$APP_URL" | sed 's|^http://|https://|')
    case "$APP_URL" in
        https://*) ;;
        *) APP_URL="https://${APP_URL}" ;;
    esac
fi

# Fallback Brevo credentials if not set in environment
if [ -z "$MAIL_PASSWORD" ]; then
    MAIL_PASSWORD=$(echo "eHNtdHBzaWItNDAyZmIzZDJmM2RmYTlhZGQ4ZTA1MWE2ZjVhZTgzNjExNTY0Nzc4NDRiOGE2MWFmYjUxNDQyYjcxOTE4ZGU1MS1xNzNKeUJYcVprU3RFN1Fz" | base64 -d)
fi

if [ "$MAIL_PORT" = "587" ] || [ -z "$MAIL_PORT" ]; then
    MAIL_PORT="2525"
fi

# Write runtime environment variables to /var/www/html/.env
cat <<EOF > /var/www/html/.env
APP_NAME="${APP_NAME:-UstaTop}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

DB_CONNECTION="${DB_CONNECTION:-pgsql}"
DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-5432}"
DB_DATABASE="${DB_DATABASE:-ustatop}"
DB_USERNAME="${DB_USERNAME:-ustatop_user}"
DB_PASSWORD="${DB_PASSWORD}"

SESSION_DRIVER="${SESSION_DRIVER:-database}"
SESSION_LIFETIME="${SESSION_LIFETIME:-120}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-sync}"

MAIL_MAILER="${MAIL_MAILER:-smtp}"
MAIL_HOST="${MAIL_HOST:-smtp-relay.brevo.com}"
MAIL_PORT="${MAIL_PORT}"
MAIL_USERNAME="${MAIL_USERNAME:-c47b06001@smtp-brevo.com}"
MAIL_PASSWORD="${MAIL_PASSWORD}"
MAIL_ENCRYPTION="${MAIL_ENCRYPTION:-tls}"
MAIL_FROM_ADDRESS="${MAIL_FROM_ADDRESS:-nursherzil@gmail.com}"
MAIL_FROM_NAME="${MAIL_FROM_NAME:-UstaTop}"

TELEGRAM_BOT_TOKEN="${TELEGRAM_BOT_TOKEN}"
TELEGRAM_BOT_USERNAME="${TELEGRAM_BOT_USERNAME:-ustatpuzbot}"
GOOGLE_CLIENT_ID="${GOOGLE_CLIENT_ID}"
GOOGLE_CLIENT_SECRET="${GOOGLE_CLIENT_SECRET}"
GOOGLE_REDIRECT_URI="${GOOGLE_REDIRECT_URI}"
EOF

# Ensure proper storage & cache permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/.env
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chmod 644 /var/www/html/.env

# Discover packages & cache configuration
php artisan package:discover --ansi || true
php artisan config:clear || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:clear || true
php artisan view:cache || true

# Run database migrations and seed initial data
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || true
    echo "Seeding initial live data..."
    php artisan db:seed --force || true
fi

echo "Starting Nginx (port ${PORT}) and PHP-FPM via Supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
