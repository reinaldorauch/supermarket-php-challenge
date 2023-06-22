FROM php:8-alpine

RUN set -ex && apk --no-cache add postgresql-dev
RUN  docker-php-ext-install pdo_pgsql pgsql

WORKDIR /var/www

COPY . .

RUN composer install

CMD php -S 0.0.0.0:8000 -t public
