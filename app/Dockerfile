FROM php:8.0-apache AS base

COPY . /var/www/html

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-install session && docker-php-ext-enable session

CMD ["php", "-S", "0.0.0.0:8000"]