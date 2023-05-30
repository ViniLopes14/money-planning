FROM php:8.1-fpm AS symfony_php

ENV PHP_VERSION=8.1.8
ENV USER=docker
ENV UID=1000
ENV GID=1000
ENV USER_SYMFONY=symfonypermissions
ENV UID_SYMFONY=1001
ENV GID_SYMFONY=1001
ENV XDEBUG_VERSION=3.1.2

ARG APCU_VERSION=5.1.21

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
	&& curl -sL https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
	&& echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
	# INSTALL
	&& apt-get update -y \
	&& apt-get install -y \ 
		ca-certificates \ 
		$PHPIZE_DEPS \ 
		bash \
		vim \
		nano \
		curl \
		git \
		zip \
		unzip \
		nodejs \ 
		yarn \ 
		chromium \
		acl \
		file \
		gettext \
        procps \
		# DEPENDENCY PHP-EXIF
		libjpeg-dev \
		libpng-dev \
		libwebp-dev \
		libfreetype6-dev \
		libexif-dev \
		# DEPENDENCY PHP-BZ2
		libbz2-dev \
		# DEPENDENCY PHP-PDO_PGSQL
		libpq-dev \
		# DEPENDENCY PHP-INTL
		libicu-dev \
		# DEPENDENCY PHP-XSL
		libxslt-dev \
		# DEPENDENCY PHP-ZIP
		libzip-dev \
		# DEPENDENCY PHP-MBSTRING
		libonig-dev \
		# DEPENDENCY YARN
		libgpgme-dev \
	&& apt-get update -y \
	# COMPOSER
	&& curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
	# PHP-OPCACHE
	&& docker-php-ext-enable opcache \
	# PHP-APCU
	&& pecl install apcu-${APCU_VERSION} \
	&& docker-php-ext-enable apcu \
	# PHP-GNUPG (YARN)
	&& pecl install gnupg \
	&& docker-php-ext-enable gnupg \
	&& pecl clear-cache \
	# PHP-EXIF
	&& docker-php-ext-configure gd \
		--with-jpeg \
		--with-webp \
		--with-freetype \
	&& docker-php-ext-install -j$(nproc) gd exif \
	# PHP-ZIP
	&& docker-php-ext-configure zip \
	&& docker-php-ext-install zip \
	&& docker-php-ext-install \
		# PHP-BZ2
		bz2 \
		# PHP-PDO_PGSQL
		pdo pdo_pgsql \
		# PHP-INTL
		intl \
		# PHP-XSL
		xsl \
		# PHP-MBSTRING
		mbstring \
	# CLEAR CACHE
	&& apt-get -y autoremove \
	&& apt-get clean \
	&& rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
	# ADD GROUP USER DOCKER
	&& addgroup \
		--gid "${GID}" \
		"${USER}" \
	&& adduser \
		--disabled-password \
		--gecos "" \
		--home "/var/www/html" \
		--ingroup "$USER" \
		--no-create-home \
		--uid "$UID" \
		"$USER" \
	# ADD GROUP USER SYMFONYPERMISSIONS
	&& addgroup \
		--gid "${GID_SYMFONY}" \
		"${USER_SYMFONY}" \
	&& adduser \
		--disabled-password \
		--gecos "" \
		--home "/var/www/html" \
		--ingroup "$USER_SYMFONY" \
		--no-create-home \
		--uid "$UID_SYMFONY" \
		"$USER_SYMFONY" \
	&& usermod -a -G ${USER_SYMFONY} www-data \
	&& rm -rf /usr/bin/php \
	&& rm -rf /usr/bin/php8 \
	&& ln -s /usr/local/bin/php /usr/bin/php \
	&& ln -s /usr/local/bin/php /usr/bin/php8 \
	&& mkdir -p "/root/.config/chromium/Crash Reports/pending" \
    && mkdir -p /run/dbus/system_bus_socket

WORKDIR /var/www/html

# XDEBUG
FROM symfony_php AS symfony_php_debug

RUN pecl install xdebug-$XDEBUG_VERSION \
	&& docker-php-ext-enable xdebug

COPY php-container-startup.sh /var/www/html/docker/php-fpm/php-container-setup.sh
RUN chmod +x /var/www/html/docker/php-fpm/php-container-setup.sh

EXPOSE 9000 3001

ENTRYPOINT ["bash", "docker/php-fpm/php-container-startup.sh"]
