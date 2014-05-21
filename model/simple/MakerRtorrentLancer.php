<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 21/05/14
 * Time: 22:40
 */

namespace model\simple;


class MakerRtorrentLancer extends \core\Model
{
    static function create()
    {
        switch (\config\Conf::$distribution) {
            case 'arch':
                return self::createForArchLinux();
                break;
            case 'ubuntu':
            case 'debian':
                return self::createForDebian();
                break;
        }
    }

    static function createForDebian()
    {
        $content = '
        #!/bin/sh -e
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
USER=$2

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
        su -l $USER -c "php $PHPDIR/init.php $USER"
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
        ';
        file_put_contents("/etc/init.d/rtorrent", $content);
        exec("chmod a+x /etc/init.d/rtorrent");
    }

    static function createForArchLinux()
    {
        $content = '
        [Unit]
Description=rTorrent
Requires=network.target local-fs.target

[Service]
Type=forking
RemainAfterExit=yes
KillMode=none
User=%I
ExecStart=/usr/bin/tmux new-session -s rt -n rtorrent -d rtorrent
ExecStartPost=/usr/bin/php ' . ROOT . '/script/init.php %I
ExecStop=/usr/bin/tmux send-keys -t rt:rtorrent C-q
WorkingDirectory=/home/%I/

[Install]
WantedBy=multi-user.target
';
        file_put_contents("/etc/systemd/system/rt@.service", $content);

    }

    static function start($user)
    {
        switch (\config\Conf::$distribution) {
            case 'arch':
                return self::startForArchLinux($user);
                break;
            case 'ubuntu':
            case 'debian':
                return self::startForDebian($user);
                break;
        }
    }

    static function startForDebian($user)
    {
        return \model\simple\Console::execute('/etc/init.d/rtorrent start ' . $user);
    }

    static function startForArchLinux($user)
    {
        return \model\simple\Console::execute('systemctl start rt@' . $user);
    }

} 