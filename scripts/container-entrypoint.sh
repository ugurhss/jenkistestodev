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
if [ "${SKIP_NPM_INSTALL:-}" = "1" ]; then
  echo "[entrypoint] SKIP_NPM_INSTALL=1, skipping npm ci"
else
  # node_modules volume can exist but be empty; check for binaries
  if [ ! -d node_modules ] || [ ! -d node_modules/.bin ]; then
    npm ci || true
  fi
fi

# Ensure node binaries are available in PATH (node_modules/.bin)
export PATH="/app/node_modules/.bin:$PATH"

echo "[entrypoint] Building frontend assets (Vite/Inertia/Vue)..."
if [ "${SKIP_VITE_BUILD:-}" = "1" ]; then
  echo "[entrypoint] SKIP_VITE_BUILD=1, skipping npm run build"
elif [ -f package.json ]; then
  if [ -x /app/node_modules/.bin/vite ]; then
    npm run build || true
  else
    echo "[entrypoint] vite binary missing; skipping build"
  fi
fi

echo "[entrypoint] Generating app key and clearing caches"
php artisan key:generate --force || true
php artisan config:clear || true
php artisan route:clear || true

# Override .env DB values with container environment variables if provided
if [ -f .env ]; then
  echo "[entrypoint] Syncing DB env vars into .env if present"
  for var in DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD; do
    val=$(printenv "$var" 2>/dev/null || true)
    if [ -n "$val" ]; then
      # Use sed to replace or append
      if grep -q "^${var}=" .env; then
        sed -i"" -e "s|^${var}=.*|${var}=${val}|g" .env || sed -i -e "s|^${var}=.*|${var}=${val}|g" .env
      else
        echo "${var}=${val}" >> .env
      fi
    fi
  done
fi

echo "[entrypoint] Starting Laravel dev server"
php artisan serve --host=0.0.0.0 --port=8000
