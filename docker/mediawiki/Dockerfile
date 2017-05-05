FROM ubuntu
MAINTAINER Marc Despland <marc.despland@orange.com>
ADD mediawiki-1.28.2.tar.gz /tmp/
RUN apt-get update &&  apt-get install -y apache2 php libapache2-mod-php php-mcrypt php-mysql php-xml php-imagick imagemagick php-intl php-mbstring
RUN rm /var/www/html/index.html
RUN mv /tmp/mediawiki-1.28.2 /var/www/html/mediawiki
COPY entrypoint.sh /
RUN chown root:root /entrypoint.sh ;chmod u=rwx,go=rx /entrypoint.sh
RUN mkdir -p /var/log/apache2 ; chown root:root /var/log/apache2; chmod a=rwx /var/log/apache2
RUN mkdir -p /var/www/done ; chown root:root /var/www/done; chmod a=rwx /var/www/done
RUN chmod a+rwx /var/www/html/mediawiki
EXPOSE 8080
COPY ports.conf /etc/apache2
COPY 000-default.conf /etc/apache2/sites-available
ENTRYPOINT ["/entrypoint.sh"]
