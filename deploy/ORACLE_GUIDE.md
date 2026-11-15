# Oracle Cloud (Always Free) এ portfolio live করার গাইড

ফলাফল: `https://আপনার-domain` এ আপনার site, MySQL database, HTTPS, প্রতিদিনের backup — সব free।

> এই গাইডের সব command লিখা আছে। যেখানে `<...>` আছে সেটা আপনার নিজের মান দিয়ে বদলাবেন।

---

## ধাপ ১: Oracle account খোলা

1. https://www.oracle.com/cloud/free/ এ গিয়ে **Start for free** চাপুন।
2. **Home Region** বাছাই করুন। এটা পরে বদলানো যায় না। Bangladesh থেকে কাছের region: **Singapore** বা **Mumbai**।
3. Card দিয়ে verify করতে হবে (Always Free resource ব্যবহার করলে টাকা কাটে না)।

## ধাপ ২: Server (VM) বানানো

Console → **Compute → Instances → Create instance**:

| ঘর | মান |
|---|---|
| Image | **Ubuntu 24.04** (Canonical Ubuntu) |
| Shape | **Ampere → VM.Standard.A1.Flex**, **2 OCPU, 12 GB RAM** |
| Boot volume | 50 GB |
| SSH keys | **Generate a key pair** চাপুন, দুটো key-ই download করে রাখুন |
| Public IP | Assign a public IPv4 address (চালু) |

- **"Out of capacity" এলে:** আলাদা Availability Domain বাছুন, বা কিছুক্ষণ পরে আবার চেষ্টা করুন। ARM server কখনো কখনো ভরা থাকে।
- **তবুও না পেলে:** `VM.Standard.E2.1.Micro` (1 GB RAM) নিতে পারেন, তবে তখন 2 GB swap যোগ করতে হবে:
  ```bash
  sudo fallocate -l 2G /swapfile && sudo chmod 600 /swapfile && sudo mkswap /swapfile && sudo swapon /swapfile
  echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
  ```
- Instance বানানো হলে **Public IP address** টা লিখে রাখুন।
- বাছাই করা IP যাতে না বদলায় তাই Networking → **Reserved public IPs** এ একটা reserved IP নিয়ে instance-এ লাগিয়ে নিন (free)।

## ধাপ ৩: Oracle এর firewall এ port খোলা (গুরুত্বপূর্ণ)

Instance → **Virtual Cloud Network** → Subnet → **Security List** → **Add Ingress Rules**:

| Source CIDR | Protocol | Destination Port |
|---|---|---|
| `0.0.0.0/0` | TCP | `80` |
| `0.0.0.0/0` | TCP | `443` |

(Port 22 আগে থেকেই খোলা থাকে।) এটা না করলে site বাইরে থেকে খুলবে না।

## ধাপ ৪: Domain

**Free option: DuckDNS**
1. https://www.duckdns.org এ GitHub/Google দিয়ে login করুন।
2. একটা নাম নিন, যেমন `hasnain` → `hasnain.duckdns.org`।
3. **current ip** ঘরে আপনার server-এর Public IP বসিয়ে **update ip** চাপুন।

**নিজের domain (`.com` ইত্যাদি) থাকলে:** DNS-এ একটা **A record** বানান: `@` (এবং চাইলে `www`) → server IP।

Domain চালু হয়েছে কিনা দেখতে (আপনার PC থেকে): `ping hasnain.duckdns.org`।

## ধাপ ৫: Server এ ঢোকা

PowerShell এ (key যেখানে download হয়েছে সেখান থেকে):
```powershell
ssh -i "C:\path\to\ssh-key.key" ubuntu@<server-ip>
```
Key file এর permission নিয়ে ত্রুটি এলে: ফাইলটায় right click → Properties → Security → শুধু আপনার user রাখুন।

## ধাপ ৬: Project server এ আনা

**সবচেয়ে ভালো উপায়: private GitHub repo।**

আপনার PC তে (একবার):
1. GitHub এ একটা **Private** repository বানান (যেমন `portfolio`)।
2. ```powershell
   cd D:\portfolio
   git remote add origin https://github.com/<আপনার-username>/portfolio.git
   git push -u origin master
   ```

Server এ (একবার): deploy key বানিয়ে repo-কে read-only access দিন:
```bash
ssh-keygen -t ed25519 -C "portfolio-server" -N "" -f ~/.ssh/id_ed25519
cat ~/.ssh/id_ed25519.pub
```
এই লাইনটা কপি করে GitHub → repo → **Settings → Deploy keys → Add deploy key** এ বসান (Write access দরকার নেই)। তারপর:
```bash
sudo mkdir -p /var/www && sudo chown ubuntu:www-data /var/www
git clone git@github.com:<আপনার-username>/portfolio.git /var/www/portfolio
cd /var/www/portfolio
git config core.fileMode false
```

