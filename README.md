#Mediastorrent

##Installation de rtorrent
1. Base

```
apt-get install autoconf build-essential comerr-dev libcloog-ppl-dev libcppunit-dev libcurl3 libcurl4-openssl-dev libncurses5-dev ncurses-base ncurses-term libterm-readline-gnu-perl libsigc++-2.0-dev libssl-dev libtool libxml2-dev subversion curl
mkdir source
cd source
svn co https://xmlrpc-c.svn.sourceforge.net/svnroot/xmlrpc-c/stable xmlrpc
curl http://libtorrent.rakshasa.no/downloads/libtorrent-0.13.3.tar.gz | tar xz
curl http://libtorrent.rakshasa.no/downloads/rtorrent-0.9.3.tar.gz | tar xz
```

2. xmlrpc

```
cd xmlrpc
./configure --prefix=/usr --enable-libxml2-backend --disable-libwww-client --disable-wininet-client --disable-abyss-server --disable-cgi-server
make
sudo make install
```

3. libtorrent

```
cd ../libtorrent-0.13.3
./autogen.sh
./configure --prefix=/usr
make -j2
sudo make install
```

3. rtorrent

```
cd ../rtorrent-0.9.3
./autogen.sh
./configure --prefix=/usr --with-xmlrpc-c
make -j2
sudo make install
```

4. final

```
sudo ldconfig
```