FROM mediaplatform/php-fpm-base-7.4:1.1.0

ENV PHP_UPLOAD_MAX_FILESIZE="265M" \
    PHP_POST_MAX_SIZE="265M" \
    PHP_MEMORY_LIMIT="4096M"
    
# SupervisorD Config

COPY deployment/supervisord/*.conf /etc/supervisor/conf.d/
RUN mkdir -p /var/log/supervisor && chmod 777 /var/log/supervisor
RUN apt-get update && apt-get install -y redis-tools libjpeg-dev && apt-get autoclean -y
RUN docker-php-ext-configure gd --with-jpeg && docker-php-ext-install gd

# Crontab

COPY deployment/cron/football-crontab /etc/cron.d/football

# GoAOP Caching

RUN mkdir -p storage/smpaspect && chmod 775 storage/smpaspect && chown -R www-data:www-data storage/smpaspect

#############################################
# After build procedures
#############################################


# COPY the source code
WORKDIR /var/www/html
COPY --chown=www-data:www-data . /var/www/html
COPY deployment/php.ini /usr/local/etc/php/php.ini

#############################################
# Set correct file permissions
#############################################

#RUN find /var/www/html -type d -exec chmod 755 {} \;
#RUN find /var/www/html -type f -exec chmod 644 {} \;
RUN chmod 777 /var/run 
RUN chmod 777 /etc/environment

ENTRYPOINT env > /etc/environment && crontab /etc/cron.d/football && /usr/bin/supervisord -n
