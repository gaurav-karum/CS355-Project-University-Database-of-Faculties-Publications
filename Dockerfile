FROM php:7.3-apache 
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql