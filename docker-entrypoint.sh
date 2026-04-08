#!/bin/bash
set -e

# Set APP_URL from Render's external URL if available
if [ -n "$RENDER_EXTERNAL_URL" ]; then
    export APP_URL="$RENDER_EXTERNAL_URL"
fi

# Generate app key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "generateValue" ]; then
    php artisan key:generate --force
fi

# Create SQLite database if not exists
touch /var/www/html/database/database.sqlite
chown www-data:www-data /var/www/html/database/database.sqlite
chmod 664 /var/www/html/database/database.sqlite

# Ensure storage directories exist with proper permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations and seed
php artisan migrate --force --seed

# Cache config and routes for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
