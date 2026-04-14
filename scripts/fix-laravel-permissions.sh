#!/usr/bin/env sh
set -e

APP_DIR="${1:-/var/www/html}"

mkdir -p "$APP_DIR/storage/logs" "$APP_DIR/bootstrap/cache"
touch "$APP_DIR/storage/logs/laravel.log"

# Keep group writable for web and queue processes.
chown -R www-data:www-data "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
find "$APP_DIR/storage" "$APP_DIR/bootstrap/cache" -type d -exec chmod 775 {} \;
find "$APP_DIR/storage" "$APP_DIR/bootstrap/cache" -type f -exec chmod 664 {} \;
