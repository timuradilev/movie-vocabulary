FROM php:8.2-fpm-alpine

WORKDIR /app

RUN apk add --no-cache \
    fcgi \
    postgresql-dev \
	  postgresql-libs \
	  libgomp \
	  bash \
	  coreutils \
	  autoconf \
	  g++ \
	  make \
	&& pecl channel-update pecl.php.net  \
	&& pecl install igbinary \
		&& rm -rf /tmp/pear \
		&& docker-php-ext-enable igbinary \
	&& docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
	&& docker-php-ext-install pdo_pgsql pcntl \
	&& apk del autoconf g++ make postgresql-dev \
	&& docker-php-source delete \
	&& rm -rf /usr/share/php \
	&& rm -rf /tmp/*

COPY ./common/php/php-fpm.d /usr/local/etc/php-fpm.d

HEALTHCHECK --interval=5s --timeout=3s --start-period=1s \
    CMD REDIRECT_STATUS=true SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET \
    cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1