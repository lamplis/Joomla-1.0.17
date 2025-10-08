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
    msmtp \
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
    && echo "short_open_tag = On" >> /usr/local/etc/php/conf.d/joomla.ini \
    && echo "auto_prepend_file = /var/www/html/REFACTO/mysql_compat.php" > /usr/local/etc/php/conf.d/00-compat.ini \
    && echo "session.save_path = /var/www/html/sessions" > /usr/local/etc/php/conf.d/99-session.ini

# Configure msmtp to relay to Mailpit (container name 'mailpit')
RUN printf "account default\nhost mailpit\nport 1025\nfrom noreply@example.test\nauto_from on\n" > /etc/msmtprc \
    && chmod 600 /etc/msmtprc \
    && chown www-data:www-data /etc/msmtprc \
    && echo "sendmail_path = /usr/bin/msmtp -t" > /usr/local/etc/php/conf.d/98-mail.ini

# Set Apache document root
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Prepare writable session path and ensure installer triggers
RUN mkdir -p /var/www/html/sessions \
    && chown -R www-data:www-data /var/www/html/sessions \
    && chmod 777 /var/www/html/sessions

EXPOSE 80

CMD ["apache2-foreground"]
