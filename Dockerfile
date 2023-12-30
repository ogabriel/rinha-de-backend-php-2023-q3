FROM php:fpm-alpine AS release

RUN apk add --update --no-cache make postgresql-client postgresql-dev nginx supervisor

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && docker-php-ext-install pgsql pdo_pgsql

WORKDIR /app

COPY composer.json composer.lock /app/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-autoloader --prefer-dist --no-dev --no-scripts --no-interaction

COPY . /app

RUN composer install --optimize-autoloader --apcu-autoloader --classmap-authoritative --no-dev --no-interaction

RUN php artisan cache:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan optimize

COPY php-fpm.conf /usr/local/etc/php-fpm.conf

RUN mkdir -p /var/run/php

FROM release AS release1

COPY nginx-app-1.conf /etc/nginx/nginx.conf

ENTRYPOINT ["/app/docker-entrypoint.sh"]

FROM release AS release2

COPY nginx-app-2.conf /etc/nginx/nginx.conf

ENTRYPOINT ["/app/docker-entrypoint.sh"]
