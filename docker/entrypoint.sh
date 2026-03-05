#!/bin/sh
set -e
# PHP-FPM: ensure listen on 9000 (all interfaces) so nginx can connect (fixes 502)
for f in /usr/local/etc/php-fpm.d/*.conf; do
  [ -f "$f" ] && sed -i 's/^listen = .*/listen = 9000/' "$f" 2>/dev/null || true
  [ -f "$f" ] && sed -i 's/^listen=.*/listen=9000/' "$f" 2>/dev/null || true
done
# Share built public assets with nginx via shared volume
cp -a /var/www/html/public/. /var/www/html/public_shared/ 2>/dev/null || true
exec "$@"
