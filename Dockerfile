# Use the official PHP image with Apache
FROM php:8.2-apache

# Install MySQLi extension for database support
RUN docker-php-ext-install mysqli

# Optional: enable Apache mod_rewrite if needed
RUN a2enmod rewrite

# Copy all your project files to the web root
COPY . /var/www/html/

# Set working directory (optional, makes paths cleaner)
WORKDIR /var/www/html

# Expose default Apache port
EXPOSE 80
