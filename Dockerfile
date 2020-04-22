FROM registry.u-hopper.com/uhopper/ubuntu:16.04

ADD docker_support/run.sh /run.sh

ADD docker_support/wenet.conf /etc/apache2/sites-available/
RUN ln -s /etc/apache2/sites-available/wenet.conf /etc/apache2/sites-enabled/
RUN a2enmod rewrite

# Configure /app folder with the app
RUN rm -fr /var/www && ln -s /app /var/www
ADD / /app

RUN rm -rf /app/vendor
RUN cd /app && php composer.phar install # --no-dev

# copy .htaccess files with correct aliases
ADD docker_support/.htaccess /.htaccess
RUN cp -f /.htaccess /app/frontend/web

RUN sed -i 's/${custom_alias}/frontend/g' /app/frontend/web/.htaccess

RUN sed -ri 's/memory_limit\s*=.*$/memory_limit = 1024M/' /etc/php/7.4/apache2/php.ini

RUN chmod 755 /*.sh

EXPOSE 80

CMD ["/run.sh"]
