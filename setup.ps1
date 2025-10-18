# Laravel CMS Setup Script
Write-Host "Setting up Laravel CMS with MySQL database..." -ForegroundColor Green

# Update .env file for MySQL database
Write-Host "Configuring .env file for MySQL..." -ForegroundColor Yellow
$envContent = Get-Content .env
$envContent = $envContent -replace '^DB_CONNECTION=sqlite', 'DB_CONNECTION=mysql'
$envContent = $envContent -replace '^# DB_HOST=127.0.0.1', 'DB_HOST=127.0.0.1'
$envContent = $envContent -replace '^# DB_PORT=3306', 'DB_PORT=3306'
$envContent = $envContent -replace '^# DB_DATABASE=laravel', 'DB_DATABASE=test'
$envContent = $envContent -replace '^# DB_USERNAME=root', 'DB_USERNAME=root'
$envContent = $envContent -replace '^# DB_PASSWORD=', 'DB_PASSWORD='
$envContent | Set-Content .env

Write-Host "Installing Composer dependencies..." -ForegroundColor Yellow
composer install

Write-Host "Generating application key..." -ForegroundColor Yellow
php artisan key:generate

Write-Host "Running database migrations..." -ForegroundColor Yellow
php artisan migrate --force

Write-Host "Installing and compiling frontend assets..." -ForegroundColor Yellow
npm install
npm run build

Write-Host "Setup completed successfully!" -ForegroundColor Green
Write-Host "You can now start the development server with: php artisan serve" -ForegroundColor Cyan
