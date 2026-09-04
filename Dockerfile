FROM php:8.2-apache

# 1. ติดตั้ง System Dependencies และ PHP Extensions ที่ Laravel ต้องใช้
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd

# 2. เปิดใช้งาน Apache Rewrite Module (สำคัญมากสำหรับ Routing ของ Laravel)
RUN a2enmod rewrite

# 3. เปลี่ยนแปลง Apache Document Root ให้ชี้ไปที่โฟลเดอร์ /public ของ Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. ตั้งค่า Working Directory และคัดลอก Source Code
WORKDIR /var/www/html
COPY . .

# 5. ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 6. ตั้งสิทธิ์ (Permissions) ให้ Laravel สามารถเขียนไฟล์ได้
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

CMD php artisan db:seed --force && apache2-foreground

# 7. เปิด Port 80
EXPOSE 80
