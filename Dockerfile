FROM php:8.2-apache

# Instala las extensiones pdo_pgsql para PHP y otras extensiones comunes
RUN apt-get update && apt-get install -y --fix-missing \
        libpq-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        unzip \
        git \
    && apt-get clean \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configura el document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/src

# Cambia el document root del servidor Apache
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf

# Habilita el mod_rewrite para Apache
RUN a2enmod rewrite

# Suprime la advertencia de Apache sobre ServerName
RUN echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf && \
    a2enconf servername

# Copia todo el proyecto dentro del contenedor
COPY . /var/www/html

# Permisos
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Expone el puerto HTTP
EXPOSE 80