#!/usr/bin/env bash
# Run on the server (e.g. cd /var/www/leadgenerator && bash scripts/diagnose-502.sh)
# to find why nginx returns 502 Bad Gateway.

set -e
cd "$(dirname "$0")/.."

echo "=== 1. Container status ==="
sudo docker compose ps

echo ""
echo "=== 2. Is the app container (leadgenerator-app-1) running? ==="
if sudo docker compose ps app 2>/dev/null | grep -q "Up"; then
  echo "App container is Up."
else
  echo "App container is NOT running. Last logs:"
  sudo docker compose logs --tail=30 app
  echo "Fix: deploy latest code (entrypoint fix) and run: sudo docker compose up -d --force-recreate"
  exit 1
fi

echo ""
echo "=== 3. PHP-FPM socket inside APP container (/var/run/php) ==="
sudo docker exec leadgenerator-app-1 ls -la /var/run/php/ 2>/dev/null || echo "Cannot list (container or path missing)"

echo ""
echo "=== 4. PHP-FPM socket inside NGINX container (/var/run/php) ==="
sudo docker exec leadgenerator-nginx-1 ls -la /var/run/php/ 2>/dev/null || echo "Cannot list (container or path missing)"

echo ""
echo "=== 5. PHP-FPM listen config in app container ==="
sudo docker exec leadgenerator-app-1 cat /usr/local/etc/php-fpm.d/www.conf 2>/dev/null | grep -E "^listen|^listen\.(owner|group|mode)" || echo "Cannot read config"

echo ""
echo "=== 6. Nginx fastcgi_pass in use ==="
sudo docker exec leadgenerator-nginx-1 grep -E "fastcgi_pass|SCRIPT_FILENAME" /etc/nginx/conf.d/default.conf 2>/dev/null || true

echo ""
echo "=== 7. Last nginx error lines (502 cause) ==="
sudo docker exec leadgenerator-nginx-1 tail -20 /var/log/nginx/error.log 2>/dev/null || true

echo ""
echo "=== 8. App container last log lines ==="
sudo docker compose logs --tail=15 app 2>/dev/null || true
