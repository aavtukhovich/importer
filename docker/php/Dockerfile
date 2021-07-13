FROM php:8.0-fpm-alpine

# Install dependencies
RUN apk add --no-cache \
	freetype \
	libpng \
	libjpeg-turbo \
	freetype-dev \
	libpng-dev \
	libjpeg-turbo-dev

RUN docker-php-ext-configure gd \
	--with-freetype \
	--with-jpeg 
RUN apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev

RUN docker-php-ext-install pdo pdo_mysql

# install and enable xdebug
RUN apk add --no-cache $PHPIZE_DEPS \
	&& pecl install xdebug-3.0.4 \
	&& docker-php-ext-enable xdebug

ADD ./www.conf /usr/local/etc/php-fpm.d/

RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

RUN mkdir -p /var/www/html

RUN chown laravel:laravel /var/www/html

WORKDIR /var/www/html
# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
