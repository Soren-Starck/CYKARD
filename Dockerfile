FROM php:latest

RUN apt-get update && apt-get install -y nodejs npm

WORKDIR /var/www/html

ADD composer.json composer.lock ./

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --no-scripts --no-interaction --no-autoloader --no-dev --prefer-dist --no-progress --no-suggest

ADD . .

EXPOSE 8000

RUN npm install

CMD ["npm", "run", "all"]
