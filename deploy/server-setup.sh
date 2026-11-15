#!/usr/bin/env bash
# One-time server preparation for Ubuntu 22.04 / 24.04 (Oracle Cloud Always Free, ARM or AMD).
# Usage (as the normal "ubuntu" user, NOT root):
#     bash deploy/server-setup.sh your-domain.example
# Safe to run more than once.
set -euo pipefail

DOMAIN="${1:?Usage: bash deploy/server-setup.sh your-domain.example}"
PHPV="8.3"
APP_DIR="/var/www/portfolio"
DEPLOY_USER="$(id -un)"

if [ "$(id -u)" -eq 0 ]; then
  echo "Run this as the normal user (ubuntu), not as root." >&2
  exit 1
fi

echo "==> Updating the system"
sudo apt-get update -y
sudo DEBIAN_FRONTEND=noninteractive apt-get upgrade -y

echo "==> Installing base tools"
sudo apt-get install -y software-properties-common ca-certificates curl git unzip nginx mysql-server certbot python3-certbot-nginx fail2ban unattended-upgrades

echo "==> Installing PHP ${PHPV}"
if ! apt-cache policy "php${PHPV}-fpm" | grep -q 'Candidate: [0-9]'; then
  sudo add-apt-repository -y ppa:ondrej/php
  sudo apt-get update -y
fi
sudo apt-get install -y \
  "php${PHPV}-fpm" "php${PHPV}-cli" "php${PHPV}-mysql" "php${PHPV}-sqlite3" "php${PHPV}-mbstring" \
  "php${PHPV}-xml" "php${PHPV}-curl" "php${PHPV}-zip" "php${PHPV}-gd" "php${PHPV}-intl" "php${PHPV}-bcmath"

sudo tee "/etc/php/${PHPV}/fpm/conf.d/99-portfolio.ini" >/dev/null <<EOF
upload_max_filesize = 20M
post_max_size = 25M
memory_limit = 256M
max_execution_time = 60
expose_php = Off
opcache.enable = 1
opcache.validate_timestamps = 1
opcache.revalidate_freq = 60
date.timezone = Asia/Dhaka
EOF
sudo systemctl restart "php${PHPV}-fpm"

echo "==> Installing Composer"
if ! command -v composer >/dev/null; then
  EXPECTED="$(curl -fsS https://composer.github.io/installer.sig)"
  php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');"
  ACTUAL="$(php -r "echo hash_file('sha384', '/tmp/composer-setup.php');")"
  [ "$EXPECTED" = "$ACTUAL" ] || { echo "Composer installer checksum mismatch" >&2; exit 1; }
  sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
  rm -f /tmp/composer-setup.php
fi

echo "==> Creating the MySQL database and user"
PASS_FILE="$HOME/.portfolio_db_pass"
if [ ! -f "$PASS_FILE" ]; then
  openssl rand -base64 48 | tr -dc 'A-Za-z0-9' | head -c 28 > "$PASS_FILE"
  chmod 600 "$PASS_FILE"
fi
DB_PASS="$(cat "$PASS_FILE")"
sudo mysql <<SQL
CREATE DATABASE IF NOT EXISTS portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'portfolio'@'localhost' IDENTIFIED BY '${DB_PASS}';
ALTER USER 'portfolio'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON portfolio.* TO 'portfolio'@'localhost';
FLUSH PRIVILEGES;
SQL

echo "==> Preparing ${APP_DIR}"
sudo mkdir -p "$APP_DIR"
sudo chown "$DEPLOY_USER":www-data "$APP_DIR"

echo "==> Nginx site for ${DOMAIN}"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
sed -e "s/__DOMAIN__/${DOMAIN}/g" -e "s/__PHPV__/${PHPV}/g" "$SCRIPT_DIR/nginx.conf" | sudo tee /etc/nginx/sites-available/portfolio >/dev/null
sudo ln -sf /etc/nginx/sites-available/portfolio /etc/nginx/sites-enabled/portfolio
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl enable --now nginx
sudo systemctl reload nginx

echo "==> Opening ports 80 and 443 in the Ubuntu firewall (Oracle images block them by default)"
if sudo iptables -L INPUT -n >/dev/null 2>&1; then
  for PORT in 80 443; do
    if ! sudo iptables -C INPUT -p tcp --dport "$PORT" -m conntrack --ctstate NEW -j ACCEPT 2>/dev/null; then
      LINE="$(sudo iptables -L INPUT --line-numbers -n | awk '/REJECT/ {print $1; exit}')"
      if [ -n "${LINE:-}" ]; then
        sudo iptables -I INPUT "$LINE" -p tcp --dport "$PORT" -m conntrack --ctstate NEW -j ACCEPT
      else
        sudo iptables -A INPUT -p tcp --dport "$PORT" -m conntrack --ctstate NEW -j ACCEPT
      fi
    fi
  done
  sudo DEBIAN_FRONTEND=noninteractive apt-get install -y iptables-persistent >/dev/null
  sudo netfilter-persistent save
fi

echo "==> Automatic security updates and brute-force protection"
sudo systemctl enable --now fail2ban
echo 'APT::Periodic::Update-Package-Lists "1"; APT::Periodic::Unattended-Upgrade "1";' | sudo tee /etc/apt/apt.conf.d/20auto-upgrades >/dev/null

cat <<MSG

Server is ready.

  Database name : portfolio
  Database user : portfolio
  Database pass : saved in ${PASS_FILE}   (cat it when you edit .env)
  App folder    : ${APP_DIR}

Next steps are in deploy/ORACLE_GUIDE.md (step 6 onwards):
  clone the project into ${APP_DIR}, create .env, install, migrate, import data, then run
  sudo certbot --nginx -d ${DOMAIN}
MSG
