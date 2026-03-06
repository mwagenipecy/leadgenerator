# Troubleshooting 502 Bad Gateway

When `curl -I http://localhost` returns **502 Bad Gateway**, nginx is up but cannot get a response from PHP-FPM. Common causes: app container not running, or PHP-FPM socket missing / wrong config.

## 1. Run the diagnostic script (on the server)

```bash
cd /var/www/leadgenerator
bash scripts/diagnose-502.sh
```

This checks: container status, presence of the PHP-FPM socket in both app and nginx containers, PHP-FPM and nginx config, and recent error logs.

## 2. Ensure latest code is deployed

The app container must start successfully so PHP-FPM can create the socket at `/var/run/php/php-fpm.sock`. If the **entrypoint** failed (e.g. `chown: /var/www/leadgenerator: No such file or directory`), the container never reaches `php-fpm` and the socket is never created.

- Pull the latest code (includes entrypoint fix and nginx socket config).
- Rebuild and recreate:

```bash
cd /var/www/leadgenerator
sudo docker compose build --no-cache app
sudo docker compose up -d --force-recreate
```

Wait a few seconds, then:

```bash
curl -I http://localhost
```

## 3. If the app container keeps exiting

See [DEBUG_APP_CONTAINER.md](DEBUG_APP_CONTAINER.md). Run the app in the foreground to see the error:

```bash
sudo docker compose run --rm app 2>&1
```

The last lines usually show the failure (e.g. permission error, missing path).

## 4. If the socket is missing

- Confirm the app container is **Up** (`docker compose ps`).
- Confirm `docker/php-fpm-www.conf` has `listen = /var/run/php/php-fpm.sock` and that this file is mounted in the app service (see `docker-compose.yml`).
- Confirm nginx config uses `fastcgi_pass unix:/var/run/php/php-fpm.sock;` (our `docker/nginx/default.conf` does).

Then restart so the app creates the socket and nginx can connect:

```bash
sudo docker compose up -d --force-recreate app nginx
```
