FROM php:8.3-apache

ENV TZ=Europe/Kyiv

RUN apt-get update && \
    apt-get install -y tzdata && \
    ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && \
    echo $TZ > /etc/timezone && \
    echo "date.timezone=$TZ" > /usr/local/etc/php/conf.d/timezone.ini

COPY . /var/www/html/
