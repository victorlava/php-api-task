FROM php:7.4-fpm

RUN apt-get update -yqq && \
    apt-get install -yqq \
    git \
    curl \
    zip \
    unzip \
    gzip \
    libzip-dev \
    libicu-dev \
    nano

RUN docker-php-ext-configure intl 

RUN docker-php-ext-install intl pdo pdo_mysql zip opcache bcmath sockets

RUN pecl install xdebug && docker-php-ext-enable xdebug opcache

RUN echo "opcache.max_accelerated_files = 20000" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer

WORKDIR /app
