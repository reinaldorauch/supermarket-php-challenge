FROM php:8-alpine

RUN set -ex && apk --no-cache add postgresql-dev curl
RUN  docker-php-ext-install pdo_pgsql pgsql
RUN curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install

CMD php -S 0.0.0.0:8000 -t public