## ধাপ ৭: Server প্রস্তুত করা (এক command এ)

```bash
cd /var/www/portfolio
bash deploy/server-setup.sh hasnain.duckdns.org
```
(`hasnain.duckdns.org` এর জায়গায় আপনার domain)

এটা যা যা করে: PHP 8.3, Nginx, MySQL, Composer বসায়; database ও user বানায়; Nginx সাজায়; Ubuntu-র ভেতরের firewall এ 80/443 খোলে; fail2ban ও auto security update চালু করে। DB password নিজে বানিয়ে `~/.portfolio_db_pass` এ রাখে।

## ধাপ ৮: `.env` বানানো

```bash
cd /var/www/portfolio
cp deploy/env.production.example .env
nano .env
```
শুধু এই কয়টা বদলান:
- `APP_URL=https://hasnain.duckdns.org` (আপনার domain)
- `DB_PASSWORD=` এর পরে `cat ~/.portfolio_db_pass` এর output বসান
- `MAIL_FROM_ADDRESS` এ আপনার domain

Save: `Ctrl+O`, `Enter`, `Ctrl+X`। তারপর:
```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
chmod 600 .env

# Laravel আর upload এর জন্য লেখার অনুমতি
sudo mkdir -p public/uploads public/images public/resume
sudo chown -R ubuntu:www-data public/images public/uploads public/resume storage bootstrap/cache
sudo chmod -R ug+rwX,o-w storage bootstrap/cache public/images public/uploads public/resume
sudo find storage bootstrap/cache public/images public/uploads public/resume -type d -exec chmod g+s {} +

php artisan migrate --force
```

## ধাপ ৯: আপনার এখনকার content (database + ছবি + CV) server এ আনা

> Profile photo আর feed এর upload git এ নেই (gitignored), তাই এই ধাপ জরুরি।

**আপনার Windows PC তে:**
```powershell
cd D:\portfolio
powershell -ExecutionPolicy Bypass -File deploy\pack-for-server.ps1
scp -i "C:\path\to\ssh-key.key" storage\backups\database.sqlite storage\backups\media.tar.gz ubuntu@<server-ip>:~/
```

**Server এ:**
```bash
cd /var/www/portfolio
tar -xzf ~/media.tar.gz                      # ছবি, CV, uploads বসে যায়
sudo chown -R ubuntu:www-data public/images public/uploads public/resume
sudo chmod -R ug+rwX public/images public/uploads public/resume

# MySQL এ content ঢোকানো (test chat/inquiry/analytics বাদ যায়)
php artisan portfolio:import-sqlite ~/database.sqlite --content-only
```
- `--content-only` ছাড়া চালালে পুরোনো test chat, inquiry, visitor stats-ও চলে আসবে। লাইভ site এ সাধারণত সেগুলো চাইবেন না।
- Admin user (email + password) আপনার আগেরটাই থাকবে।

কাজ শেষে ব্যক্তিগত ফাইলগুলো মুছে ফেলুন:
```bash
rm ~/database.sqlite ~/media.tar.gz
```

তারপর caches বানান:
```bash
php artisan config:cache && php artisan view:cache && php artisan event:cache
sudo systemctl reload php8.3-fpm
```

## ধাপ ১০: HTTPS (free certificate)

Domain server-এর IP তে point করছে নিশ্চিত হয়ে:
```bash
sudo certbot --nginx -d hasnain.duckdns.org --redirect -m <আপনার-email> --agree-tos -n
```
Certbot নিজে নিজে ৯০ দিন পরপর renew করে। পরীক্ষা: `sudo certbot renew --dry-run`।

## ধাপ ১১: Live হলো কিনা যাচাই

Browser এ খুলে দেখুন:
- [ ] `https://আপনার-domain/` — site খুলছে, profile photo আর project এর ছবি আসছে
- [ ] `https://আপনার-domain/up` — "OK"
- [ ] `https://আপনার-domain/portal/login` — আপনার আসল email/password দিয়ে admin এ ঢোকা যাচ্ছে
- [ ] Admin এ একটা ছোট পরিবর্তন করে save করুন, public site এ দেখা যাচ্ছে
- [ ] Admin থেকে ছোট একটা ছবি upload করুন (permission ঠিক আছে কিনা)
- [ ] Site থেকে live chat এ একটা message পাঠান, admin inbox এ আসছে
- [ ] ফোনে খুলে "Install on Phone" ঠিক আছে (HTTPS থাকায় PWA চলবে)

