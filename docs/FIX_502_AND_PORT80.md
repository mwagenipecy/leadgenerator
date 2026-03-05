# Fix 502 on server + "Can't connect" from browser

You have **two separate issues**. Fix both.

---

## Issue 1: From your Mac – "Failed to connect to 20.164.19.2 port 80"

**Cause:** Azure (or the VM firewall) is **blocking inbound traffic on port 80**. The server is not reachable from the internet on HTTP.

**Fix – open port 80 in Azure:**

1. Go to **Azure Portal** → your **Virtual Machine** (e.g. `fanikisha-app-prod`).
2. In the left menu click **Networking** (or **Settings** → **Networking**).
3. Open **Inbound port rules** (or **Create port rule** → **Inbound**).
4. **Add rule:**
   - **Source:** Any, or **IP Addresses** and your IP
   - **Source port ranges:** *
   - **Destination:** Any
   - **Service:** **HTTP** (or **Custom** with port **80**)
   - **Protocol:** TCP
   - **Action:** **Allow**
   - **Priority:** e.g. 1010
5. **Save.**

Wait a minute, then from your Mac run:

```bash
curl -I http://20.164.19.2
```

You should get an HTTP response (200, 302, or 502), **not** "Failed to connect".  
If you still get "Failed to connect", check you’re using the correct VM/public IP and that no other firewall (e.g. on the VM) is blocking 80.

---

## Issue 2: On the server – "502 Bad Gateway" (curl http://localhost)

**Cause:** Nginx is running but **cannot reach PHP-FPM** in the app container (often because PHP-FPM was listening only on 127.0.0.1).

**Fix – rebuild app with PHP-FPM listening on 9000:**

On the server:

```bash
cd /var/www/leadgenerator
sudo git fetch origin refined01
sudo git reset --hard origin/refined01
sudo docker compose build --no-cache app
sudo docker compose up -d
sleep 15
curl -I http://localhost
```

You want **HTTP/1.1 200** or **302**, not 502.

**Check PHP-FPM is listening correctly:**

```bash
sudo docker compose exec app sh -c "grep '^listen' /usr/local/etc/php-fpm.d/www.conf"
```

Expected: `listen = 9000` (not `127.0.0.1:9000`).

**If still 502:**

```bash
sudo docker compose ps          # app must be "Up", not "Restarting"
sudo docker compose logs app --tail 30
sudo docker compose logs nginx --tail 20
```

---

## Summary

| Problem | Where | Fix |
|--------|--------|-----|
| Can't connect / timeout to 20.164.19.2:80 | From Mac/browser | Azure NSG: add **Inbound** rule **TCP port 80** (HTTP) |
| 502 Bad Gateway | On server: curl localhost | Rebuild app image (PHP-FPM listen=9000), then `docker compose up -d` |

After both are done, **http://20.164.19.2** should load in your browser.
