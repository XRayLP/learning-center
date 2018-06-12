#!/bin/bash
packages=(php7.2-cli php7.2-curl php7.2-gd php7.2-intl php7.2-mbstring php7.2-xml)
echo "Installationsvorgang beginnt."
#Root-Überprüfung
if (($EUID != 0));
  then echo "Keine Root-Rechte. Bitte Script als Root ausführen."
  exit
fi

#Paketinstallation
echo "Vorausgesetzte Pakete werden installiert."
sudo apt-get install php7.2-cli php7.2-curl php7.2-gd php7.2-intl php7.2-mbstring php7.2-xml
echo "xampp herunterladen"
sudo mkdir tmp
wget https://www.apachefriends.org/xampp-files/7.1.17/xampp-linux-x64-7.1.17-0-installer.run tmp/
sudo chmod 777 tmp/xampp-linux-x64-7.1.17-0-installer.run
sudo ./tmp/xampp-linux-x64-7.1.17-0-installer.run
#INTL
echo "Contao Installation"
sudo rm -R /opt/lampp/htdocs/*
wget https://download.contao.org/4.5.8/tar
tar -xzf contao-4.5.8.tar.gz -c /opt/lampp/htdocs contao-4.5.8/*
wget https://getcomposer.org/composer.phar /opt/lampp/htdocs
echo "MySQL Datenbank erstellen"
read -p "Wie soll die Datenbank heißen?:" dbname
sudo ./opt/lampp/bin/mysql -u root <<EOF
CREATE DATABASE dbname
COLLATE SQL_UFT8MB4_GENERAL_CI
GO
EOF
echo "Contao Konfiguration unter http://localhost/contao/install"
read -p "Zum fortfahren 'enter' drücken..."
sudo cp config.yml /opt/lampp/htdocs/app/config
sudo mv /opt/lampp/htdocs/composer.json /opt/lampp/htdocs/composer.json.bak
sudo cp composer.json /opt/lampp/htdocs
sudo cp -R ../src/ /opt/lampp/htdocs
sudo rm /opt/lampp/htdocs/src/ContaoMangerPlugin.php
sudo cp ContaoManagerPlugin.php /opt/lampp/htdocs/app
sudo chmod -R 777 /opt/lampp/htdocs
sudo php /opt/lampp/htdocs/composer.phar update --optimize-autoloader
echo "Datenbankaktualisierung unter http://localhost/contao/install"
