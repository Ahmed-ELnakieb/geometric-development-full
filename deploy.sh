#!/bin/bash

echo "=========================================="
echo "🚀 Starting Deployment Process"
echo "=========================================="

# Step 1: Install/Update Composer Dependencies
echo ""
echo "📦 Step 1: Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Step 2: Clear all caches
echo ""
echo "🧹 Step 2: Clearing all caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Step 3: Fresh Database Migration with Seeders
echo ""
echo "🗄️  Step 3: Running fresh migrations with seeders..."
echo "⚠️  WARNING: This will DELETE ALL DATA!"
read -p "Are you sure you want to continue? (yes/no): " confirm

if [ "$confirm" = "yes" ]; then
    php artisan migrate:fresh --seed --force
else
    echo "❌ Migration skipped. Running pending migrations only..."
    php artisan migrate --force
fi

# Step 4: Create Storage Link
echo ""
echo "🔗 Step 4: Creating storage symlink..."
# Remove old storage directory/link if exists
if [ -e "public/storage" ]; then
    rm -rf public/storage
fi
php artisan storage:link

# Step 5: Optimize for Production
echo ""
echo "⚡ Step 5: Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 6: Set Permissions
echo ""
echo "🔐 Step 6: Setting correct permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs
chmod -R 775 storage/framework/sessions
chmod -R 775 storage/framework/views
chmod -R 775 storage/framework/cache

# Step 7: Final Checks
echo ""
echo "✅ Step 7: Running final checks..."
php artisan about

echo ""
echo "=========================================="
echo "✅ Deployment Complete!"
echo "=========================================="
echo ""
echo "📝 Summary:"
echo "  - Composer dependencies installed"
echo "  - All caches cleared"
echo "  - Database migrated and seeded"
echo "  - Storage link created"
echo "  - Application optimized"
echo "  - Permissions set"
echo ""
echo "🌐 Your site should now be live!"
echo "=========================================="
