FROM php:8.3-cli

RUN apt-get update && apt-get install -y nodejs npm && \
    apt-get install -y libpq-dev libicu-dev && \
    docker-php-ext-install pdo pdo_pgsql intl

WORKDIR /var/www/html

ADD composer.json ./

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --no-scripts --no-interaction --no-progress --no-suggest

ADD . .

EXPOSE 8000

RUN npm install

RUN npm run build

CMD "php -S 0.0.0.0:8000 -t public"
