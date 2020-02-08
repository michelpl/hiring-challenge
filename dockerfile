FROM php:7.4-apache
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

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


##COMPOSER
RUN cd /var/www/html/ && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" &&\
php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" &&\
php composer-setup.php &&\
php -r "unlink('composer-setup.php');"
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
RUN cd /var/www/html/ && php artisan migrate:fresh