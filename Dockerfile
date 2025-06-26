FROM php:8.4-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
  autoconf \
  build-essential \
  apt-utils \
  zlib1g-dev \
  libzip-dev \
  unzip \
  zip \
  libmagick++-dev \
  libmagickwand-dev \
  libpq-dev \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  unixodbc \
  unixodbc-dev \
  freetds-dev \
  freetds-bin \
  tdsodbc
  
RUN docker-php-ext-configure gd --with-jpeg --with-freetype

RUN docker-php-ext-configure pdo_dblib --with-libdir=/lib/x86_64-linux-gnu

RUN docker-php-ext-configure soap --enable-soap 

RUN docker-php-ext-install gd intl opcache pdo_mysql pdo_pgsql mysqli zip pdo_dblib soap

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN docker-php-ext-enable pdo_dblib

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#upload
RUN echo "file_uploads = On\n" \
         "memory_limit = 500M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         "max_input_vars=5000\n" \
         > /usr/local/etc/php/conf.d/uploads.ini

# Clear package lists
RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

WORKDIR /var/www/html

RUN a2enmod rewrite
RUN a2enmod headers
RUN a2enmod expires

EXPOSE 80
