FROM php:8.4-fpm
WORKDIR /var/www/html
RUN apt-get install -y libzip-dev zip libpng-dev git unzip libonig-dev libpq-dev
RUN docker-php-ext-install pdo_mysql mbstring zip
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# install npm for assets (optional)
RUN apt-get install -y nodejs npm
COPY . /var/www/html
RUN composer install --no-interaction --prefer-dist
RUN php artisan key:generate || true

CMD php artisan serve --host=0.0.0.0 --port=8000