FROM php:8.2-cli-alpine

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

RUN curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer