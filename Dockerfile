FROM php:8.2-apache

RUN apt-get update && \
    apt-get install -y zlib1g-dev libzip-dev unzip && \
    docker-php-ext-install zip pdo pdo_mysql

RUN a2enmod rewrite

COPY --from=composer /usr/bin/composer /usr/bin/composer