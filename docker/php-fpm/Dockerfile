FROM php:7.4-fpm-alpine

RUN apk update && apk add autoconf g++ make bash git \
    && pecl install xdebug \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable xdebug

RUN docker-php-ext-install pdo pdo_mysql

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

RUN adduser -D -u 1000 appuser

WORKDIR /app

USER appuser

