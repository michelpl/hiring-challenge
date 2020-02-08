FROM php:7.2-apache
RUN apt -yqq update

## Libraries
RUN apt -yqq install libxml2-dev
RUN apt -yqq install zlib1g-dev
RUN apt -yqq install libpng-dev
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libldap2-dev

## Dependencies
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install xml
RUN docker-php-ext-install zip

## GIT
RUN apt-get install git -y
RUN apt-get install vim -y
RUN a2enmod rewrite

RUN cd /var/www/html && git clone https://github.com/michelpl/message-management-api.git .
RUN cd /var/www/html && git checkout develop && git config core.fileMode false

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


##COMPOSER
RUN cd /var/www/html/ && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'c5b9b6d368201a9db6f74e2611495f369991b72d9c8cbd3ffbc63edff210eb73d46ffbfce88669ad33695ef77dc76976') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"

RUN cp /var/www/html/.env.example /var/www/html/.env

## File create and permissions
RUN find /var/www/html/ -type d -exec chmod 775 {} \;
RUN find /var/www/html/ -type f -exec chmod 664 {} \;
RUN chown -R www-data:root /var/www/html/

RUN php composer.phar update -vvv

COPY .env /var/www/html
#COPY migrate.sh /var/www/html
RUN php artisan key:generate
#RUN ./migrate.sh
#RUN cd /var/www/html/ && php artisan migrate:fresh && php artisan passport:install