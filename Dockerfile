FROM php:8.0.5-fpm-alpine

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN set -ex \
        && apk --no-cache add postgresql-dev yarn\
        && docker-php-ext-install pdo pdo_pgsql

RUN curl -sL https://deb.nodesource.com/setup_16.x
RUN apk add --update nodejs npm

COPY . /var/www/html

WORKDIR /var/www/html
RUN composer install
