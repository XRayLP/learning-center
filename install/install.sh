#!/bin/bash
BLUE='\033[0;34m'
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m'
echo -e "${BLUE}Installationsvorgang beginnt.${NC}"
#Root-Überprüfung
if (($EUID != 0));
  then echo -e "${RED}Keine Root-Rechte. Bitte Script als Root ausführen.${NC}"
  exit
fi
www="/var/www/"

#Paketinstallationen
echo -e "${BLUE}Vorausgesetzte Pakete werden installiert: ${NC}"
echo -e "${BLUE}PHP 7.1 wird mit allen nötigen Erweiterungen installiert.${NC}"
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt-get install php7.1 php7.1-cli php7.1-gd php7.1-xml php7.1-intl php7.1-mbstring php7.1-mcrypt

echo -e "${BLUE}Apache Webserver wird installiert.${NC}"
sudo apt-get install apache2 libapache2-mod-php7.1

echo -e "${BLUE}MySQL Datenbankserver wird installiert.${NC}"
sudo apt-get install mysql-server php-mysql

echo -e "${BLUE}Pakete werden Konfiguriert.${NC}"
sudo sed -i.bak 's/;extension=php_intl.dll/extension=php_intl.dll/g' /etc/php/7.1/apache2/php.ini
sudo sed -i.bak 's!/var/www/html!/var/www/contao/web!g' /etc/apache2/sites-enabled/000-default.conf
sudo service apache2 restart
#INTL
echo -e "${BLUE}Contao Installation${NC}"
mkdir tmp
wget -O tmp/contao.tar.gz https://download.contao.org/4.5.8/tar
tar -xzf tmp/contao.tar.gz -C $www
sudo mv -R ${www}contao-4.5.8/ ${www}contao/
wget https://getcomposer.org/composer.phar ${www}contao/


echo -e "${BLUE}MySQL Datenbank erstellen:${NC}"
PASSWDDB="$(openssl rand -base64 12)"
read -p "Wie soll die Datenbank heißen?: " MAINDB

# If /root/.my.cnf exists then it won't ask for root password
if [ -f /root/.my.cnf ]; then

    mysql -e "CREATE DATABASE ${MAINDB} /*\!40100 DEFAULT CHARACTER SET utf8mb4 */;"

# If /root/.my.cnf doesn't exist then it'll ask for root password
else
    mysql -e "CREATE DATABASE ${MAINDB} /*\!40100 DEFAULT CHARACTER SET utf8mb4 */;"

fi

echo -e "${BLUE}Contao Konfiguration unter http://localhost/contao/install ${NC}"
read -p "Zum fortfahren 'enter' drücken... "
sudo cp config.yml ${www}contao/app/config
sudo mv ${www}contao/composer.json ${www}contao/composer.json.bak
sudo cp composer.json ${www}contao/
sudo cp -R ../src/ ${www}contao/
sudo rm ${www}contao/src/ContaoMangerPlugin.php
sudo cp ContaoManagerPlugin.php ${www}contao/app
sudo chmod -R 777 ${www}contao/
sudo php ${www}contao/composer.phar update --optimize-autoloader

echo -e "${BLUE}Datenbankaktualisierung unter http://localhost/contao/install ${NC}"
