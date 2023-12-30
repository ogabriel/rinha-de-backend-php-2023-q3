#!/bin/sh

if [ "$1" = 'release' ]; then
    make database-check

    exec php /app/artisan serve --host=0.0.0.0 --port=$PORT
elif [ "$1" = 'migrate_and_release' ]; then
    make database-check

    PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -U $DB_USERNAME -c "CREATE DATABASE $DB_DATABASE"
    php /app/artisan migrate:fresh --force

    exec php /app/artisan serve --host=0.0.0.0 --port=$PORT
fi
