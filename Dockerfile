FROM php:8.2-apache

# 1. ติดตั้ง Dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd

# 2. คัดลอก SSL Certificate
COPY ca.pem /etc/ssl/certs/aiven-ca.pem

# 3. เปิดใช้งาน Apache Rewrite Module
RUN a2enmod rewrite

# 4. เปลี่ยน Document Root ไปที่ /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. ตั้งค่า Directory และคัดลอกไฟล์
WORKDIR /var/www/html
COPY . .

# 6. ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 7. จัดการ Permissions และ Entrypoint
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]