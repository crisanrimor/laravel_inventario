#!/bin/sh

echo "🚀 Iniciando deploy..."

echo "⚙️ Cacheando configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🗄️ Corriendo migraciones..."
php artisan migrate --force

echo "🌱 Corriendo seeders..."
php artisan db:seed --force

echo "🔗 Creando storage link..."
php artisan storage:link

echo "✅ Listo, iniciando servidor..."
php artisan serve --host=0.0.0.0 --port=$PORT
