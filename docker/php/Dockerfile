FROM php:8.0.3-fpm-alpine

RUN apk update && apk upgrade

# Install dependencies
RUN echo "http://dl-cdn.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories && \
	sed -i -e 's/https/http/' /etc/apk/repositories && \
	apk update && apk add --no-cache  \
	dpkg-dev dpkg re2c ca-certificates build-base shadow nano curl \
	unzip autoconf libaio libnsl libc6-compat tzdata git supervisor \
	zip libzip-dev libxml2 libxml2-dev libpng-dev libwebp-dev libjpeg-turbo-dev libjpeg jpeg-dev \
	oniguruma-dev freetype-dev \
	php8 \
	php8-fpm \
	php8-common \
	php8-pdo \
	php8-pdo_mysql \
	php8-mysqli \
	php8-mbstring \
	php8-xml \
	php8-openssl \
	php8-json \
	php8-phar \
	php8-zip \
	php8-gd \
	php8-dom \
	php8-session \
	php8-zlib \
	php8-zip \
	php8-sodium \
	php8-shmop \
	&& rm -rf /var/cache/apk/*

# Install Redis, Xdebug
RUN pecl install xdebug \
	&& pecl install redis \
	&& docker-php-ext-install pdo pdo_mysql zip xml gd mbstring mysqli shmop \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
	&& docker-php-ext-enable xdebug redis pdo_mysql

ADD ./www.conf /usr/local/etc/php-fpm.d/

RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

RUN mkdir -p /var/www/html

RUN chown laravel:laravel /var/www/html

WORKDIR /var/www/html
# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
