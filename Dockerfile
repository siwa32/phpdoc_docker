FROM php:8.3-cli-alpine

RUN apk update && apk add git make openjdk11

RUN docker-php-ext-install \
    opcache

EXPOSE 80

ENTRYPOINT ["php", "-S", "0.0.0.0:80"]
CMD ["-t", "/workspaces/output/php-chunked-xhtml/"]
