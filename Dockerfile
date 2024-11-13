# Usa una imagen base de PHP con FPM y extensiones de soporte para Laravel
FROM php:8.1-fpm

# Instala dependencias del sistema necesarias para Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd pdo pdo_mysql

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establece el directorio de trabajo
WORKDIR /var/www

# Copia todos los archivos del proyecto al contenedor
COPY . .

# Instala las dependencias de Laravel
RUN composer install --optimize-autoloader --no-dev

# Da permisos para los directorios de almacenamiento y caché
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copia el archivo .env.example y genera la clave de la aplicación
RUN cp .env.example .env
RUN php artisan key:generate

# Exponer el puerto 9000 para PHP-FPM
EXPOSE 9000

# Comando de inicio del servidor PHP-FPM
CMD ["php-fpm"]
