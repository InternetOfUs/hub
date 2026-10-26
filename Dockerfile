# Dependencies are installed from composer.lock with Composer running on PHP 8:
# recent Composer versions refuse to extract tar archives (npm-asset/bootstrap)
# on PHP < 8.0. The lock was resolved for PHP 7.4, hence --ignore-platform-reqs.
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock /app/
RUN composer install --no-dev --no-interaction --ignore-platform-reqs

FROM public.registry.u-hopper.com/uhopper/ubuntu:20.10

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
