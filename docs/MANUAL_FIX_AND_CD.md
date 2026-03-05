<!-- # Manual fix and CD – get the site working

## Important: always use the app directory

**All `docker compose` commands must be run from the project directory.**  
If you run them from `~` (home) you get: `no configuration file provided: not found`.

```bash
cd /var/www/leadgenerator
```

Then run any `sudo docker compose ...` command.

---

## One-time manual fix (do this once on the server)

SSH in and run these in order:

```bash
# 1. Go to project (required for every docker command)
cd /var/www/leadgenerator

# 2. Get latest code (includes docker-compose with app command fix)
sudo git fetch origin refined01
sudo git reset --hard origin/refined01

# 3. Ensure .env exists and has DB_HOST=postgres
sudo grep -E '^DB_HOST=' .env
# Must show: DB_HOST=postgres
# If not: sudo sed -i 's/^DB_HOST=.*/DB_HOST=postgres/' .env

# 4. Rebuild and start all containers
sudo docker compose build --no-cache app
sudo docker compose up -d --remove-orphans

# 5. Wait a few seconds, then check app is Up (not Restarting)
sleep 10
sudo docker compose ps
# leadgenerator-app-1 should show "Up" not "Restarting"

# 6. Run migrations and caches
sudo docker compose run --rm app php artisan migrate --force
sudo docker compose run --rm app php artisan config:cache
sudo docker compose run --rm app php artisan route:cache
sudo docker compose run --rm app php artisan view:cache

# 7. Test
curl -I http://localhost
# Expect: HTTP/1.1 200 OK or 302
```

Then open **http://20.164.19.2** in a browser (ensure Azure NSG allows inbound port 80).

---

## If you still get 502

Run from the server:

```bash
cd /var/www/leadgenerator
sudo docker compose ps
```

- If **app** shows **Restarting**: the image may be old. Rebuild and restart:
  ```bash
  cd /var/www/leadgenerator
  sudo docker compose build --no-cache app
  sudo docker compose up -d
  sleep 15
  sudo docker compose ps
  ```
- If **app** is **Up** but curl is still 502: check nginx can reach app:
  ```bash
  sudo docker compose logs nginx --tail 20
  sudo docker compose logs app --tail 20
  ```

---

## What CD does on every push to refined01

When you push to the **refined01** branch, GitHub Actions:

1. SSHs to the server
2. `cd /var/www/leadgenerator`
3. `git fetch` + `git reset --hard origin/refined01`
4. `docker compose build --no-cache app`
5. `docker compose up -d --remove-orphans`
6. Waits 15 seconds
7. Runs migrate, config:cache, route:cache, view:cache

So after each push, the site should update. If the server’s repo or `.env` is wrong, run the **One-time manual fix** above once, then CD will keep things in sync.

---

## Quick reference

| Task              | Command (run from server) |
|-------------------|----------------------------|
| Go to project     | `cd /var/www/leadgenerator` |
| Status            | `sudo docker compose ps` |
| Restart stack     | `sudo docker compose up -d` |
| Rebuild app       | `sudo docker compose build --no-cache app && sudo docker compose up -d` |
| View app logs     | `sudo docker compose logs app --tail 50` |
| Test site         | `curl -I http://localhost` |
| Site URL          | http://20.164.19.2 | -->
