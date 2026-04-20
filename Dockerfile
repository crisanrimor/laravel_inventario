FROM php:8.3-fpm-alpine

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install bcmath pdo pdo_mysql opcache mbstring

# Instalar Redis (no viene en docker-php-ext-install)
RUN apk add --no-cache autoconf g++ make \
    && pecl install redis \
    && docker-php-ext-enable redis

# Instalar Node.js y npm
RUN apk add --no-cache nodejs npm

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE $PORT
CMD php artisan serve --host=0.0.0.0 --port=$PORT
