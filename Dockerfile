FROM php:8.3-fpm-alpine

# Dependencias del sistema
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

# Instalar dependencias PHP y Node
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Dar permisos
RUN chmod -R 775 storage bootstrap/cache

# Copiar y dar permisos al entrypoint
COPY entrypoint.sh /app/entrypoint.sh
RUN chmod +x /app/entrypoint.sh

EXPOSE $PORT
CMD ["/app/entrypoint.sh"]
