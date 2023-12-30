FROM composer:latest AS composer

WORKDIR /app

COPY composer.json composer.lock /app/

RUN composer install --no-autoloader --no-scripts

COPY . /app

RUN composer dump-autoload

FROM php:alpine AS release

WORKDIR /app

COPY --from=composer /app/ /app/

ENTRYPOINT ["/app/docker-entrypoint.sh"]
