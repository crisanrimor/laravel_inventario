FROM php:8.3-fpm-alpine

# Dependencias del sistema necesarias para compilar extensiones
RUN apk add --no-cache \
    bash \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    oniguruma-dev \
    nodejs \
    npm

# Instalar extensiones PHP
RUN docker-php-ext-install \
    bcmath \
    pdo \
    pdo_mysql \
    opcache \
    mbstring

# Instalar Redis
RUN apk add --no-cache autoconf g++ make \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del autoconf g++ make

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan key:generate \
    && php artisan migrate --force \
    && php artisan db:seed --force \
    && php artisan storage:link


EXPOSE $PORT
CMD php artisan serve --host=0.0.0.0 --port=$PORT
