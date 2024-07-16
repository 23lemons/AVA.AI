#FROM php:8.1-apache

# Install system dependencies
#RUN apt-get update && apt-get install -y \
#   libzip-dev \
#  zip \
# && docker-php-ext-install zip

# Install PHP extensions
#RUN docker-php-ext-install pdo pdo_mysql

# Copy custom Apache configuration
# COPY ./conf/js-no-cache.conf /etc/apache2/conf-available/js-no-cache.conf

# Enable the new configuration

#RUN a2enmod rewrite

# Copy application source
#COPY . /var/www/html/

#EXPOSE 80

FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache to allow .htaccess overrides and specify landing_page.php as DirectoryIndex
RUN echo "<Directory /var/www/html/> \n\
    Options Indexes FollowSymLinks \n\
    AllowOverride All \n\
    Require all granted \n\
    DirectoryIndex landing_page.php \n\
    </Directory>" > /etc/apache2/conf-available/allowoverride.conf

# Enable the new configuration
RUN a2enconf allowoverride

# Set the ServerName to avoid the warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy application source
COPY . /var/www/html/

# Expose the port 80 for the web server
EXPOSE 80
