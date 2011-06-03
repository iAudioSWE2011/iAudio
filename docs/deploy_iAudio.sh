#
# Deployment of iAudio
#
#

base=/var/www/iAudio
backup=/var/www/tmp
application=/var/www/iAudio/application
public=/var/www/iAudio/public
tmp=/var/www/iAudio/public/tmp

echo "Start deployment.."
echo ""

#Backup tmp structure
echo -n "Backup up tmp structure..."
cp -r $tmp $backup
echo "DONE"

#delete iAudio
echo -n "Removing current version of iAudio..."
rm -r $base
echo "DONE"

#download latest version
echo -n "Clone iAudio from github..."
git clone git://github.com/iAudioSWE2011/iAudio.git >> log.txt
echo "DONE"

#copy mp3 back
echo -n "Copy tmp structure back..."
cp -r $backup $tmp
echo "DONE"

#remove backup
echo -n "remove backup..."
rm -r $backup
rm log.txt
echo "DONE"

#set rights for upload
echo -n "Set correct user rights..."
chmod 0777 $application
chmod 0777 $public
chmod 0777 $tmp
chown -Rv www-data $tmp >> log.txt
chgrp -Rv www-data $tmp >> log.txt
echo "DONE"

echo ""
echo "Deployment FINISHED"

exit 0
