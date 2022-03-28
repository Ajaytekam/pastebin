FROM php:7.3-apache

## Install mysql extensions for php
RUN apt-get update && apt-get install -y git
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite

COPY . /var/www/html/webapp/

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80/tcp
EXPOSE 443/tcp
