FROM php:8.3-fpm-alpine
RUN apk add --no-cache \
    nginx \
    mysql-client \
    git \
    curl \
    supervisor \
    openssl \
    bash \
    icu-dev \
    nodejs \
    npm

RUN docker-php-ext-install pdo pdo_mysql opcache bcmath pcntl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /var/www/html
