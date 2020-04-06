FROM ubuntu:16.04

# Update OS.
RUN apt-get update

# Install basic packages.
RUN apt-get install -y sudo software-properties-common python-software-properties curl git htop unzip vim wget python python-pip netcat

# Set working directory.
ENV HOME /root
WORKDIR /root

# in order install php 5.6 on ubuntu 16.04, add the repository
RUN LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php
RUN sudo apt-get update

# Install packages
RUN apt update && DEBIAN_FRONTEND=noninteractive apt-get -y upgrade && DEBIAN_FRONTEND=noninteractive apt-get -y install supervisor git apache2 php7.4 php-pear php7.4-curl php7.4-dev php7.4-gd php7.4-mbstring php7.4-zip php7.4-mysql php7.4-xml

# Add image configuration and scripts
ADD docker_support/start-apache2.sh /start-apache2.sh
ADD docker_support/supervisord-apache2.conf /etc/supervisor/conf.d/supervisord-apache2.conf

ADD docker_support/run.sh /run.sh

ADD docker_support/wenet.conf /etc/apache2/sites-available/
RUN ln -s /etc/apache2/sites-available/wenet.conf /etc/apache2/sites-enabled/
RUN a2enmod rewrite

# Configure /app folder with the app
RUN rm -fr /var/www && ln -s /app /var/www
ADD / /app

# RUN rm -rf /app/vendor
# RUN cd /app && php composer.phar install

# copy .htaccess files with correct aliases
ADD docker_support/.htaccess /.htaccess
RUN cp -f /.htaccess /app/frontend/web

RUN sed -i 's/${custom_alias}/frontend/g' /app/frontend/web/.htaccess

RUN sed -ri 's/memory_limit\s*=.*$/memory_limit = 1024M/' /etc/php/7.4/apache2/php.ini

RUN chmod 755 /*.sh
RUN chmod -R a+w /app/frontend/runtime/logs

ADD https://bitbucket.org/uhopper/docker-wait-links/raw/master/waitlinks.sh /waitlinks.sh
RUN chmod a+x /waitlinks.sh
ENTRYPOINT ["/waitlinks.sh"]

EXPOSE 80

CMD ["/run.sh"]
