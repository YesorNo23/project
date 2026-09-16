FROM php:8.2-apache

# 1. ติดตั้ง System Dependencies และ PHP Extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd

# 2. คัดลอกใบรับรอง SSL สำหรับ Aiven
COPY ca.pem /etc/ssl/certs/aiven-ca.pem

# 3. เปิดใช้งาน Apache Rewrite Module
RUN a2enmod rewrite

# 4. เปลี่ยนแปลง Apache Document Root ชี้ไปที่ /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. ปรับแต่งให้ Apache รองรับ Port Dynamic ของ Render
RUN sed -i 's/Listen 80/Listen ${PORT:-80}/g' /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost \*:${PORT:-80}>/g' /etc/apache2/sites-available/000-default.conf

# 6. ตั้งค่า Working Directory และคัดลอก Source Code
WORKDIR /var/www/html
COPY . .

# 7. ติดตั้ง Composer Dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 8. จัดการ Permissions และตั้งค่า Entrypoint
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]