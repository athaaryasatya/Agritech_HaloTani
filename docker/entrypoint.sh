#!/usr/bin/env bash
set -euo pipefail

PORT="${PORT:-10000}"

if [ -z "${APP_KEY:-}" ]; then
    echo "ERROR: APP_KEY environment variable is not set." >&2
    exit 1
fi

# Configure Apache to listen on Render's dynamic PORT
sed -i "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Ensure writable directories exist
mkdir -p \
    storage/app/public \
    storage/app/private \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Create public/storage symlink if not present
if [ ! -L public/storage ]; then
    php artisan storage:link --no-interaction || true
fi

# Cache configuration from runtime environment variables
php artisan config:cache

# Run database migrations automatically (Render Free Tier has no Shell tab)
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force --no-interaction
fi

# Optionally seed demo accounts & default prompt template (set RUN_SEEDER=true on first deploy)
if [ "${RUN_SEEDER:-false}" = "true" ]; then
    php artisan db:seed --force --no-interaction
fi

# Cache routes, views, and Filament components for faster responses
php artisan route:cache
php artisan view:cache
php artisan filament:optimize || true

# Ensure Apache worker user (www-data) owns storage and cache files
chown -R www-data:www-data storage bootstrap/cache

exec apache2-foreground
