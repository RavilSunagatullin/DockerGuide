FROM php:8.2-apache

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli

COPY index.php ./

EXPOSE 80
CMD ["apache2-foreground"]
