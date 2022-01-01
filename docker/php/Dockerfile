FROM php:7.4-fpm

RUN apt-get update

RUN apt-get install -y nano curl zip libpq-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install mysqli pdo pdo_mysql

CMD [ "php-fpm" ]

