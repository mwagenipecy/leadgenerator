#!/bin/sh
set -e

# -------------------------------------------------
# Laravel Docker entrypoint adjustments for Nginx
# -------------------------------------------------

# Ensure Laravel storage and cache directories have correct permissions
chown -R www-data:www-data /var/www/leadgenerator
chmod -R 775 /var/www/leadgenerator/storage /var/www/leadgenerator/bootstrap/cache

# Optional: run database migrations if needed
# Uncomment the next line if you want migrations to run automatically
# php /var/www/leadgenerator/artisan migrate --force

# Execute the container CMD (PHP-FPM or other command)
exec "$@"