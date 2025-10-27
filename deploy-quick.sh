#!/bin/bash

# Quick deployment script (without fresh migration)
# Use this when you DON'T want to delete database data

echo "🚀 Quick Deployment Started..."

# Install dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run pending migrations only (no data loss)
php artisan migrate --force

# Fix storage link
rm -rf public/storage
php artisan storage:link

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache

echo "✅ Quick Deployment Complete!"
