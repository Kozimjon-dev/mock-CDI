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

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Only seed if the users table is empty (fresh database)
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null | tail -1)
if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo "Fresh database detected, running seeds..."
    php artisan db:seed --force
else
    echo "Database already seeded, skipping..."
fi

# Cache for performance
echo "Caching config and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Deployment complete, starting Apache on port ${PORT:-10000} ==="

exec "$@"
