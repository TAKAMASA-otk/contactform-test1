FROM php:8.2-fpm

# 必要な拡張
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip

# Composer を追加
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# タイムゾーンなど
RUN ln -snf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && echo "Asia/Tokyo" > /etc/timezone

# php.ini を読み込む
COPY php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www
