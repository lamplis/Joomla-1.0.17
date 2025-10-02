FROM php:7.4-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    libxslt-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    libssl-dev \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    mysqli \
    pdo_mysql \
    zip \
    intl \
    xml \
    xsl \
    curl \
    mbstring \
    bcmath \
    opcache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set PHP configuration for Joomla 1.0 era (PHP 5.6 compatible)
RUN echo "memory_limit = 128M" >> /usr/local/etc/php/conf.d/joomla.ini \
    && echo "upload_max_filesize = 32M" >> /usr/local/etc/php/conf.d/joomla.ini \
    && echo "post_max_size = 32M" >> /usr/local/etc/php/conf.d/joomla.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/joomla.ini \
    && echo "register_globals = Off" >> /usr/local/etc/php/conf.d/joomla.ini \
    && echo "magic_quotes_gpc = Off" >> /usr/local/etc/php/conf.d/joomla.ini \
    && echo "session.auto_start = Off" >> /usr/local/etc/php/conf.d/joomla.ini \
    && echo "short_open_tag = On" >> /usr/local/etc/php/conf.d/joomla.ini

# Set Apache document root
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Create configuration file
RUN echo "<?php" > /var/www/html/configuration.php \
    && echo "\$mosConfig_offline = '0';" >> /var/www/html/configuration.php \
    && echo "\$mosConfig_host = 'joomla-db';" >> /var/www/html/configuration.php \
    && echo "\$mosConfig_user = 'joomla';" >> /var/www/html/configuration.php \
    && echo "\$mosConfig_password = 'joomlapassword';" >> /var/www/html/configuration.php \
    && echo "\$mosConfig_db = 'joomla_test';" >> /var/www/html/configuration.php \
    && echo "\$mosConfig_dbprefix = 'jos_';" >> /var/www/html/configuration.php \
    && echo "\$mosConfig_lang = 'english';" >> /var/www/html/configuration.php \
    && echo "\$mosConfig_absolute_path = '/var/www/html';" >> /var/www/html/configuration.php \
    && echo "\$mosConfig_live_site = 'http://localhost:8081';" >> /var/www/html/configuration.php \
    && echo "\$mosConfig_sitename = 'Joomla Test Site';" >> /var/www/html/configuration.php \
    && echo "\$mosConfig_secret = 'test_secret_key';" >> /var/www/html/configuration.php \
    && echo "\$mosConfig_debug = '1';" >> /var/www/html/configuration.php \
    && echo "define('RG_EMULATION', 0);" >> /var/www/html/configuration.php

EXPOSE 80

CMD ["apache2-foreground"]
