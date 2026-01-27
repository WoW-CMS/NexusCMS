FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libpq-dev \
    libcurl4-openssl-dev \
    libxml2-dev \
    nginx \
    openssl \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath sockets \
    && docker-php-ext-enable pdo_mysql mbstring zip exif pcntl bcmath sockets

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Nginx config
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/nginx/default-ssl.conf /etc/nginx/conf.d/default-ssl.conf

# Entrypoint para ejecutar php-fpm y nginx
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

CMD ["/entrypoint.sh"]
