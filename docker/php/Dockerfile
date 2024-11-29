FROM php:8.3-fpm

LABEL authors="Miguel Angel Garcia Martin"

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

# Instala las extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libicu-dev \
    libcurl4-openssl-dev \
    libxml2-dev \
    libxslt1-dev \
    zlib1g-dev \
    libfreetype6-dev \
    libwebp-dev \
    libgd-dev \
    && docker-php-ext-install pdo_mysql zip gd curl mbstring xml bcmath intl exif \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Descarga e instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Agrega el directorio de Composer al PATH
ENV PATH="$PATH:/usr/local/bin"

# Copia tu código fuente a la carpeta del contenedor
COPY . /var/www/html
# Copia el archivo de configuración de Xdebug
COPY ./php.ini /usr/local/etc/php/conf.d/php.ini
# Copia el archivo de configuración de Xdebug
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Exponer el puerto 80
EXPOSE 9000

# Comando para ejecutar PHP-FPM
CMD ["php-fpm"]
