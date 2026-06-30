#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

echo "==> Installing PHP dependencies"
composer install --no-dev --optimize-autoloader --no-interaction

if command -v npm >/dev/null 2>&1; then
    echo "==> Building frontend assets"
    npm ci
    npm run build
else
    echo "==> npm not found; skipping frontend build"
    echo "    Run npm run build locally and upload public/build if your hosting has no Node.js."
fi

echo "==> Running database migrations"
php artisan migrate --force

echo "==> Linking storage"
php artisan storage:link || true

echo "==> Caching Laravel bootstrap files"
php artisan optimize

echo "==> Deployment complete"
