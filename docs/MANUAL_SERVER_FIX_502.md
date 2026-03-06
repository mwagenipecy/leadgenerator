# Manual server fix for 502 Bad Gateway

Do these steps **on the server** (SSH as AdminFanikisha). Run every command from `/var/www/leadgenerator`.

---

## Step 1: Go to project directory

```bash
cd /var/www/leadgenerator
```

All following commands assume you are in this directory.

---

## Step 2: Create PHP-FPM config (Unix socket – avoids TCP "Connection refused")

Create the config file so PHP-FPM uses a socket that nginx can connect to:

```bash
sudo mkdir -p docker
sudo tee docker/php-fpm-www.conf << 'EOF'
; Use Unix socket so nginx can connect (avoids TCP Connection refused)
[www]
user = www-data
group = www-data
listen = /var/run/php/php-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0666
pm = dynamic
pm.max_children = 5
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
clear_env = no
catch_workers_output = yes
decorate_workers_output = no
EOF
```

---

## Step 3: Mount this config in docker-compose

Edit the compose file:

```bash
sudo nano docker-compose.yml
```

Find the **app** service and its **volumes** section. It should look like:

```yaml
    volumes:
      - ./.env:/var/www/html/.env:ro
      - ./storage/app:/var/www/html/storage/app
```

**Add these two lines** right after the `.env` line:

```yaml
      - ./docker/php-fpm-www.conf:/usr/local/etc/php-fpm.d/www.conf:ro
      - phpfpm_socket:/var/run/php
```

Under the **nginx** service volumes, add (after the nginx default.conf line):

```yaml
      - phpfpm_socket:/var/run/php:ro
```

Under **volumes:** at the bottom of the file, add:

```yaml
  phpfpm_socket:
```

Also change nginx’s PHP location to use the socket: in `docker/nginx/default.conf` (or in the file you mount for nginx), set:

`fastcgi_pass unix:/var/run/php/php-fpm.sock;`

instead of `fastcgi_pass app:9000;`.

Save (Ctrl+O, Enter) and exit (Ctrl+X).

---

## Step 4: Restart the app container

```bash
sudo docker compose up -d
```

Wait about 10 seconds, then check:

```bash
sudo docker compose ps
```

**leadgenerator-app-1** should show **Up** (not Restarting).

---

## Step 5: Test

```bash
curl -I http://localhost
```

You should see **HTTP/1.1 200 OK** or **HTTP/1.1 302** (not 502).

---

## Step 6: If you still get 502

**A) Confirm the config file exists and is used:**

```bash
ls -la docker/php-fpm-www.conf
sudo docker compose exec app cat /usr/local/etc/php-fpm.d/www.conf | head -15
```

You should see `listen = 9000` in the output.

**B) Check app container is up and PHP-FPM is listening:**

```bash
sudo docker compose ps
sudo docker compose exec app sh -c "ss -tlnp | grep 9000"
```

You should see something like `*:9000` or `0.0.0.0:9000`.

**C) Check nginx and app logs:**

```bash
sudo docker compose logs nginx --tail 20
sudo docker compose logs app --tail 20
```

---

## Summary

| Step | What to do |
|------|------------|
| 1 | `cd /var/www/leadgenerator` |
| 2 | Create `docker/php-fpm-www.conf` with `listen = 9000` (use the block above) |
| 3 | In `docker-compose.yml`, under app volumes, add the mount for `php-fpm-www.conf` |
| 4 | `sudo docker compose up -d` |
| 5 | `curl -I http://localhost` → expect 200 or 302 |

After this, **http://20.164.19.2** should work in your browser (if Azure allows inbound port 80).
