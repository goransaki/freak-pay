sudo apt-get update
sudo apt-get --assume-yes install php7.0-xml
sudo -H -u vagrant composer self-update
sudo -H -u vagrant composer global require "fxp/composer-asset-plugin:^1.3.1"

echo "CREATE DATABASE IF NOT EXISTS scoutal" | mysql -u root -proot