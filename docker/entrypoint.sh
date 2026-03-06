#!/bin/sh
set -e

# -------------------------------------------------
# Laravel Docker entrypoint adjustments for Nginx
# -------------------------------------------------

# Ensure Laravel storage and cache directories have correct permissions.
# Only chown these dirs: .env is mounted read-only and must not be touched.
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Optional: run database migrations if needed
# Uncomment the next line if you want migrations to run automatically
# php /var/www/html/artisan migrate --force

# Execute the container CMD (PHP-FPM or other command)
exec "$@"