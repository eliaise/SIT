#!/bin/bash
sudo apt-get update -y && sudo apt-get upgrade -y
sudo apt install apache2 -y
sudo apt install mysql-server -y
sudo apt install php php-common libapache2-mod-php php-cli -y
sudo apt install php-mysqli -y
sudo apt install php-gd -y

sudo mkdir /var/www/empty
sudo mkdir /var/www/ductuscarry.sitict.net
sudo mkdir -p /var/www/internal.ductuscarry.sitict.net/uploads

sudo cp -r ./empty/* /var/www/empty/
sudo cp -r ./public/* /var/www/ductuscarry.sitict.net/
sudo cp -r ./private/* /var/www/internal.ductuscarry.sitict.net/

sudo cp ./hosts /etc/hosts

sudo chown -R www-data:www-data /var/www/
sudo chmod 775 /var/www/internal.ductuscarry.sitict.net/uploads

sudo cp -r ./*.conf /etc/apache2/sites-available/

sudo a2dissite 000-default
sudo a2ensite 001-empty
sudo a2ensite 002-public
sudo a2ensite 003-private

sudo systemctl restart apache2
