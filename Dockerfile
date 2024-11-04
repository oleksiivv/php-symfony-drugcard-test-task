FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
        git \
        unzip \
        libzip-dev \
        libicu-dev \
        libpq-dev \
        && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
        pdo_mysql \
        pdo_pgsql \
        intl \
        zip \
        ctype \
        iconv

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install --prefer-dist --no-scripts --no-autoloader && rm -rf /root/.composer

COPY . .

RUN composer dump-autoload --optimize && composer run-script post-install-cmd

RUN a2enmod rewrite

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

RUN sed -i 's!/var/www/html!/var/www/html/public!g' \
  /etc/apache2/sites-available/000-default.conf

CMD ["apache2-foreground"]

