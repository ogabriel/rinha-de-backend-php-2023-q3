FROM composer:latest AS composer

FROM php:alpine AS release

RUN apk add --update --no-cache  make postgresql-client postgresql-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && docker-php-ext-install pgsql pdo_pgsql

WORKDIR /app

COPY composer.json composer.lock /app/

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN composer install --no-autoloader --prefer-dist --no-dev --no-scripts --no-interaction

COPY . /app

RUN composer install --optimize-autoloader --apcu-autoloader --classmap-authoritative --no-dev --no-interaction

RUN php artisan config:cache && php artisan event:cache && php artisan route:cache

ENTRYPOINT ["/app/docker-entrypoint.sh"]
