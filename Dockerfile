# Use the official PHP 8.3 Apache image from the dockerhub
FROM composer as builder

# Copy the PHP script into the image
COPY index.php /var/www/html/

# Copy the composer files into the image
COPY composer.lock /var/www/html/
COPY composer.json /var/www/html/

# Install dependencies
WORKDIR /var/www/html/
RUN composer install

FROM php:8.2.8-apache as runtime

COPY --from=builder /var/www/html/ /var/www/html/
