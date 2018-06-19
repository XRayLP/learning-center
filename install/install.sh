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
sudo mv ${www}contao-4.5.8/ ${www}contao/
wget https://getcomposer.org/composer.phar ${www}contao/
sudo chmod -R 777 ${www}contao/


echo -e "${BLUE}MySQL Datenbank erstellen:${NC}"
ead -p "Wie soll die Datenbank heißen?: " MAINDB
read -p "Datenbanknutzer (z.B: root): " DBUSER
mysql -u${DBUSER} -p -e "CREATE DATABASE ${MAINDB} CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"

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
