FROM php:8.4-fpm-alpine

# Installer les dépendances et l'extension pdo_mysql
RUN apk add --no-cache \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql
