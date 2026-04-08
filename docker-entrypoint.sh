#!/bin/bash
set -e

echo "=== Starting deployment ==="

# Set APP_URL from Render's external URL if available
if [ -n "$RENDER_EXTERNAL_URL" ]; then
    export APP_URL="$RENDER_EXTERNAL_URL"
    echo "APP_URL set to: $APP_URL"
fi

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Create SQLite database if not exists
echo "Setting up database..."
touch /var/www/html/database/database.sqlite
chown www-data:www-data /var/www/html/database/database.sqlite
chmod 664 /var/www/html/database/database.sqlite

# Ensure storage directories exist with proper permissions
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations and seed
echo "Running migrations..."
php artisan migrate --force
echo "Running seeds..."
php artisan db:seed --force

# Cache for performance
echo "Caching config and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Deployment complete, starting Apache on port ${PORT:-10000} ==="

exec "$@"
