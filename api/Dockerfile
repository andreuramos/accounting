FROM php:8.1-fpm

WORKDIR /api
COPY . .

RUN apt-get update \
    && apt-get install -y libzip-dev \
    && docker-php-ext-install zip


EXPOSE 80
