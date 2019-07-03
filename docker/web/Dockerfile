FROM debian:jessie

RUN apt-get update &&\
    apt-get upgrade -y &&\
    apt-get install -y wget ssh apache2 git &&\
    a2enmod rewrite

RUN apt-get update &&\
    apt-get install -y ca-certificates apt-transport-https &&\
    wget -q https://packages.sury.org/php/apt.gpg -O- | apt-key add - &&\
    echo "deb https://packages.sury.org/php/ jessie main" | tee /etc/apt/sources.list.d/php.list

RUN apt-get update &&\
    apt-get install -y unzip zip php7.2 libapache2-mod-php7.2 php7.2-mysql php7.2-fpm php7.2-mbstring php7.2-xml php7.2-curl php7.2-gd

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" &&\
    php composer-setup.php &&\
    php -r "unlink('composer-setup.php');" &&\
    mv composer.phar /usr/local/bin/composer

COPY bobby.conf /etc/apache2/sites-enabled/

RUN usermod -u 1000 www-data &&\
    groupmod -g 1000 www-data

RUN mkdir ~/.ssh && ln -s /run/secrets/host_ssh_key ~/.ssh/id_rsa

COPY uploads.ini /etc/php/7.2/apache2/conf.d/

WORKDIR /var/www/bobby

EXPOSE 8000

CMD ["apache2ctl", "-D", "FOREGROUND"]
