# Usar la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones de PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev zip unzip git \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Instalar Composer para manejar las librerías
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar todo nuestro código a la carpeta pública de Apache
COPY . /var/www/html/

# Dar permisos a Apache para leer los archivos
RUN chown -R www-data:www-data /var/www/html

# Ejecutar Composer para instalar Eloquent y Dotenv (excluyendo el .env)
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto 80 para que Render pueda enrutar el tráfico
EXPOSE 80