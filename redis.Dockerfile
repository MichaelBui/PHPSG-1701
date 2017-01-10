FROM registry.gitlab.com/gigary/docker-base:3.5-armhf

ADD docker/redis/ /

RUN apk add --update redis && rm -rf /var/cache/apk/*

EXPOSE 6379
CMD redis-server /etc/redis.conf