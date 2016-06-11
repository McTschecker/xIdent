#!/bin/bash
#für Raspi unter Rasbian Linux
sudo -i
apt-get update && apt-get upgrade
apt-get install dialog
apt-get install apache2 php5-mysql libapache2-mod-php5 mysql-server
apt-get install vsftpd 

exit
