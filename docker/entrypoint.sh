#!/bin/sh
set -e

# -------------------------------------------------
# Laravel Docker entrypoint adjustments for Nginx
# -------------------------------------------------

# Ensure Laravel storage and cache directories have correct permissions
# (App in image is at /var/www/html per Dockerfile WORKDIR)
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Optional: run database migrations if needed
# Uncomment the next line if you want migrations to run automatically
# php /var/www/html/artisan migrate --force

# Execute the container CMD (PHP-FPM or other command)
exec "$@"