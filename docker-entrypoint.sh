#!/bin/sh

if [ "$1" = 'release' ]; then
    make database-check

    exec php /app/artisan serve --host=0.0.0.0 --port=$PORT
elif [ "$1" = 'migrate_and_release' ]; then
    make database-check

    php /app/artisan migrate:fresh
    php /app/artisan migrate

    exec php /app/artisan serve --host=0.0.0.0 --port=$PORT
fi
