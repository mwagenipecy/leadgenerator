#!/usr/bin/env bash
# Run on the server to deploy (or called by GitHub Actions via SSH).
# Usage: ./scripts/deploy.sh   or   cd /var/www/leadgenerator && ./scripts/deploy.sh

set -e
cd "$(dirname "$0")/.."

echo "==> Pulling latest code..."
git fetch origin refined01
git reset --hard origin/refined01

echo "==> Building and starting containers..."
docker compose build --no-cache app
docker compose up -d

echo "==> Fixing Laravel storage/log permissions..."
docker compose exec -T -u root app sh /var/www/html/scripts/fix-laravel-permissions.sh /var/www/html

echo "==> Running migrations and caches..."
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache

echo "==> Deploy finished at $(date -u +%Y-%m-%dT%H:%M:%SZ)"
