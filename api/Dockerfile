FROM php:8.1-fpm

RUN apt-get update \
    && apt-get install -y \
    libzip-dev \
    libfcgi-bin \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install zip mysqli pdo pdo_mysql

WORKDIR /api

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

HEALTHCHECK --interval=3s --timeout=1s \
    CMD cgi-fcgi -bind -connect 127.0.0.1:9000