---

## নিয়মিত কাজ

### Code update করলে
আপনার PC তে `git push` করার পর server এ:
```bash
cd /var/www/portfolio && bash deploy/deploy.sh
```
এটা নিজে backup নেয়, maintenance mode চালু করে, নতুন code আনে, migrate করে, cache নতুন করে, তারপর site আবার চালু করে।

### Backup (প্রতিদিন নিজে নিজে)
```bash
crontab -e
```
শেষে এই লাইন যোগ করুন:
```
0 3 * * * /var/www/portfolio/deploy/backup.sh >> $HOME/backups/backup.log 2>&1
```
Backup থাকে `~/backups/` এ (শেষ ১৪ দিন)। **সপ্তাহে অন্তত একবার নিজের PC তে নামিয়ে রাখুন:**
```powershell
scp -i "C:\path\to\ssh-key.key" "ubuntu@<server-ip>:backups/*" D:\portfolio-backups\
```

> **গুরুত্বপূর্ণ:** Oracle Always Free এ অনেকদিন প্রায় কোনো কাজ না করা (idle) instance Oracle কখনো কখনো বন্ধ বা মুছে দিতে পারে। তাই PC তে backup রাখা বাধ্যতামূলক ধরুন। অনেকে account টা **Pay As You Go** তে upgrade করে (Always Free সীমার মধ্যে থাকলে টাকা কাটে না), এতে এই ঝুঁকি কমে।

### Backup থেকে ফেরত আনা
```bash
gunzip -c ~/backups/db-<তারিখ>.sql.gz | mysql -u portfolio -p portfolio
tar -xzf ~/backups/media-<তারিখ>.tar.gz -C /var/www/portfolio
```

### Log দেখা
```bash
tail -n 50 /var/www/portfolio/storage/logs/laravel-$(date +%F).log
sudo tail -n 50 /var/log/nginx/error.log
```

---

## সমস্যা হলে

| লক্ষণ | কারণ ও সমাধান |
|---|---|
| Site একদম খোলে না (timeout) | ধাপ ৩ এর Security List rule আর ধাপ ৭ এর iptables ঠিক আছে কিনা দেখুন: `sudo iptables -L INPUT -n \| grep -E "80\|443"` |
| **502 Bad Gateway** | `sudo systemctl status php8.3-fpm`; না চললে `sudo systemctl restart php8.3-fpm` |
| **500 error** | `storage/logs` এর log দেখুন। প্রায়ই `.env` এর DB password ভুল, অথবা `storage` এ লেখার অনুমতি নেই (ধাপ ৮ এর `chown/chmod` আবার চালান) |
| Login এ **419 Page Expired** | `.env` এ `APP_URL` ঠিক `https://...` কিনা দেখুন, `SESSION_SECURE_COOKIE=true` থাকলে অবশ্যই HTTPS দিয়ে খুলতে হবে। তারপর `php artisan config:cache` |
| Upload এ ত্রুটি | `public/images`, `public/uploads`, `public/resume` এ www-data এর লেখার অনুমতি (ধাপ ৮) |
| `.env` বদলালাম কিন্তু কাজ হচ্ছে না | `php artisan config:cache` আবার চালান |
| Certbot ব্যর্থ | Domain ঠিক IP তে point করছে কিনা, আর port 80 বাইরে থেকে খোলা কিনা দেখুন |
| `git pull` এ conflict (CV বদলেছেন) | Admin থেকে CV upload করলে tracked ফাইল বদলে যায়। `deploy.sh` এর আগে `git stash` করুন, বা CV নতুন নামে upload করুন |

## নিরাপত্তা চেকলিস্ট

- [x] `APP_DEBUG=false`, `APP_ENV=production` (template এ আছে)
- [x] `.env` ও `.git` ফোল্ডার web থেকে বন্ধ (Nginx config এ আছে)
- [x] `fail2ban`, auto security update চালু (setup script এ আছে)
- [x] DB শুধু `localhost` এ, বাইরে থেকে খোলা নয়
- [ ] Admin এর password শক্ত রাখুন (১২+ অক্ষর) এবং অন্য কোথাও ব্যবহার করবেন না
- [ ] SSH key-ফাইল কাউকে দেবেন না, হারালে Oracle console থেকে নতুন key যোগ করুন
