#Mediastorrent
## Introduction
Mediastorrent un front-end pour rtorrent avec gestion multi-user, multi-seedbox et multi-médias

##Mettre à jour la liste des paquets de votre système
    sudo pacman -Syu

##Installation de rtorrent
    sudo pacman -S rtorrent


##Installation de Memcached
    sudo pacman -S memcached
    systemctl enable memcached.service
    systemctl start memcached.service

##Installation de maridb
    sudo pacman -S mariadb
    mysql_install_db --user=mysql --basedir=/usr --datadir=/var/lib/mysql
    systemctl start mysqld.service
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
    systemctl enable mysqld.service

##Installation de Mediastorrent
    sudo pacman -S nginx php-fpm php git cronie tmux php-memcached
    systemctl start cronie.service
    systemctl enable cronie.service
    git clone https://github.com/salorium/Mediastorrent.git => dans un dossier <utilisateur> pas sur le root.
    sudo ln -s /home/<utilisateur>/Mediastorrent /usr/share/nginx/html/Mediastorrent
    sudo chmod a+xr /home/<utilisateur>
    sudo php /home/<utilisateur>/Mediastorrent/script/initroot.php
    
    
    
    
    
    sudo apt-get install apache2 libapache2-mod-php5 php5-mysqlnd php5-json php5-imagick php5-memcached php5-curl dtach libssh2-php git tmux mediainfo
    sudo a2enmod rewrite
    sudo service apache2 restart
    
    sudo ln -s /home/<utilisateur>/Mediastorrent /var/www/Mediastorrent
    sudo php /home/<utilisateur>/Mediastorrent/script/initroot.php

##Configuration de php-fpm 
Dans /etc/php/php-fpm.conf ajouter ```.svg``` à la ligne ```security.limit_extensions``` :

    security.limit_extensions = .php .php3 .php4 .php5 .svg

##Configuration de php
Dans /etc/php/php.ini modifier le fichier comme cela : 
    
    short_open_tag = On
    open_basedir = /srv/http/:/home/:/tmp/:/usr/share/pear/:/usr/share/webapps/:/etc/:/usr/share/nginx/html/
    extension=mysqli.so
    date.timezone =Europe/Paris
    
Dans /etc/php/conf.d/memcached.ini modifier comme cela :
    
    extension=memcached.so
    
##Configuration de nginx
Dans /etc/nginx/fastcgi.conf ajouter :
    
    fastcgi_param PATH_INFO $fastcgi_path_info;
    fastcgi_param   PATH_TRANSLATED         $document_root$fastcgi_path_info;
    
##Configuration d'apache2
Dans  /etc/apache2/sites-available/000-default.conf, il faut ajouter ce code dans le ```<VirtualHost *:80>``` :

    <Directory /var/www>
        AllowOverride All
    </Directory>

Exemple :

    <VirtualHost *:80>
	    # The ServerName directive sets the request scheme, hostname and port that
	    # the server uses to identify itself. This is used when creating
	    # redirection URLs. In the context of virtual hosts, the ServerName
	    # specifies what hostname must appear in the request's Host: header to
	    # match this virtual host. For the default virtual host (this file) this
	    # value is not decisive as it is used as a last resort host regardless.
	    # However, you must set it for any further virtual host explicitly.
	    #ServerName www.example.com

	    ServerAdmin webmaster@localhost
	    DocumentRoot /var/www

	    # Available loglevels: trace8, ..., trace1, debug, info, notice, warn,
	    # error, crit, alert, emerg.
	    # It is also possible to configure the loglevel for particular
	    # modules, e.g.
	    #LogLevel info ssl:warn

	    ErrorLog ${APACHE_LOG_DIR}/error.log
	    CustomLog ${APACHE_LOG_DIR}/access.log combined
        <Directory /var/www>
            AllowOverride All
        </Directory>

        # For most configuration files from conf-available/, which are
        # enabled or disabled at a global level, it is possible to
        # include a line for only one particular virtual host. For example the
        # following line enables the CGI configuration for this host only
        # after it has been globally disabled with "a2disconf".
        #Include conf-available/serve-cgi-bin.conf
    </VirtualHost>

##Configuration de php
Dans le fichier /etc/php5/apache2/php.ini
Mettre la directive short_open_tag à On
 
##Utilisation
Développé sous firefox, mais devrait fonctionner sans problème sous chrome.
