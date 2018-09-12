FROM php:7.2-apache

RUN apt-get update && apt-get install -y gnupg \
    && apt-key adv --keyserver hkp://p80.pool.sks-keyservers.net:80 --recv-keys B97B0AFCAA1A47F044F244A07FCC7D46ACCC4CF8 \
    && echo "deb http://apt.postgresql.org/pub/repos/apt/ stretch-pgdg main" > /etc/apt/sources.list.d/pgdg.list \
    && apt-get update && apt-get install -y \
        curl \
        wget \
        git \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        postgresql-client \
        libpq-dev \
    && docker-php-ext-install -j$(nproc) iconv mbstring pgsql pdo_pgsql zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY docker/php.ini /usr/local/etc/php/conf.d/40-custom.ini
COPY ./ /var/www/html
COPY docker/.htaccess /var/www/html
COPY docker/.env /var/www/html
COPY docker/startup.sh /var/www/html
RUN php /usr/local/bin/composer install
COPY docker/wait-for-it.sh /usr/bin/

EXPOSE 80
RUN a2enmod rewrite

CMD ["/var/www/html/startup.sh"]