FROM php:8.2-fpm

ENV APP_DIR=/var/www/html \
    YII_ENV=dev \
    YII_DEBUG=1 \
    COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev libicu-dev \
    && docker-php-ext-install intl pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR ${APP_DIR}

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
