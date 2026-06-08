FROM php:8.2-apache

# 1. Instalar la extensión mysqli para la base de datos
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# 2. Habilitar módulos de Apache necesarios para las rutas
RUN a2enmod rewrite

# 3. Copiar todo el proyecto al servidor
COPY . /var/www/html/

# 4. Asegurar que Apache tenga permisos para leer los estilos y las carpetas
RUN chown -w -R www-data:www-data /var/www/html/

EXPOSE 80
