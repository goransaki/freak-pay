sudo rm -r /etc/apache2/sites-enabled

sudo rm -r /etc/apache2/sites-available

sudo ln -s /var/www/gobox/apache/ /etc/apache2/sites-enabled

sudo ln -s /var/www/gobox/apache/ /etc/apache2/sites-available

sudo /etc/init.d/apache2 restart