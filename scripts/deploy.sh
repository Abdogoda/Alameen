#!/usr/bin/env bash

# Exit immediately if a command exits with a non-zero status
set -e

echo "--------------------------------------------------------"
echo "🚀 Starting Deployment: $(date)"
echo "--------------------------------------------------------"

# 1. Put Laravel into maintenance mode (graceful for visitors)
if [ -f artisan ]; then
    echo "🔒 Enabling maintenance mode..."
    php artisan down --retry=60 || true
fi

# 2. Clean up default hostinger file if present
rm -f default.php

# 3. Discard any local unstaged changes and pull latest main
echo "📥 Fetching and pulling latest changes from GitHub..."
git fetch origin main
git reset --hard origin/main

# 4. Ensure .env exists
if [ ! -f .env ]; then
    echo "⚙️ Creating .env file..."
    cp .env.example .env
    php artisan key:generate --force || true
fi

# 5. Install / update Composer dependencies (production optimized)
echo "📦 Installing Composer dependencies (no-dev, optimized)..."
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# 6. Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force || echo "⚠️ Migrations skipped (check DB credentials in .env)"

# 7. Clear old caches and warm up production caches
echo "⚡ Caching configurations, routes, and views..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 8. Ensure public storage symlink exists
echo "🔗 Ensuring storage symlink..."
if [ ! -L public/storage ]; then
    rm -rf public/storage
    ln -sfn ../storage/app/public public/storage || true
    echo "✅ Storage symlink ensured via bash"
fi
php artisan storage:link || true

# 9. Bring Laravel back online
if [ -f artisan ]; then
    echo "🔓 Disabling maintenance mode..."
    php artisan up
fi

echo "--------------------------------------------------------"
echo "✅ Deployment finished successfully at $(date)"
echo "--------------------------------------------------------"
