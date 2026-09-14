FROM php:8.3-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y msmtp \
    libpng-dev \
    libjpeg-dev

COPY ./conf/msmtprc /etc/msmtprc

RUN chown www-data:www-data /etc/msmtprc && chmod 600 /etc/msmtprc

RUN echo "sendmail_path = /usr/bin/msmtp -t" > /usr/local/etc/php/conf.d/msmtp.ini

RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install gd
