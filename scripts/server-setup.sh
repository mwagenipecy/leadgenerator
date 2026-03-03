#!/usr/bin/env bash
# One-time server setup for Azure (Ubuntu). Run as root or with sudo.
# Usage: ssh AdminFanikisha@<IP> 'bash -s' < scripts/server-setup.sh

set -e

APP_DIR="${DEPLOY_PATH:-/var/www/leadgenerator}"
REPO_URL="${REPO_URL:-https://github.com/your-org/lead_generator.git}"  # Set your repo URL

echo "==> Updating system and installing dependencies..."
export DEBIAN_FRONTEND=noninteractive
apt-get update -qq
apt-get install -y -qq ca-certificates curl git unzip

echo "==> Installing Docker..."
if ! command -v docker &>/dev/null; then
    curl -fsSL https://get.docker.com | sh
    systemctl enable docker
    systemctl start docker
fi

echo "==> Installing Docker Compose plugin..."
if ! docker compose version &>/dev/null; then
    apt-get install -y -qq docker-compose-plugin
fi

echo "==> Creating app directory $APP_DIR..."
mkdir -p "$APP_DIR"
cd "$APP_DIR"

if [ ! -d ".git" ]; then
    echo "==> Cloning repository..."
    git clone --depth 1 --branch refined01 "$REPO_URL" .
else
    echo "==> Repository already present, pulling latest..."
    git fetch origin refined01
    git reset --hard origin/refined01
fi

echo "==> Creating .env from example (customize before first deploy)..."
if [ ! -f .env ]; then
    cp .env.example .env
    # Production defaults
    sed -i 's/APP_ENV=local/APP_ENV=production/' .env
    sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
    sed -i 's/APP_URL=.*/APP_URL=http://your-server-ip/' .env
    sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=pgsql/' .env
    sed -i 's/# DB_HOST=.*/DB_HOST=postgres/' .env
    sed -i 's/# DB_PORT=5432/DB_PORT=5432/' .env
    sed -i 's/# DB_DATABASE=.*/DB_DATABASE=laravel/' .env
    sed -i 's/# DB_USERNAME=.*/DB_USERNAME=laravel/' .env
    sed -i 's/# DB_PASSWORD=/DB_PASSWORD=changeme_strong_password/' .env
    echo "Created .env – edit $APP_DIR/.env and set APP_KEY, APP_URL, DB_PASSWORD, then run deploy."
else
    echo ".env already exists, skipping."
fi

echo "==> Creating storage dirs..."
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# So the SSH user (e.g. AdminFanikisha) can git fetch during GitHub Actions deploy
OWNER="${SUDO_USER:-$USER}"
if [ -n "$OWNER" ] && [ "$OWNER" != root ]; then
  echo "==> Setting ownership to $OWNER..."
  chown -R "$OWNER:$OWNER" "$APP_DIR"
fi

echo "==> Server setup complete."
echo "Next steps:"
echo "  1. Edit $APP_DIR/.env (APP_KEY, APP_URL, DB_PASSWORD, etc.)"
echo "  2. Generate key: docker compose run --rm app php artisan key:generate"
echo "  3. Deploy: docker compose up -d && docker compose exec app php artisan migrate --force"
echo "  4. Add GitHub Secrets (DEPLOY_HOST, DEPLOY_USER, DEPLOY_SSH_KEY, DEPLOY_PATH) for CI/CD"
echo "  5. Portainer: https://<server-ip>:9443 (manage Docker containers)"
