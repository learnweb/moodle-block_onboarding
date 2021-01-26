FROM php:7.2.2-apache
RUN docker-php-ext-install mysqli

# apt-get upgrade -y && \

RUN apt-get update && \
    apt-get install -y git

# Moodle php extensions
# Moodle php extensions: zip
RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-configure zip --with-libzip \
  && docker-php-ext-install zip

# Moodle php extensions: gd
RUN apt-get update -y && apt-get install -y sendmail libpng-dev
RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev \
  && docker-php-ext-install gd

# Moodle php extensions: intl
RUN apt-get -y update \
&& apt-get install -y libicu-dev \
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl

# Moodle
WORKDIR /var/www/html
RUN git clone git://git.moodle.org/moodle.git -b MOODLE_39_STABLE --single-branch

# Moodle data
WORKDIR /var/www
RUN mkdir moodledata
RUN chmod -R 777 moodledata

# config
WORKDIR /var/www/html/moodle
COPY docker/moodle-config.php.dist .
RUN mv moodle-config.php.dist config.php

# Code-Checker Plugin
WORKDIR /var/www/html/moodle
RUN git clone git://github.com/moodlehq/moodle-local_codechecker.git local/codechecker

# WWU Moodle Theme
WORKDIR /var/www/html/moodle
RUN git clone https://github.com/learnweb/moodle-theme_wwu2019.git theme/wwu2019

# Behat
## Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /
