NOW=$(date +"%Y-%m-%d")
FILENAME="archive/a2a-$NOW.tar"
tar -cf $FILENAME /home/mgoetz/nb_private_prod /home/mgoetz/nutsybolts.com

cp -R /home/mgoetz/echoes.nutsybolts.com/* /home/mgoetz/nutsybolts.com
cp -R /home/mgoetz/nb_private_dev/lib/* /home/mgoetz/nb_private_prod/lib
cp -R /home/mgoetz/nb_private_dev/templates/* /home/mgoetz/nb_private_prod/templates