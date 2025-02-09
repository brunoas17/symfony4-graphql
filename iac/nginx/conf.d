server {
    listen 80 default_server;
    listen [::]:80 default_server;
    server_name _;

    root /var/www/public;
    index index.php;

    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;

    server_tokens off;

    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass php:9000;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}