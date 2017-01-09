FROM php:alpine


ADD . /var/www
WORKDIR /var/www

EXPOSE 8000
CMD ./bin/console server:run 0.0.0.0:8000
