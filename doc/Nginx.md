Sans domaine
============
    root   /usr/share/nginx/html;
    autoindex off;
    location /Mediastorrent/ {
        rewrite ^/Mediastorrent/(.*)$ /Mediastorrent/webroot/$1 break;
        index index.php;
        try_files /$uri $uri/  @mediastorrent;
    }


    location @mediastorrent{
        rewrite ^/Mediastorrent/webroot/(.*)$ /Mediastorrent/webroot/index.php/$1 last;
    }
    location ~ \.php {
        fastcgi_split_path_info ^(.+?\.php)(/.*)$;
        if (!-e $document_root$fastcgi_script_name) {
            return 404;
        }
        fastcgi_pass  unix:/run/php-fpm/php-fpm.sock;
        include fastcgi.conf;
    }

    location ~ /Mediastorrent/.*\.svg$ {
        rewrite ^/Mediastorrent/(.*)$ /Mediastorrent/webroot/$1 break;
        if (!-e $document_root$fastcgi_script_name) {
        return 404;
        }
        fastcgi_split_path_info ^(.+?\.svg)(/.*)$;
        fastcgi_pass  unix:/run/php-fpm/php-fpm.sock;
        include fastcgi.conf;
    }

Avec domaine 
============

#mediastorrent.ssl.conf
    server {
            listen      [::]:80;
            listen       80;
            server_name  www.exemple.com;
            root /usr/share/nginx/html/Mediastorrent/webroot;
            autoindex off;
    location /film/download {
            fastcgi_buffering off;
            try_files $uri /index.php$request_uri;
    }
    location /serie/download {
                fastcgi_buffering off;
                try_files $uri /index.php$request_uri;
        }
    location /torrent/download {
            fastcgi_buffering off;
            try_files $uri /index.php$request_uri;
    }
    
    location /ticket/traite {
            try_files $uri /index.php$request_uri;
    }
    
    location / {
            return 301 https://$http_host$request_uri;
    }
    location ~ ^/(index)\.php(/|$) {
    fastcgi_pass unix:/run/php-fpm/php-fpm.sock;
            include fastcgi_params;
            fastcgi_param PATH_INFO $request_uri;
            fastcgi_param SCRIPT_NAME /index.php;
            fastcgi_param SCRIPT_FILENAME /usr/share/nginx/html/Mediastorrent/webroot/index.php;
            fastcgi_param DOCUMENT_ROOT /usr/share/nginx/html/Mediastorrent;
    }       
    }
    
    server {
            listen       [::]:443;
            listen       443;
            server_name  www.exemple.com;
    root /usr/share/nginx/html/Mediastorrent/webroot;
    ssl on;
    ssl_certificate /etc/nginx/ssl/www.exemple.com/ssl-unified.crt;
    ssl_certificate_key /etc/nginx/ssl/www.exemple.com/ssl.key;
    autoindex off;
    location / {
        index index.php;
        try_files $uri/ $uri /index.php$request_uri;
    }
    
    location ~ ^/(index)\.php(/|$) {
        fastcgi_pass unix:/run/php-fpm/php-fpm.sock;
        include fastcgi_params;
        fastcgi_param PATH_INFO $request_uri;
        fastcgi_param SCRIPT_NAME /index.php;
        fastcgi_param SCRIPT_FILENAME /usr/share/nginx/html/Mediastorrent/webroot/index.php;
        fastcgi_param DOCUMENT_ROOT /usr/share/nginx/html/Mediastorrent;
    }
    location ~ \.svg$ {
           if (!-e $document_root$document_uri){
           return 404;
           }
            fastcgi_pass   unix:/run/php-fpm/php-fpm.sock;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
    }
    
    }

#mediastorrent.conf
    server {
            listen      [::]:80;
            listen       80;
            server_name  www.exemple.com;
            root /usr/share/nginx/html/Mediastorrent/webroot;
            autoindex off;
    location / {
        index index.php;
        try_files $uri/ $uri /index.php$request_uri;
    }
    
    location ~ ^/(index)\.php(/|$) {
        fastcgi_pass unix:/run/php-fpm/php-fpm.sock;
        include fastcgi_params;
        fastcgi_param PATH_INFO $request_uri;
        fastcgi_param SCRIPT_NAME /index.php;
        fastcgi_param SCRIPT_FILENAME /usr/share/nginx/html/Mediastorrent/webroot/index.php;
        fastcgi_param DOCUMENT_ROOT /usr/share/nginx/html/Mediastorrent;
    }
    location ~ \.svg$ {
           if (!-e $document_root$document_uri){
           return 404;
           }
            fastcgi_pass   unix:/run/php-fpm/php-fpm.sock;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
    }
    
    }

Autre exemple avec domaine
==========================
#mediastorrent.ssl.conf
    server {
            listen      [::]:80;
            listen       80;
            server_name  www.exemple.com;
            root /usr/share/nginx/html/Mediastorrent/webroot;
            autoindex off;
    location /film/download {
            try_files $uri /index.php$request_uri;
    }
    location /serie/download {
                try_files $uri /index.php$request_uri;
        }
    location /torrent/download {
            try_files $uri /index.php$request_uri;
    }
    
    location /ticket/traite {
            try_files $uri /index.php$request_uri;
    }
    
    location / {
            return 301 https://$http_host$request_uri;
    }
    location ~ \.php {
                fastcgi_split_path_info ^(.+?\.php)(/.*)$;
                if (!-e $document_root$fastcgi_script_name) {
                    return 404;
                }
                fastcgi_pass  unix:/run/php-fpm/php-fpm.sock;
                include fastcgi.conf;
            }    
    }
    
    server {
            listen       [::]:443;
            listen       443;
            server_name  www.exemple.com;
    root /usr/share/nginx/html/Mediastorrent/webroot;
    ssl on;
    ssl_certificate /etc/nginx/ssl/www.exemple.com/ssl-unified.crt;
    ssl_certificate_key /etc/nginx/ssl/www.exemple.com/ssl.key;
    autoindex off;
    location / {
        index index.php;
        try_files $uri/ $uri /index.php$request_uri;
    }
    
    location ~ \.php {
                fastcgi_split_path_info ^(.+?\.php)(/.*)$;
                if (!-e $document_root$fastcgi_script_name) {
                    return 404;
                }
                fastcgi_pass  unix:/run/php-fpm/php-fpm.sock;
                include fastcgi.conf;
            }
        
            location ~ \.svg$ {
                if (!-e $document_root$fastcgi_script_name) {
                return 404;
                }
                fastcgi_split_path_info ^(.+?\.svg)(/.*)$;
                fastcgi_pass  unix:/run/php-fpm/php-fpm.sock;
                include fastcgi.conf;
            }
    
    }

#mediastorrent.conf
    server {
            listen      [::]:80;
            listen       80;
            server_name  www.exemple.com;
            root /usr/share/nginx/html/Mediastorrent/webroot;
            autoindex off;
    location / {
        index index.php;
       try_files /$uri $uri/  /index.php$request_uri;
    }
    location ~ \.php {
            fastcgi_split_path_info ^(.+?\.php)(/.*)$;
            if (!-e $document_root$fastcgi_script_name) {
                return 404;
            }
            fastcgi_pass  unix:/run/php-fpm/php-fpm.sock;
            include fastcgi.conf;
        }
    
        location ~ \.svg$ {
            if (!-e $document_root$fastcgi_script_name) {
            return 404;
            }
            fastcgi_split_path_info ^(.+?\.svg)(/.*)$;
            fastcgi_pass  unix:/run/php-fpm/php-fpm.sock;
            include fastcgi.conf;
        }
    }
