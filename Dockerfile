FROM php:7.3.4-apache

COPY .docker/php/php.ini /usr/local/etc/php/
COPY . /var/www/html
COPY .docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite negotiation
