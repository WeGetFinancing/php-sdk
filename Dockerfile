FROM php:8.1-fpm-alpine

RUN apk add --no-cache \
      libzip-dev \
      zip \
    && docker-php-ext-install zip

RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && apk del pcre-dev ${PHPIZE_DEPS}

# Install composer
COPY --from=composer/composer:2 /usr/bin/composer /usr/bin/composer
RUN chmod +x /usr/bin/composer

WORKDIR /app
