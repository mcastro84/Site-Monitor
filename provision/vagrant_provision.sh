#!/bin/bash
export DEBIAN_FRONTEND=noninteractive
echo "Provisioning virtual machine..."

echo "Updating Server"
apt-get update > /dev/null

#Htop
echo "Installing Htop"
apt-get install htop -y > /dev/null

#supervisor
echo "Installing Supervisor"
apt-get install supervisor -y > /dev/null

# TMUX
echo "Installing TMUX"
apt-get install tmux -y > /dev/null

# PHPUNIT
echo "Installing PHPUNIT"
apt-get install phpunit -y > /dev/null

# Git
echo "Installing Git"
apt-get install git -y > /dev/null

# Nginx
echo "Installing Nginx"
add-apt-repository ppa:nginx/stable > /dev/null
sudo apt-get update > /dev/null
apt-get install nginx -y > /dev/null

# PHP
echo "Updating PHP repository"
apt-get install python-software-properties build-essential -y > /dev/null
LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php > /dev/null
apt-get update > /dev/null

echo "Installing PHP"
apt-get install php7.2 -y > /dev/null
apt-get install php7.2-common php7.2-cli php7.2-fpm php7.2-dev -y > /dev/null

echo "Installing PHP extensions"
apt-get install php-pear php7.2-curl php7.2-gd php7.2-mbstring php7.2-zip php7.2-mysql php7.2-xml -y > /dev/null

# Install Mysql
echo "Installing Mysql"
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password 1234'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password 1234'
apt-get install mysql-server -y > /dev/null

#Redis
echo "Installing Redis Server"
apt-get install redis-server -y > /dev/null

# Nginx Configuration
echo "Configuring Nginx"
cp /var/www/provision/nginx_vhost /etc/nginx/sites-available/nginx_vhost > /dev/null
ln -s /etc/nginx/sites-available/nginx_vhost /etc/nginx/sites-enabled/


rm -rf /etc/nginx/sites-available/default
update-rc.d nginx defaults
# Restart Nginx for the config to take effect
service nginx restart > /dev/null

apt-get remove apache2 -y > /dev/null
apt-get remove nginx -y > /dev/null
apt-get install nginx -y > /dev/null
service nginx restart > /dev/null

apt-get install dos2unix
dos2unix /var/www/provision/supervisor.sh

echo "Creating database"
mysql -u root -p1234 -e "create database vm_php7"


curl -Ss https://getcomposer.org/installer | php > /dev/null
sudo mv composer.phar /usr/bin/composer

# Install Laravel dependencies
echo "Configuring Laravel"
composer install -d=/var/www > /dev/null
mv /var/www/env.env.local /var/www/.env
php /var/www/artisan key:generate
php /var/www/artisan migrate
bash /var/www/provision/supervisor.sh

echo "Finished provisioning."