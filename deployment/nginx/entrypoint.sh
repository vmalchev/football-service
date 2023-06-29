#!/bin/bash

sed -i "s@#PHP_CONTAINER_NAME#@${PHP_CONTAINER_NAME}@g; s@#IMAGE_API_HOST#@${IMAGE_API_HOST}@g" /etc/nginx/conf.d/default.conf

exec nginx -g 'daemon off;'