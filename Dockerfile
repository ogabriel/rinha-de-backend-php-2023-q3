FROM phpswoole/swoole:php8.3-alpine AS release

RUN apk add --update --no-cache make postgresql-client postgresql-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo_pgsql pcntl \
    && docker-php-source delete

WORKDIR /app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /app

RUN composer install --optimize-autoloader --apcu-autoloader --classmap-authoritative --prefer-dist --no-dev --no-interaction

RUN php artisan optimize:clear

RUN mkdir -p /var/run/php

ENTRYPOINT ["/app/docker-entrypoint.sh"]
