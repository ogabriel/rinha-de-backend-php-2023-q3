#!/bin/sh

if [ "$1" = 'release' ]; then
    make database-check

    exec supervisord -c /app/supervisord.conf
elif [ "$1" = 'migrate_and_release' ]; then
    make database-check

    PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -U $DB_USERNAME -c "CREATE DATABASE $DB_DATABASE"
    php /app/artisan migrate:fresh --force

    exec supervisord -c /app/supervisord.conf
fi
