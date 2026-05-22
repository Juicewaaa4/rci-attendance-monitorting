#!/bin/sh

# Fix permissions for storage and cache (critical for www-data user)
mkdir -p /var/www/public/images/faces /var/www/public/rfid_images
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/public/images /var/www/public/rfid_images
chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/public/images /var/www/public/rfid_images

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Run seeders (ignore errors if data already exists)
php artisan db:seed --force || true

# Fix permissions again after artisan commands created new files
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/public/images /var/www/public/rfid_images
chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/public/images /var/www/public/rfid_images

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
nginx -g "daemon off;"
