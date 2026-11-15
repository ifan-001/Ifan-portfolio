#!/usr/bin/env bash
# Backs up the MySQL database and every file uploaded from the admin panel.
# Manual:  bash deploy/backup.sh
# Daily at 03:00 (crontab -e):
#   0 3 * * * /var/www/portfolio/deploy/backup.sh >> $HOME/backups/backup.log 2>&1
# Restore the database:  gunzip -c db-XXXX.sql.gz | mysql -u portfolio -p portfolio
set -euo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BACKUP_DIR="${BACKUP_DIR:-$HOME/backups}"
KEEP_DAYS="${KEEP_DAYS:-14}"
STAMP="$(date +%Y%m%d-%H%M%S)"

cd "$APP_DIR"
mkdir -p "$BACKUP_DIR"
chmod 700 "$BACKUP_DIR"

env_get() { grep -E "^$1=" .env | head -1 | cut -d= -f2- | sed -e 's/^"//' -e 's/"$//'; }

CNF="$(mktemp)"
trap 'rm -f "$CNF"' EXIT
chmod 600 "$CNF"
{
  echo "[client]"
  echo "user=$(env_get DB_USERNAME)"
  echo "password=$(env_get DB_PASSWORD)"
  echo "host=$(env_get DB_HOST)"
  echo "port=$(env_get DB_PORT)"
} > "$CNF"

mysqldump --defaults-extra-file="$CNF" --single-transaction --no-tablespaces "$(env_get DB_DATABASE)" \
  | gzip > "$BACKUP_DIR/db-$STAMP.sql.gz"

tar -czf "$BACKUP_DIR/media-$STAMP.tar.gz" public/uploads public/images public/resume 2>/dev/null || true

find "$BACKUP_DIR" -type f \( -name 'db-*.sql.gz' -o -name 'media-*.tar.gz' \) -mtime +"$KEEP_DAYS" -delete

echo "[$(date '+%F %T')] backup ok -> $BACKUP_DIR (db-$STAMP.sql.gz, media-$STAMP.tar.gz)"
