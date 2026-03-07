# Fix "This site can't be reached" / ERR_CONNECTION_TIMED_OUT

When you get **ERR_CONNECTION_TIMED_OUT** at your server IP (e.g. `http://20.164.19.2`), the browser never gets a response. The app and Docker are usually fine; the problem is **network/firewall** between the internet and your VM.

---

## Do this in Azure Portal (open port 80)

1. Go to **https://portal.azure.com** and sign in.
2. Search for your VM (e.g. **fanikisha-app-prod**) or go to **Virtual machines** and open it.
3. In the left menu, click **Networking** (under Settings).
4. Open the **Inbound port rules** tab.
5. Click **+ Add inbound port rule** (or **Create port rule** → **Inbound**).
6. Set:
   - **Source:** Any  
   - **Source port ranges:** *  
   - **Destination:** Any  
   - **Service:** HTTP (this fills port 80) — or set **Destination port ranges:** 80  
   - **Protocol:** TCP  
   - **Action:** Allow  
   - **Priority:** 1010 (or any free number)  
   - **Name:** Allow-HTTP
7. Click **Add**.
8. Wait ~30 seconds, then try **http://20.164.19.2** in your browser again.

If the VM has a **public IP** different from 20.164.19.2, use that IP instead (VM → Overview → Public IP address).

---

## 1. Confirm the IP and that the server is reachable

- **20.164.19.2** – Check in Azure Portal whether this is the VM’s **public** IP. If it’s a private/internal IP, use the VM’s **Public IP** from the Azure Portal (VM → Overview → Public IP address).
- From your laptop: `ping 20.164.19.2` (may be blocked by ICMP but worth trying).
- From your laptop: `curl -v --connect-timeout 5 http://20.164.19.2` – if it hangs then the problem is before the server (firewall/NSG).

## 2. Open port 80 (and 443) in Azure

The most common cause is that **Azure Network Security Group (NSG)** is blocking inbound HTTP/HTTPS.

1. In **Azure Portal** go to your **Virtual Machine** (e.g. `fanikisha-app-prod`).
2. In the left menu open **Networking** (or **Settings → Networking**).
3. Under **Inbound port rules** (or **Inbound rules**), click **Add an inbound port rule** (or **Create port rule → Inbound**).
4. Add a rule for HTTP:
   - **Source:** Any (or `Internet`, or `0.0.0.0/0`)
   - **Source port ranges:** *
   - **Destination:** Any (or leave default)
   - **Service:** HTTP (or **Destination port:** 80)
   - **Protocol:** TCP
   - **Action:** Allow
   - **Priority:** e.g. 1000 (lower = higher priority)
   - **Name:** e.g. `Allow-HTTP`
5. Click **Add** / **OK**.
6. Repeat for HTTPS if you use it:
   - **Destination port:** 443, **Name:** e.g. `Allow-HTTPS`

If you manage the NSG from **Networking → Network security group** (the NSG resource itself), add the same **Inbound security rules** there (port 80, and 443 if needed).

## 3. Confirm Docker is listening on the host

On the server you already confirmed:

```bash
sudo ss -tulpn | grep :80
# Should show docker-proxy on 0.0.0.0:80
```

So the server is listening. If the timeout persists, the block is between the internet and the VM (NSG, or another firewall in front of the VM).

## 4. Optional: test from another network

Try from a different network (e.g. mobile hotspot) or use an online "port checker" for `20.164.19.2` port 80. If it fails from everywhere, it’s the VM/NSG. If it works from one place but not your office/home, it could be your local network or ISP.

## Summary

| Check | What to do |
|-------|------------|
| Correct IP? | Use the VM’s **Public IP** from Azure Portal. |
| Port 80 open? | Add an **Inbound rule** in the VM’s NSG: allow TCP port 80 (and 443 if needed) from the internet. |
| Docker listening? | On server: `ss -tulpn \| grep :80` → docker-proxy on 0.0.0.0:80. |

After opening port 80 (and 443) in the NSG, wait a few seconds and try `http://<public-ip>` again.
