FROM --platform=linux/amd64 composer:2.2.1 as builder
WORKDIR /app/
COPY composer.* ./
RUN composer install

FROM --platform=linux/amd64 php:7.3-apache
COPY --from=builder /app/vendor /var/www/html/vendor

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini && \
    docker-php-ext-install mysqli && \
    docker-php-ext-enable mysqli
RUN a2enmod rewrite
WORKDIR /var/www/html
COPY . .
RUN chmod 777 uploads
