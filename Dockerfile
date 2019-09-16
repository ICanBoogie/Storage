FROM php:7.1.32-cli-stretch

RUN apt-get update && \
    pecl channel-update pecl.php.net && \
    pecl install apcu redis && \
    docker-php-ext-enable apcu opcache redis && \
    docker-php-source delete

RUN echo '\
apc.enable_cli=On\n\
' >> /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini
