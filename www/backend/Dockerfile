FROM php:8.1-fpm-alpine

# Timezone
RUN ln -sf /usr/share/zoneinfo/Europe/Moscow /etc/localtime

RUN apk update
RUN apk add bash curl git

# INSTALL COMPOSER
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

CMD ["php-fpm"]