FROM php:8.1-fpm

RUN apt-get update \
    && apt-get install -y libzip-dev \
    && docker-php-ext-install zip mysqli pdo pdo_mysql

WORKDIR /api

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 80
