# Server setup – one-time (Azure)

The error `cd: /var/www/leadgenerator: No such file or directory` means the app directory and repo do not exist on the server yet. Do this **once** on the server so GitHub Actions can deploy.

---

## Option A: Run from your Mac (recommended)

From your project folder, with the server reachable via SSH:

```bash
# Replace with your actual GitHub repo URL (use SSH if private: git@github.com:OWNER/lead_generator.git)
export REPO_URL="https://github.com/YOUR_ORG/lead_generator.git"
export DEPLOY_PATH="/var/www/leadgenerator"

ssh -i config/fanikisha-app-_key.pem AdminFanikisha@20.164.19.2 "DEPLOY_PATH=$DEPLOY_PATH REPO_URL=$REPO_URL sudo bash -s" < scripts/server-setup.sh
```

That script will create `/var/www/leadgenerator`, clone the repo (branch `refined01`), create `.env`, and create storage dirs.

Then do **Step 2 and 3** below (edit `.env` and first deploy).

---

## Option B: Run directly on the server

SSH into the server, then run these commands.

### 1. Create directory and clone repo

```bash
# SSH into server first:
# ssh -i config/fanikisha-app-_key.pem AdminFanikisha@20.164.19.2

sudo mkdir -p /var/www/leadgenerator
sudo chown $USER:$USER /var/www/leadgenerator
cd /var/www/leadgenerator

# Clone (replace with your repo URL; use git@github.com:... if private and you have deploy key)
git clone --branch refined01 https://github.com/YOUR_ORG/lead_generator.git .
```

**Private repo:** use SSH URL and add the server’s SSH key as a Deploy key in GitHub:

```bash
git clone --branch refined01 git@github.com:YOUR_ORG/lead_generator.git .
```

### 2. Create and edit `.env`

```bash
cd /var/www/leadgenerator
cp .env.example .env
nano .env   # or vim .env
```

Set at least:

- **APP_ENV** = `production`
- **APP_DEBUG** = `false`
- **APP_URL** = `http://20.164.19.2` (or your domain)
- **DB_CONNECTION** = `pgsql`
- **DB_HOST** = `postgres`
- **DB_PORT** = `5432`
- **DB_DATABASE** = `laravel`
- **DB_USERNAME** = `laravel`
- **DB_PASSWORD** = a strong password (e.g. `your_secure_db_password`)

Save and exit.

### 3. First deploy on the server

```bash
cd /var/www/leadgenerator

# Generate Laravel app key
docker compose run --rm app php artisan key:generate

# Create storage dirs
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs
chmod -R 775 storage bootstrap/cache

# Build and start everything
docker compose up -d

# After containers are up (wait ~30 sec if needed)
docker compose exec app php artisan migrate --force
docker compose exec app php artisan storage:link
```

Optional, if you use Passport:

```bash
docker compose exec app php artisan passport:install
```

---

## 4. Confirm

- **App:** open `http://20.164.19.2` in a browser.
- **Portainer:** open `https://20.164.19.2:9443`.

After this, **GitHub Actions** can deploy on every push to `refined01`: it will `cd /var/www/leadgenerator`, pull, build, and run migrations.

---

## If the repo is private

The server must be able to `git fetch` without a password:

1. On the server: `ssh-keygen -t ed25519 -N "" -f ~/.ssh/id_ed25519` (or use existing key).
2. Print the public key: `cat ~/.ssh/id_ed25519.pub`.
3. In GitHub: repo → **Settings** → **Deploy keys** → **Add deploy key** → paste key, allow read-only.
4. Clone with SSH: `git clone --branch refined01 git@github.com:YOUR_ORG/lead_generator.git .`

Then GitHub Actions’ deploy will run `git fetch origin refined01` on the server and it will succeed.
