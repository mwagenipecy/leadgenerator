#!/bin/sh
set -e
# Share built public assets with nginx via shared volume
cp -a /var/www/html/public/. /var/www/html/public_shared/ 2>/dev/null || true
exec "$@"
