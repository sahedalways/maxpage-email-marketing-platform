#!/usr/bin/env bash
set -e

cd /var/www/maxpage

echo "=== [deploy] pulling latest code ==="
git pull --ff-only origin main

echo "=== [deploy] clearing stale caches ==="
rm -f /var/www/maxpage/bootstrap/cache/*.php

echo "=== [deploy] installing dependencies ==="
export COMPOSER_ALLOW_SUPERUSER=1
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

echo "=== [deploy] running migrations ==="
php artisan migrate --force

echo "=== [deploy] clearing & caching config ==="
php artisan config:cache || echo "WARN: config cache failed (site still works)"
php artisan route:cache || echo "WARN: route cache failed (site still works)"
php artisan view:cache || echo "WARN: view cache failed (site still works)"

echo "=== [deploy] fixing permissions ==="
chown -R www-data:www-data /var/www/maxpage
chmod -R 775 /var/www/maxpage/storage /var/www/maxpage/bootstrap/cache

echo "=== [deploy] restarting queue workers ==="
supervisorctl restart maxpage-worker:*

echo "=== [deploy] done ==="
