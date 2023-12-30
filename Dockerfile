FROM composer:latest AS composer

WORKDIR /app

COPY composer.json composer.lock /app/

RUN composer install --no-autoloader --no-dev --no-scripts --no-interaction

COPY . /app

RUN composer install --optimize-autoloader --apcu-autoloader --classmap-authoritative --no-dev --no-interaction

RUN php artisan config:cache && php artisan event:cache && php artisan route:cache

FROM php:alpine AS release

WORKDIR /app

RUN apk add --update --no-cache  make postgresql-client postgresql-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && docker-php-ext-install pgsql pdo_pgsql

COPY --from=composer /app/ /app/

ENTRYPOINT ["/app/docker-entrypoint.sh"]
