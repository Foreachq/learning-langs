FROM tunet/php:8.1.4-fpm-alpine3.15

ARG LINUX_USER_ID
USER $LINUX_USER_ID