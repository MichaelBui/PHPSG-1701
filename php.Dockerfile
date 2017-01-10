FROM registry.gitlab.com/gigary/docker-base:3.5-armhf

ADD docker/php/ /
ADD main/ /var/www/

RUN apk add --update php7 php7-json php7-iconv php7-mbstring php7-ctype php7-dom php7-pdo php7-session && \
    rm -rf /var/cache/apk/* && \
    ln -sf /usr/bin/php7 /usr/bin/php

WORKDIR /var/www
EXPOSE 8000
CMD bin/console server:run 0.0.0.0:8000