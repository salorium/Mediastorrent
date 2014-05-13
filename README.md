#Mediastorrent
## Introduction
Mediastorrent un front-end pour rtorrent avec gestion multi-user, multi-seedbox et multi-médias

##Mettre à jour la liste des paquets de votre système
```
sudo apt-get update
```

##Installation de rtorrent
- Base
```
sudo apt-get install autoconf build-essential comerr-dev libcloog-ppl-dev libcppunit-dev libcurl3 libcurl4-openssl-dev libncurses5-dev ncurses-base ncurses-term libterm-readline-gnu-perl libsigc++-2.0-dev libssl-dev libtool libxml2-dev subversion curl
mkdir source
cd source
svn co https://svn.code.sf.net/p/xmlrpc-c/code/stable xmlrpc
curl http://libtorrent.rakshasa.no/downloads/libtorrent-0.13.3.tar.gz | tar xz
curl http://libtorrent.rakshasa.no/downloads/rtorrent-0.9.3.tar.gz | tar xz
```

- Xmlrpc
```
cd xmlrpc
./configure --prefix=/usr --enable-libxml2-backend --disable-libwww-client --disable-wininet-client --disable-abyss-server --disable-cgi-server
make
sudo make install
```

- Libtorrent
```
cd ../libtorrent-0.13.3
./autogen.sh
./configure --prefix=/usr
make -j2
sudo make install
```

- Rtorrent
```
cd ../rtorrent-0.9.3
./autogen.sh
./configure --prefix=/usr --with-xmlrpc-c
make -j2
sudo make install
```

- Final
```
sudo ldconfig
```

##Installation de Memcached
```
sudo apt-get install memcached
```

##Installation de mysql-server
```
sudo apt-get install mysql-server
```

##Installation de Mediastorrent
```
sudo apt-get install apache2 libapache2-mod-php5 php5-mysqlnd php5-json php5-imagick php5-memcached php5-curl dtach libssh2-php git
sudo a2enmod rewrite
sudo service apache2 restart
git clone https://github.com/salorium/Mediastorrent.git => dans un dossier <utilisateur> pas sur le root.
sudo ln -s /home/<utilisateur>/Mediastorrent /var/www/Mediastorrent
```

##Configuration d'apache2

Dans  /etc/apache2/sites-available/000-default.conf, il faut ajouter ce code dans le ```<VirtualHost *:80>``` :
```
<Directory /var/www>
AllowOverride All
</Directory>
```

Exemple :

```
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
```

##Utilisation
Je vous conseil d'utiliser Mediastorrent avec firefox.
