FROM php:8.2-fpm

# Instalações básicas
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libonig-dev libpng-dev \
    && docker-php-ext-install pdo_mysql zip mbstring

# Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer
copy ./bankerize /var/www

WORKDIR /var/www

# Comando padrão ao rodar o container
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
