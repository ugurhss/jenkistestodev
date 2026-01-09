#!/bin/sh
set -e

APP_DIR=/app

echo "[entrypoint] Starting container entrypoint"

cd "$APP_DIR"

# Copy env if not present
if [ ! -f .env ]; then
  cp -n .env.example .env || true
fi

echo "[entrypoint] Ensuring Composer dependencies..."
if [ ! -d vendor ] || [ ! -f vendor/autoload.php ]; then
  composer install --no-interaction --prefer-dist --optimize-autoloader || true
fi

echo "[entrypoint] Ensuring NPM dependencies..."
if [ ! -d node_modules ]; then
  npm ci || true
fi

echo "[entrypoint] Building frontend assets (Vite/Inertia/Vue)..."
if [ -f package.json ]; then
  npm run build || true
fi

echo "[entrypoint] Generating app key and clearing caches"
php artisan key:generate --force || true
php artisan config:clear || true
php artisan route:clear || true

echo "[entrypoint] Starting Laravel dev server"
php artisan serve --host=0.0.0.0 --port=8000
