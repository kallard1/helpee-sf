FROM php:7.3-fpm

ENV TZ=Europe/Paris
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt-get install -y \
      libpq-dev \
      libxml2-dev

RUN docker-php-ext-install \
        ctype \
        iconv \
        json \
        opcache \
        pdo_pgsql \
        pgsql \
        session \
        simplexml \
        tokenizer

RUN pecl install \
        apcu \
        redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable \
        apcu \
        redis
