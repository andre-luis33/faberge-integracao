#!/bin/bash

# cron
echo "* * * * * cd /home/site/wwwroot && /usr/local/bin/php artisan schedule:run >> cron.log" | crontab -
service cron start

# nginx
cp /home/site/wwwroot/server/nginx.conf /etc/nginx/sites-enabled/default
service nginx restart
