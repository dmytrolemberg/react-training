FROM php:8.5-cli-alpine

RUN apk add --no-cache bash coreutils git unzip icu-libs libpq sqlite-libs oniguruma \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS linux-headers icu-dev postgresql-dev sqlite-dev oniguruma-dev \
    && docker-php-ext-install pdo_pgsql pdo_sqlite intl mbstring bcmath \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY ./common/php/conf.d /usr/local/etc/php/conf.d
COPY ./development/php/conf.d /usr/local/etc/php/conf.d

COPY ./common/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
