#!/bin/sh
set -e

# -------------------------------------------------
# Laravel Docker entrypoint adjustments for Nginx
# -------------------------------------------------

# Ensure storage/log permissions are always fixed on container start.
sh /var/www/html/scripts/fix-laravel-permissions.sh /var/www/html

# Ensure storage symlink exists inside container with container path.
if [ -L /var/www/html/public/storage ] || [ -e /var/www/html/public/storage ]; then
  rm -rf /var/www/html/public/storage
fi
ln -s /var/www/html/storage/app/public /var/www/html/public/storage

# Sync public assets into shared volume so nginx can serve them (public_assets → nginx root).
cp -a /var/www/html/public/. /var/www/html/public_shared/

# Optional: run database migrations if needed
# Uncomment the next line if you want migrations to run automatically
# php /var/www/html/artisan migrate --force

# Execute the container CMD (PHP-FPM or other command)
exec "$@"