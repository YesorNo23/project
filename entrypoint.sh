#!/bin/bash

# ตั้งค่า Port ของ Apache จากตัวแปร $PORT (ถ้าไม่มีจะใช้ 80)
PORT=${PORT:-80}
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# ปรับสิทธิ์โฟลเดอร์
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# รัน Migration
php artisan migrate --force

# เริ่มทำงาน Apache
exec apache2-foreground