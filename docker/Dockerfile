FROM appkr/lemp-base:latest

ENV DEBIAN_FRONTEND=noninteractive

COPY myapp.conf /etc/nginx/sites-available/
RUN rm /etc/nginx/sites-available/default \
    && rm /etc/nginx/sites-enabled/default \
    && ln -nfs /etc/nginx/sites-available/myapp.conf /etc/nginx/sites-enabled/

WORKDIR /var/www/myapp
VOLUME ["/var/www/myapp", "/var/lib/mysql"]
EXPOSE 80 9991 3306
