#!/bin/bash

# ตั้งค่า Port
PORT=${PORT:-80}
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# กำหนดสิทธิ์โฟลเดอร์ storage และ bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# รัน Migration
php artisan migrate --force
php artisan db:seed --force

# เริ่ม Apache
exec apache2-foreground
