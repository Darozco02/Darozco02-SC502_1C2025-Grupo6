# Usa una imagen base con PHP y Apache
FROM php:8.2-apache

# Instala extensiones necesarias (como mysqli o pdo_mysql)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copia el código fuente al directorio raíz de Apache
COPY ./pages/ /var/www/html/

# Establece permisos adecuados
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Habilita el módulo de reescritura de Apache
RUN a2enmod rewrite