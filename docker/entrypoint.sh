#!/bin/sh
set -e

# -------------------------------------------------
# Laravel Docker entrypoint adjustments for Nginx
# -------------------------------------------------

# Ensure Laravel storage and cache directories have correct permissions.
# Only chown these dirs: .env is mounted read-only and must not be touched.
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Sync public assets into shared volume so nginx can serve them (public_assets → nginx root).
cp -a /var/www/html/public/. /var/www/html/public_shared/

# Optional: run database migrations if needed
# Uncomment the next line if you want migrations to run automatically
# php /var/www/html/artisan migrate --force

# Execute the container CMD (PHP-FPM or other command)
exec "$@"