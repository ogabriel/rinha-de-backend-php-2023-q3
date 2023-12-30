FROM composer:latest AS composer

WORKDIR /app

COPY composer.json composer.lock /app/

RUN composer install --no-autoloader --no-scripts

COPY . /app

RUN composer dump-autoload

FROM php:alpine AS release

WORKDIR /app

RUN apk add --update --no-cache  make postgresql-client postgresql-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && docker-php-ext-install pgsql pdo_pgsql

COPY --from=composer /app/ /app/

ENTRYPOINT ["/app/docker-entrypoint.sh"]
