#!/bin/sh

# Create required Laravel storage directories
mkdir -p /app/storage/framework/cache/data
mkdir -p /app/storage/framework/sessions
mkdir -p /app/storage/framework/views
mkdir -p /app/storage/logs

# Ensure SQLite database exists (directory is a mounted volume)
mkdir -p /app/database/sqlite
if [ ! -f /app/database/sqlite/database.sqlite ]; then
    touch /app/database/sqlite/database.sqlite
fi

# Run database migrations
php artisan migrate --force

# Cache config, events, routes, and views for production
php artisan optimize:clear
php artisan optimize

frankenphp run --config /etc/caddy/Caddyfile
