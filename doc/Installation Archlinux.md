#Mediastorrent
## Introduction
Mediastorrent un front-end pour rtorrent avec gestion multi-user, multi-seedbox et multi-médias

##Mettre à jour la liste des paquets de votre système
    sudo pacman -Syu

##Installation de Rtorrent
    sudo pacman -S rtorrent


##Installation de Memcached
    sudo pacman -S memcached
    systemctl enable memcached.service
    systemctl start memcached.service

##Installation de Mariadb
    sudo pacman -S mariadb
    mysql_install_db --user=mysql --basedir=/usr --datadir=/var/lib/mysql
    systemctl start mysqld.service
    systemctl enable mysqld.service
    /usr/bin/mysql_secure_installation
    
    NOTE: RUNNING ALL PARTS OF THIS SCRIPT IS RECOMMENDED FOR ALL MariaDB
          SERVERS IN PRODUCTION USE!  PLEASE READ EACH STEP CAREFULLY!
    
    In order to log into MariaDB to secure it, we'll need the current
    password for the root user.  If you've just installed MariaDB, and
    you haven't set the root password yet, the password will be blank,
    so you should just press enter here.
    
    Enter current password for root (enter for none): 
    OK, successfully used password, moving on...
    
    Setting the root password ensures that nobody can log into the MariaDB
    root user without the proper authorisation.
    
    Set root password? [Y/n] Y
    New password: 
    Re-enter new password: 
    Password updated successfully!
    Reloading privilege tables..
     ... Success!
    
    
    By default, a MariaDB installation has an anonymous user, allowing anyone
    to log into MariaDB without having to have a user account created for
    them.  This is intended only for testing, and to make the installation
    go a bit smoother.  You should remove them before moving into a
    production environment.
    
    Remove anonymous users? [Y/n] Y
     ... Success!
    
    Normally, root should only be allowed to connect from 'localhost'.  This
    ensures that someone cannot guess at the root password from the network.
    
    Disallow root login remotely? [Y/n] Y
     ... Success!
    
    By default, MariaDB comes with a database named 'test' that anyone can
    access.  This is also intended only for testing, and should be removed
    before moving into a production environment.
    
    Remove test database and access to it? [Y/n] Y
     - Dropping test database...
     ... Success!
     - Removing privileges on test database...
     ... Success!
    
    Reloading the privilege tables will ensure that all changes made so far
    will take effect immediately.
    
    Reload privilege tables now? [Y/n] Y
     ... Success!
    
    Cleaning up...
    
    All done!  If you've completed all of the above steps, your MariaDB
    installation should now be secure.

    Thanks for using MariaDB!
    

##Installation de Mediastorrent
    sudo pacman -S nginx php-fpm php git cronie tmux php-memcached php-pear base-devel imagemagick postfix mediainfo
    pecl install imagick
    systemctl start cronie.service
    systemctl enable cronie.service
    systemctl start postfix.service
    systemctl enable postfix.service
    git clone https://github.com/salorium/Mediastorrent.git => dans un dossier <utilisateur> pas sur le root.
    sudo ln -s /home/<utilisateur>/Mediastorrent /usr/share/nginx/html/Mediastorrent
    sudo chmod a+xr /home/<utilisateur>

##Configuration de Php-fpm 
Dans /etc/php/php-fpm.conf ajouter ```.svg``` à la ligne ```security.limit_extensions``` :

    security.limit_extensions = .php .php3 .php4 .php5 .svg

##Configuration de Php
Dans /etc/php/php.ini modifier le fichier comme cela : 
    
    short_open_tag = On
    open_basedir = /srv/http/:/home/:/tmp/:/usr/share/pear/:/usr/share/webapps/:/etc/:/usr/share/nginx/html/
    extension=mysqli.so
    extension=imagick.so
    date.timezone =Europe/Paris
    
Dans /etc/php/conf.d/memcached.ini modifier comme cela :
    
    extension=memcached.so
    
Redémarage de php-fpm

    systemctl start php-fpm.service
    systemctl enable php-fpm.service
    
    
##Configuration de Nginx
Dans /etc/nginx/fastcgi.conf ajouter :
    
    fastcgi_param PATH_INFO $fastcgi_path_info;
    fastcgi_param   PATH_TRANSLATED         $document_root$fastcgi_path_info;

Configuration du virtualhost sous [Nginx](https://github.com/salorium/Mediastorrent/blob/master/doc/Nginx.md)
    
Redémarage de nginx

    systemctl start nginx.service
    systemctl enable nginx.service
        
    
##Configuration de Mediastorrent
    
    sudo php /home/<utilisateur>/Mediastorrent/script/initroot.php

 
##Utilisation
Développé sous firefox, mais devrait fonctionner sans problème sous chrome.
