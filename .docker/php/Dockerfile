FROM php:7.4-cli

ARG uid=1000
ARG login="dev"

COPY "./xdebug.ini" "/usr/local/etc/php/conf.d/xdebug.ini"

RUN apt-get update \
&& apt-get upgrade -y \
&& apt-get install -y \
   wget \
   zip \
   git \
   zlib1g-dev \
   libpng-dev \
&& pecl install \
   xdebug \
&& docker-php-ext-install \
   gd \
&& docker-php-ext-enable \
   xdebug \
   gd \
&& wget -O "/usr/local/bin/composer" "https://getcomposer.org/composer-stable.phar" \
&& chmod -R 755 "/usr/local/bin" \
&& useradd --create-home --user-group --uid=$uid $login
