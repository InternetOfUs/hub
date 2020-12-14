FROM registry.u-hopper.com/uhopper/ubuntu:16.04

ADD docker-support/run.sh /run.sh

ADD docker-support/sites-available.conf /etc/apache2/sites-available/
RUN ln -s /etc/apache2/sites-available/sites-available.conf /etc/apache2/sites-enabled/
RUN a2enmod rewrite

# Configure /app folder with the app
RUN rm -fr /var/www && ln -s /app /var/www

RUN mkdir /app

ADD composer.json /app
ADD composer.phar /app
RUN cd /app && php composer.phar install --no-dev

ADD / /app



# copy .htaccess files with correct aliases
ADD docker-support/.htaccess /.htaccess
RUN cp -f /.htaccess /app/frontend/web

RUN sed -i 's/${custom_alias}/frontend/g' /app/frontend/web/.htaccess

RUN sed -ri 's/memory_limit\s*=.*$/memory_limit = 1024M/' /etc/php/7.4/apache2/php.ini

env BASE_URL=''

RUN chmod 755 /*.sh

EXPOSE 80

CMD ["/run.sh"]
