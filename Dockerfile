# Dependencies are installed from composer.lock with Composer running on PHP 8:
# recent Composer versions refuse to extract tar archives (npm-asset/bootstrap)
# on PHP < 8.0. The lock was resolved for PHP 7.4, hence --ignore-platform-reqs.
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock /app/
RUN composer install --no-dev --no-interaction --ignore-platform-reqs

FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

# PHP 7.4 has no upstream releases anymore: it comes from the ondrej/php PPA, whose
# 7.4.33-31 build includes the official backport of the fixes for CVE-2026-109618
# and CVE-2026-109619. Same packages as the old uhopper/ubuntu:20.10 base image,
# except php7.4-dev and php-pear, which are not needed at runtime.
RUN apt-get update \
    && apt-get install -y --no-install-recommends software-properties-common gpg-agent \
    && add-apt-repository -y ppa:ondrej/php \
    && apt-get update \
    && apt-get -y upgrade \
    && apt-get install -y sudo curl git htop unzip vim wget python3 python3-pip netcat-openbsd \
        supervisor apache2 libapache2-mod-php7.4 php7.4-cli php7.4-curl php7.4-gd \
        php7.4-mbstring php7.4-zip php7.4-mysql php7.4-xml \
    && rm -rf /var/lib/apt/lists/*

# Previously shipped in the base image
ADD docker-support/start-apache2.sh /start-apache2.sh
ADD docker-support/supervisord-apache2.conf /etc/supervisor/conf.d/supervisord-apache2.conf

ADD docker-support/run.sh /run.sh

ADD docker-support/sites-available.conf /etc/apache2/sites-available/
RUN ln -s /etc/apache2/sites-available/sites-available.conf /etc/apache2/sites-enabled/
RUN a2enmod rewrite

# Configure /app folder with the app
RUN rm -fr /var/www && ln -s /app /var/www

RUN mkdir /app

COPY --from=vendor /app/vendor /app/vendor

ADD / /app



# copy .htaccess files with correct aliases
ADD docker-support/.htaccess /.htaccess
RUN cp -f /.htaccess /app/frontend/web

RUN sed -i 's/${custom_alias}/frontend/g' /app/frontend/web/.htaccess

RUN sed -ri 's/memory_limit\s*=.*$/memory_limit = 1024M/' /etc/php/7.4/apache2/php.ini

env BASE_URL=''

ARG GIT_REF_ARG

ENV GIT_REF=$GIT_REF_ARG

RUN chmod 755 /*.sh

EXPOSE 80

CMD ["/run.sh"]
