FROM php:7.4-fpm
LABEL maintainer="demmonico@gmail.com"
LABEL service="app"
LABEL env="prod"

COPY infra/common/php/php.ini /usr/local/etc/php/
COPY infra/common/php/php-fpm.conf /usr/local/etc/
COPY infra/common/php/www.conf /usr/local/etc/php-fpm.d/

RUN apt-get -y update \
    && apt-get -my install zip \
        git \
        mariadb-client \
        # for Symfony web-skeleton
        libzip-dev \
    # composer
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer \
    # reduce disk space
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# for Symfony web-skeleton
RUN pecl install zip && docker-php-ext-enable zip \
    && docker-php-ext-install pdo_mysql

# setup logs
RUN mkdir -p /var/log/php \
    && touch /var/log/php/php.log \
    && chmod -R 777 /var/log/php \
    && tail -f /var/log/php/php.log >/proc/1/fd/1 &

COPY codebase /app
WORKDIR /app

# fix permissions
RUN if [ -d "./var" ]; then chown -R www-data:www-data ./var; fi

# composer install
RUN composer --no-ansi --no-interaction --prefer-dist install \
    && composer clearcache

CMD ["php-fpm"]
