#!/bin/bash

# ปรับสิทธิ์เพื่อความปลอดภัย (กรณี Render จัดการไฟล์)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# รัน Migrate ทุกครั้งที่คอนเทนเนอร์สตาร์ท 
# (ปลอดภัย เพราะถ้า Migrate ไปแล้ว Laravel จะไม่ทำซ้ำ)
php artisan migrate --force

# *** ห้ามใส่คำสั่ง php artisan db:seed --force ตรงนี้เด็ดขาด ***

# เริ่มการทำงานของ Apache
exec apache2-foreground