FROM node:24-alpine AS asset_builder
WORKDIR /app

COPY ./package.json ./package-lock.json ./
RUN npm install

COPY ./vite.config.ts ./tsconfig.json ./
COPY ./resources ./resources
RUN npm run build


FROM dunglas/frankenphp:php8.5-alpine

ENV SERVER_NAME=:80

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    install-php-extensions \
        mbstring \
        tokenizer \
        xml \
        opcache \
        zip \
        @composer && \
    apk add --no-cache git nano caddy

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY . ./
RUN composer run post-autoload-dump
COPY --from=asset_builder /app/public/build ./public/build

COPY ./Caddyfile /etc/frankenphp/Caddyfile
COPY docker/config/custom-php.ini /usr/local/etc/php/conf.d/zzz-custom-php.ini
COPY docker/config/custom-php-fpm.conf /usr/local/etc/php-fpm.d/zzz-custom-php-fpm.conf

RUN rm -rf ./docker && \
    mkdir -p /var/cache/php/opcache && \
    chmod 700 /var/cache/php/opcache

COPY ./docker-entrypoint.sh /

VOLUME /app/database/sqlite

ENTRYPOINT ["/docker-entrypoint.sh"]
