FROM php:7.3-fpm-alpine

RUN apk update && apk upgrade


# Install dependencies
RUN echo "http://dl-cdn.alpinelinux.org/alpine/edge/community" >> /etc/apk/repositories && \
	sed -i -e 's/https/http/' /etc/apk/repositories && \
	apk update && apk add --no-cache  \
	dpkg-dev dpkg re2c ca-certificates build-base shadow nano curl \
	unzip autoconf libaio libnsl libc6-compat tzdata git supervisor \
	zip libzip-dev libxml2 libxml2-dev \ 
	oniguruma-dev freetype-dev rabbitmq-c rabbitmq-c-dev  \
	php7 \
	php7-fpm \
	php7-common \
	php7-pdo \
	php7-pdo_mysql \
	php7-mysqli \
	php7-mbstring \
	php7-xml \
	php7-openssl \
	php7-json \
	php7-phar \
	php7-zip \
	php7-gd \
	php7-dom \
	php7-session \
	php7-zlib \
	php7-sodium \
	php7-shmop \
	zip libzip-dev \
	php7-pecl-amqp --repository=http://dl-cdn.alpinelinux.org/alpine/edge/testing/ \
	&& rm -rf /var/cache/apk/* 

# Add gd extension
RUN apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev && \
	docker-php-ext-configure gd \
	--with-gd \
	--with-freetype-dir=/usr/include/ \
	--with-png-dir=/usr/include/ \
	--with-jpeg-dir=/usr/include/ && \
	NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
	docker-php-ext-install -j${NPROC} gd && \
	apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev

# Install Redis, Xdebug
RUN pecl install xdebug redis amqp \
	&& docker-php-ext-install sockets zip exif \
	&& docker-php-ext-enable xdebug redis 

ADD ./www.conf /usr/local/etc/php-fpm.d/

RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

RUN mkdir -p /var/www/html

RUN chown laravel:laravel /var/www/html

WORKDIR /var/www/html
# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
