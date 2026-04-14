#!/usr/bin/env bash
# Run on the server to deploy (or called by GitHub Actions via SSH).
# Usage: ./scripts/deploy.sh   or   cd /var/www/leadgenerator && ./scripts/deploy.sh

set -euo pipefail
cd "$(dirname "$0")/.."

BRANCH="${1:-refined01}"

echo "==> Ensuring repository ownership for deploy user..."
sudo chown -R "$(id -un)":"$(id -gn)" "$PWD"
sudo chmod -R u+rwX "$PWD"

echo "==> Normalizing writable Laravel directories before git reset..."
sudo mkdir -p "$PWD/storage/app" "$PWD/storage/framework" "$PWD/storage/logs" "$PWD/bootstrap/cache"
sudo chown -R "$(id -un)":"$(id -gn)" "$PWD/storage" "$PWD/bootstrap/cache"
sudo find "$PWD/storage" "$PWD/bootstrap/cache" -type d -exec chmod 775 {} \;
sudo find "$PWD/storage" "$PWD/bootstrap/cache" -type f -exec chmod 664 {} \;

echo "==> Pulling latest code..."
git fetch origin "$BRANCH"
git reset --hard "origin/$BRANCH"

echo "==> Building and starting containers..."
sudo docker compose build --no-cache app
sudo docker compose up -d --remove-orphans
sleep 15

echo "==> Fixing Laravel storage/log permissions..."
sudo docker compose exec -T -u root app sh /var/www/html/scripts/fix-laravel-permissions.sh /var/www/html

echo "==> Running migrations and caches..."
sudo docker compose exec -T app php artisan optimize:clear
sudo docker compose exec -T app php artisan migrate --force
sudo docker compose exec -T app php artisan db:seed --force
sudo docker compose exec -T app php artisan config:cache
sudo docker compose exec -T app php artisan route:cache
sudo docker compose exec -T app php artisan view:cache

echo "==> Deploy finished at $(date -u +%Y-%m-%dT%H:%M:%SZ)"
