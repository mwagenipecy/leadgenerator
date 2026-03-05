<!-- # Debug app container crash

Run these **on the server** to see why the app container exits.

## 1. See the container’s CMD (confirm -F is used)

```bash
sudo docker inspect lead_generator_app:latest --format '{{.Config.Cmd}}'
```

Expected: `[php-fpm -F]`. If you see `[php-fpm]` only, the image was built from an old Dockerfile; rebuild after pulling the latest code.

## 2. Run the app in the foreground and capture the error

```bash
cd /var/www/leadgenerator
sudo docker compose run --rm app 2>&1
```

Leave it running; when it exits, the **last lines** are usually the error. Copy that output.

## 3. If nothing useful, run php-fpm directly (skip entrypoint)

```bash
sudo docker compose run --rm --entrypoint "" app php-fpm -F 2>&1
```

Again, copy the last lines when it exits.

## 4. Optional: run a shell and start php-fpm manually

```bash
sudo docker compose run --rm --entrypoint "" app sh
# Inside the container:
php-fpm -F
# (copy any error, then type exit)
```

Share the error output so we can fix the root cause. -->
