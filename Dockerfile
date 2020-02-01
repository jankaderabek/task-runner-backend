FROM php:7.4-apache

RUN apt-get update \
 && apt-get install -y git zlib1g-dev \
 && apt-get install -y libzip-dev \
 && apt-get install -y zip \
 && docker-php-ext-install zip \
 && a2enmod rewrite \
 && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
 && mv /var/www/html /var/www/public \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer \
 && echo "AllowEncodedSlashes On" >> /etc/apache2/apache2.conf

RUN pecl install redis && docker-php-ext-enable redis

RUN docker-php-ext-install \
	pdo_mysql
RUN pecl install xdebug

RUN pecl install swoole

RUN touch /usr/local/etc/php/conf.d/swoole.ini && \
    echo 'extension=swoole.so' > /usr/local/etc/php/conf.d/swoole.ini

RUN composer global require hirak/prestissimo

WORKDIR /var/www
