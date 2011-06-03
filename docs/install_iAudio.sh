# Konfiguration von iAudio mit Zend, PHP 5.3

# Installation von mysql Server und Passwort setzen
apt-get install mysql-server
mysql -u root -p "1qay2wsx3edc"

# Vorbereitung der sources.list fuer Zend
wget http://repos.zend.com/deb/zend.key -O- | apt-key add -
deb http://repos.zend.com/zend-server/deb server non-free >> /etc/apt/sources.list
apt-get update

# Installation von Zend
apt-get install --force-yes zend-server-ce-php-5.3
apt-get install --force-yes phpmyadmin-zend-server
apt-get install --force-yes php-5.3-extra-extensions-zend-server

# Update PATH Variable fuer Zend
PATH=$PATH:/usr/local/zend/bin

# Create structure
mkdir /var/www/music
mkdir /var/www/music/upload
mkdir /var/www/iAudio
mkdir /var/www/iAudio/public
mkdir /var/www/iAudio/public/tmp
chmod -Rv 0777 /var/www/music
chgrp -Rv www-data /var/www/music

# git clone
git clone git://github.com/iAudioSWE2011/iAudio.git >> log.txt

# sqls ausfuehren auf DB
mysql -h localhost -u root - p "1qay2wsx3edc" < /var/www/iAudio/docs/iAudio.sql

# php.ini kopieren
cp -r /var/www/iAudio/docs/php.ini /usr/local/zend/etc/php.ini

# httpd.conf kopieren
cp -r /var/www/iAudio/docs/httpd.conf /etc/apache2/conf/httpd.conf

# default ins sites-available
cp -r /var/www/iAudio/docs/default /etc/apache2/conf/sites-available/default

# apache durchstarten 
/usr/local/zend/bin/zendctl.sh restart

