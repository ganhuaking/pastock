FROM ghcr.io/mileschou/composer:8.0 as vendor

WORKDIR /source

COPY composer.* ./
RUN composer install --prefer-dist --no-progress

FROM php:8.0-alpine

WORKDIR /usr/src/app

COPY --from=vendor /source/vendor /usr/src/app/vendor
COPY . .

ENV APP_NAME=Pastock \
    APP_ENV=local \
    APP_DEBUG=false \
    LOG_CHANNEL=stdout \
    CACHE_DRIVER=file \
    QUEUE_CONNECTION=sync

ENTRYPOINT ["php", "/usr/src/app/artisan"]
CMD ["list"]