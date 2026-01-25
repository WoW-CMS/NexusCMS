# Imagen base con PHP 8.4 FPM
FROM php:8.4-fpm

# Instalar dependencias del sistema y extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libpq-dev \
    libcurl4-openssl-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath sockets \
    && docker-php-ext-enable pdo_mysql mbstring zip exif pcntl bcmath sockets

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear directorio de la aplicación
WORKDIR /app

# Copiar archivos de Laravel
COPY . .

# Dar permisos de almacenamiento
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Exponer puerto PHP-FPM
EXPOSE 9000

# Comando por defecto
CMD ["php-fpm"]