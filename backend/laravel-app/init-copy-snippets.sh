#!/bin/sh
# Copy our laravel-snippets into the created Laravel project (idempotent)
set -e
ROOT_DIR=$(pwd)
SNIPPETS_DIR="/var/www/html/laravel-snippets-source"
TARGET_DIR="/var/www/html"

if [ -d "$SNIPPETS_DIR" ]; then
  echo "Copying snippets from $SNIPPETS_DIR to $TARGET_DIR"
  cp -r $SNIPPETS_DIR/* $TARGET_DIR/
  echo "Snippets copied"
else
  echo "No snippets directory found at $SNIPPETS_DIR — be sure to mount snippets into the container or copy them manually from repo/backend/laravel-snippets"
fi

# Ensure storage and cache permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true
