#!/bin/bash
set -e

echo "==> Generando caches de Laravel para producción..."

php artisan config:cache
php artisan route:cache
php artisan event:cache
php artisan view:cache

# Crea el symlink public/storage -> storage/app/public (para fotos de perfil)
php artisan storage:link --quiet 2>/dev/null || true

echo "==> Iniciando Apache..."
exec apache2-foreground
