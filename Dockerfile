FROM php:8.0-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Copiar el contenido del proyecto al contenedor
COPY . /var/www/html/

# Dar permisos y habilitar apache mod_rewrite si lo necesitas
RUN a2enmod rewrite