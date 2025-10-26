# Use official PHP 8.2 Apache image
FROM php:8.2-apache

WORKDIR /var/www/html

# Copy project files
COPY . .

# Install composer
RUN apt-get update && apt-get install -y unzip git \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port (optional)
EXPOSE 10000

# Start PHP built-in server using Render's PORT env variable
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t public"]
