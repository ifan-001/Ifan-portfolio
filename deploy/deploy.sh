#!/usr/bin/env bash
# Update the live site after you pushed new code:   bash deploy/deploy.sh
# Takes a quick backup, pulls the latest code, installs, migrates and refreshes the caches.
set -euo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")/.."
PHPV="${PHPV:-8.3}"

echo "==> Backup before update"
bash deploy/backup.sh

echo "==> Maintenance mode on"
php artisan down --retry=15 || true
trap 'php artisan up || true' EXIT

echo "==> Pulling latest code"
git pull --ff-only

echo "==> Installing PHP dependencies"
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Migrating the database"
php artisan migrate --force

echo "==> Refreshing caches"
php artisan optimize:clear
php artisan config:cache
php artisan view:cache
php artisan event:cache
# Note: route:cache is intentionally not used (some routes are closures).

sudo systemctl reload "php${PHPV}-fpm"

echo "==> Done. The site is back online."
