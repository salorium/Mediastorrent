<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 11/05/14
 * Time: 08:57
 */

namespace model\simple;


class MakerRtorrentConf
{
    static function create($user, $scgi)
    {
        $scgi = (int)$scgi;
        $content = '# This is an example resource file for rTorrent. Copy to
# ~/.rtorrent.rc and enable/modify the options as needed. Remember to
# uncomment the options you wish to enable.

# Maximum and minimum number of peers to connect to per torrent.
#min_peers = 40
#max_peers = 100

# Same as above but for seeding completed torrents (-1 = same as downloading)
#min_peers_seed = 10
#max_peers_seed = 50

# Maximum number of simultanious uploads per torrent.
#max_uploads = 15

# Global upload and download rate in KiB. "0" for unlimited.
download_rate = 0
upload_rate = 3000

# Default directory to save the downloaded torrents.
directory = ~/rtorrent/data

# Default session directory. Make sure you don t run multiple instance
# of rtorrent using the same session directory. Perhaps using a
# relative path?
session = ~/rtorrent/session
# Watch a directory for new torrents, and stop those that have been
# deleted.
#schedule = watch_directory,5,5,load_start=/home/salorium/rutorrent/data/username/torrent_active/*.torrent
#schedule = untied_directory,5,5,stop_untied=

# Close torrents when diskspace is low.
schedule = low_diskspace,5,60,close_low_diskspace=100M

# Stop torrents when reaching upload ratio in percent,
# when also reaching total upload in bytes, or when
# reaching final upload ratio in percent.
# example: stop at ratio 2.0 with at least 200 MB uploaded, or else ratio 20.0
#schedule = ratio,60,60,"stop_on_ratio=200,200M,2000"

# The ip address reported to the tracker.
#ip = 127.0.0.1
#ip = rakshasa.no

# The ip address the listening socket and outgoing connections is
# bound to.
#bind = 127.0.0.1
#bind = rakshasa.no

# Port range to use for listening.
port_range = 6001-6100

# Start opening ports at a random position within the port range.
port_random = yes

# Check hash for finished torrents. Might be usefull until the bug is
# fixed that causes lack of diskspace not to be properly reported.
check_hash = yes

# Set whetever the client should try to connect to UDP trackers.
#use_udp_trackers = yes

# Alternative calls to bind and ip that should handle dynamic ip s.
#schedule = ip_tick,0,1800,ip=rakshasa
#schedule = bind_tick,0,1800,bind=rakshasa

# Encryption options, set to none (default) or any combination of the following:
# allow_incoming, try_outgoing, require, require_RC4, enable_retry, prefer_plaintext
#
# The example value allows incoming encrypted connections, starts unencrypted
# outgoing connections but retries with encryption if they fail, preferring
# plaintext to RC4 encryption after the encrypted handshake
#
encryption = allow_incoming,require,require_rc4
#enable_retry,prefer_plaintext

# Enable DHT support for trackerless torrents or when all trackers are down.
# May be set to "disable" (completely disable DHT), "off" (do not start DHT),
# "auto" (start and stop DHT as needed), or "on" (start DHT immediately).
# The default is "off". For DHT to work, a session directory must be defined.
#
 dht = auto

# UDP port to use for DHT.
#
 dht_port = ' . ($scgi + 1100) . '

# Enable peer exchange (for torrents not marked private)
#
# peer_exchange = yes

#
# Do not modify the following parameters unless you know what youre doing.
#

# Hash read-ahead controls how many MB to request the kernel to read
# ahead. If the value is too low the disk may not be fully utilized,
# while if too high the kernel might not be able to keep the read
# pages in memory thus end up trashing.
#hash_read_ahead = 10

# Interval between attempts to check the hash, in milliseconds.
#hash_interval = 100

# Number of attempts to check the hash while using the mincore status,
# before forcing. Overworked systems might need lower values to get a
# decent hash checking rate.
#hash_max_tries = 10
scgi_port = 127.0.0.1:' . $scgi . '

#Capture de la date d\'ajout et du temps de seed :P
system.method.set_key=event.download.inserted_new,addtime,"d.set_custom=addtime,\"$execute_capture={date,+%s}\""
system.method.set_key = event.download.finished,seedingtime,"d.set_custom=seedingtime,\"\$execute_capture={date,+%s}\""
system.method.set_key=event.download.hash_done,seedingtimecheck,"branch=$not=$d.get_complete=,,d.get_custom=seedingtime,,\"d.set_custom=seedingtime,$d.get_custom=addtime\""

#Efface des donnée
system.method.set_key = event.download.erased,erasedata,"branch=d.get_custom1=,\"execute={rm,-r,$d.get_base_path=}\""

#Prise en charge de mediastorrent
system.method.set_key = event.download.finished,addbibliotheque,"execute={php,' . ROOT . DS . 'script' . DS . 'addbibliotheque.php,' . $scgi . ',$d.get_hash=,$d.get_base_path=,$d.get_base_filename=,$d.is_multi_file=,$d.get_custom=clefunique,$d.get_custom=typemedias}"

';
        file_put_contents("/home/" . $user . "/.rtorrent.rc", $content);
        //file_put_contents("/home/" . $user . "/.scgi.txt", $scgi . "");
    }
} 