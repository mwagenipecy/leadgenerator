# Deploy: CI/CD + Docker on Azure

## Overview

- **CI/CD**: GitHub Actions runs tests on every push; on **push to `refined01`**, auto-deploys to the Azure server via SSH.
- **Server**: Azure VM (Ubuntu) at `20.164.19.2`, user `AdminFanikisha`, SSH key in repo at `config/fanikisha-app-_key.pem` (do **not** commit this key; use GitHub Secrets).
- **Stack**: Docker Compose (app, nginx, **PostgreSQL**, queue worker, **Portainer**).
- **Documentation**: The `system-documentation` folder is excluded from the Docker image (see `.dockerignore`). Deploy it separately if you need it on the server.

---

## 1. GitHub Secrets

In your repo: **Settings → Secrets and variables → Actions → New repository secret.**

| Secret            | Description                          | Example / value |
|-------------------|--------------------------------------|------------------|
| `DEPLOY_HOST`     | Server IP                            | `20.164.19.2`    |
| `DEPLOY_USER`     | SSH username                         | `AdminFanikisha` |
| `DEPLOY_SSH_KEY`  | Full contents of the SSH private key | Paste entire content of `config/fanikisha-app-_key.pem` |
| `DEPLOY_PATH`     | App directory on server              | `/var/www/lead_generator` |

**Important:** Never commit the `.pem` file. It is in `.gitignore`. For Actions, paste the key contents into `DEPLOY_SSH_KEY`.

**Private repos:** On the server, clone with SSH (`git@github.com:YOUR_ORG/lead_generator.git`) and add the server's SSH public key to the repo as a **Deploy key** (read-only) so `git fetch` during deploy works without a token.

---

## 2. One-time server setup

SSH into the server (from a machine that has the key):

```bash
ssh -i config/fanikisha-app-_key.pem AdminFanikisha@20.164.19.2
```

Then run the setup script. Option A – from your **local** machine (script sent over SSH):

```bash
# From your laptop (replace with your repo URL)
export REPO_URL="https://github.com/YOUR_ORG/lead_generator.git"
export DEPLOY_PATH="/var/www/lead_generator"
ssh -i config/fanikisha-app-_key.pem AdminFanikisha@20.164.19.2 "DEPLOY_PATH=$DEPLOY_PATH REPO_URL=$REPO_URL bash -s" < scripts/server-setup.sh
```

Option B – **on the server** after cloning the repo once:

```bash
sudo bash scripts/server-setup.sh
```

Setup script will:

- Install Docker and Docker Compose
- Clone the repo into `DEPLOY_PATH` (default `/var/www/lead_generator`)
- Create `.env` from `.env.example` with production-style defaults (**PostgreSQL**)

After setup:

1. **Edit `.env` on the server** (e.g. `nano /var/www/lead_generator/.env`):
   - `APP_URL` = your domain or `http://20.164.19.2`
   - `DB_CONNECTION=pgsql`, `DB_HOST=postgres`, `DB_PORT=5432`
   - `DB_PASSWORD` = strong PostgreSQL password (same as in compose)
   - Add any other keys (Passport, mail, etc.)

2. **Generate app key and run first deploy** (on the server):

```bash
cd /var/www/lead_generator
docker compose run --rm app php artisan key:generate
docker compose up -d
docker compose exec app php artisan migrate --force
docker compose exec app php artisan storage:link
# If you use Passport:
docker compose exec app php artisan passport:install
```

3. **Portainer** is started with `docker compose up -d`. Open **https://&lt;server-ip&gt;:9443** to create an admin user and manage containers.

---

## 3. Auto deploy on push (GitHub Actions)

On every **push to `refined01`**:

1. **Test** job runs: `composer install`, `php artisan test`.
2. **Deploy** job runs: SSH to server, `cd DEPLOY_PATH`, `git fetch` / `git reset --hard origin/refined01`, `docker compose build app`, `docker compose up -d`, then migrations and cache.

No extra steps needed after the one-time setup and GitHub Secrets. Push to `refined01` = auto deploy.

To deploy manually: **Actions → CI/CD Deploy → Run workflow.**

---

## 4. Docker layout

| Service     | Role               | Ports              |
|------------|--------------------|--------------------|
| `app`      | Laravel (PHP-FPM)  | internal           |
| `nginx`    | Web server         | 80, 443            |
| `postgres` | PostgreSQL         | 5432 (localhost)   |
| `queue`    | Queue worker       | -                  |
| `portainer`| Container manager  | 9000 (HTTP), 9443 (HTTPS) |

- **App URL:** `http://20.164.19.2` (or your domain pointing to this IP).
- **Portainer:** `https://20.164.19.2:9443` (manage stacks, containers, logs).
- **`.env` on server** must use `DB_CONNECTION=pgsql`, `DB_HOST=postgres`, and `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` matching the `postgres` service in `docker-compose.yml`.

---

## 5. HTTPS (optional)

To use HTTPS:

1. Put certificates on the server (e.g. under `/etc/letsencrypt` or a custom path).
2. Add a second nginx config (e.g. `docker/nginx/ssl.conf`) that listens on 443 and references those certs.
3. Mount that config and the cert directory in `docker-compose.yml` for the `nginx` service.

---

## 6. Troubleshooting

- **SSH key permission denied:** Ensure the key is correct in `DEPLOY_SSH_KEY` (full content, including `-----BEGIN/END ...`).
- **Deploy job fails on `cd`:** Check `DEPLOY_PATH` matches the directory created by `server-setup.sh`.
- **502 Bad Gateway:** Ensure `app` and `postgres` containers are up: `docker compose ps`. Check logs: `docker compose logs app nginx`.
- **DB connection refused:** In `.env`, `DB_HOST` must be `postgres` (Compose service name) and `DB_CONNECTION=pgsql`. Postgres container must be healthy before `app` starts.
- **Portainer not loading:** Ensure port 9443 is open in Azure NSG/firewall. First visit: create admin user.
