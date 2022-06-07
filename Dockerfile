FROM php:8.1-fpm-alpine

RUN apk add --no-cache \
      libzip-dev \
      zip \
    && docker-php-ext-install zip

# Install composer
COPY --from=composer/composer:2 /usr/bin/composer /usr/bin/composer
RUN chmod +x /usr/bin/composer

WORKDIR /app
