#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

echo "==> Waiting for MySQL..."
until php -r "
    try {
        new PDO(
            'mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: '3306'),
            getenv('DB_USERNAME'),
            getenv('DB_PASSWORD')
        );
        exit(0);
    } catch (Throwable \$e) {
        exit(1);
    }
" 2>/dev/null; do
    sleep 2
done

if [ ! -f .env ]; then
    echo "==> Creating .env from .env.docker"
    cp .env.docker .env
fi

if [ ! -d vendor ] || [ ! -f vendor/autoload.php ]; then
    echo "==> Installing Composer dependencies"
    composer config --no-plugins audit.block-insecure false
    composer install --no-interaction --prefer-dist
fi

if ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
    echo "==> Generating APP_KEY"
    php artisan key:generate --force --no-interaction
fi

if [ ! -L public/storage ]; then
    echo "==> Linking storage"
    php artisan storage:link --no-interaction
fi

if [ ! -f .docker/initialized ]; then
    echo "==> Running migrations and seeders"
    php artisan migrate --seed --force --no-interaction

    if [ ! -f public/build/manifest.json ]; then
        echo "==> Building frontend assets"
        npm install
        npm run build
    fi

    mkdir -p .docker
    touch .docker/initialized
fi

echo "==> Starting application"
exec "$@"
