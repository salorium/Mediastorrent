<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 19/05/14
 * Time: 16:33
 */

namespace model\simple;


class MakerRtorrent
{
    static function create()
    {

        $content = '#!/bin/sh -e
#
### BEGIN INIT INFO
# Provides: rtorrentd
# Required-Start: $network $syslog
# Required-Stop: $network
# Default-Start: 2 3 5
# Default-Stop: 0 1 6
# Description: Démarrer/arrêter rtorrent sous forme de daemon.
### END INIT INFO

NAME=rtorrentd
SCRIPTNAME=/etc/init.d/$NAME

if [ -n "$2" ]; then
if [ -n "$3" ]; then
USER=$2
SCGI=$3

PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
# path du fichier temporaire
TMP=/tmp/rtorrent$USER.dtach
# user qui lance le torrent
# chemin vers fichier conf
CONF=/home/$USER/.rtorrent.rc
PHPDIR=' . ROOT . '/script
start() {
        echo -n $"Starting $NAME: "
        su -l $USER -c "dtach -n $TMP rtorrent -n -o import=$CONF"
        su -l $USER -c "php $PHPDIR/init.php $SCGI"
        #chmod 666 /tmp/rtorrent$USER.dtach
        echo "started"
}

stop() {
        echo -n $"Stopping $NAME: "
	tmmp=`su -l $USER -c "ps aux | grep -e \'rtorrent\' -c"`
        if [ $tmmp != 0  ]; then
        su -l $USER -c "killall -s 9 -r \"rtorrent\""
	echo "stopped"
	else
	echo "aucun processus trouve"
	fi
}

restart() {
tmmp=`su -l $USER -c "ps aux | grep -e \'rtorrent\' -c"`
 	if [ $tmmp != 0  ]; then
        {
                stop
                sleep 5
        }
        fi
        start
}


case $1 in
        start)
               start
        ;;
        stop)
                stop
        ;;
        restart)
                restart
        ;;
        *)
                echo "Usage:  {start|stop|restart}" >&2
                exit 2
        ;;
esac
else
echo "ERREUR"
fi
fi';
        file_put_contents("/etc/init.d/rtorrent", $content);
    }
} 