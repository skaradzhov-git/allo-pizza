#!/usr/bin/env bash
set -euo pipefail

echo "==> Installing PHP dependencies..."
composer install

if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

echo "==> Running migrations and seeders..."
php artisan migrate --seed

echo "==> Linking storage..."
php artisan storage:link

echo "==> Installing frontend dependencies..."
npm install
npm run build

echo "==> Running tests..."
php artisan test

echo ""
echo "Done! Start the server with: php artisan serve"
echo "Admin panel: http://localhost:8000/admin"
echo "Login: admin@pizzeria.local / password"
