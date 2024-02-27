FROM php:8.2-fpm

RUN apt-get -y update \
    && apt-get -y install curl

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash

RUN apt-get -y install \
        libzip-dev \
        zip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libicu-dev \
        libxml2-dev \
        libxslt-dev \
        libpq-dev \
        symfony-cli \
    && apt-get -y clean

RUN docker-php-ext-install zip

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo_pgsql pdo

RUN docker-php-ext-install soap

RUN docker-php-ext-install xsl

RUN docker-php-ext-install sockets

RUN docker-php-ext-install bcmath

# Install composer
COPY --from=composer/composer:2 /usr/bin/composer /usr/bin/composer
RUN chmod +x /usr/bin/composer

WORKDIR /app
