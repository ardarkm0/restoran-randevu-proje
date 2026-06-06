FROM php:8.2-apache

# Gerekli PHP uzantılarını yükle
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Apache mod_rewrite etkinleştir
RUN a2enmod rewrite

# Apache'nin .htaccess okuyabilmesi için AllowOverride ayarını güncelle
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# PHP ayarları
RUN echo "display_errors = Off" >> /usr/local/etc/php/php.ini \
    && echo "upload_max_filesize = 10M" >> /usr/local/etc/php/php.ini \
    && echo "post_max_size = 10M" >> /usr/local/etc/php/php.ini

# Uygulama dosyalarını kopyala
COPY . /var/www/html/

# Dosya izinlerini ayarla
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# 80 portunu aç
EXPOSE 80
