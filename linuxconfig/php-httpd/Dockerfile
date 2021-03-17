FROM php:5.6-apache

# Install gd, iconv, mbstring, mcrypt, mysql, soap, sockets, zip, and zlib extensions
# see example at https://hub.docker.com/_/php/
RUN apt-get update && apt-get install -y \
        libbz2-dev \
        libfreetype6-dev \
        libgd-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng16-16 \
        libxml2-dev \
        zlib1g-dev \
    && docker-php-ext-install iconv mbstring mcrypt soap sockets zip \
    && docker-php-ext-configure gd --enable-gd-native-ttf --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd \
    && docker-php-ext-configure mysql --with-mysql=mysqlnd \
    && docker-php-ext-install mysql
