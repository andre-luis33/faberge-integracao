#!/bin/bash

# cron
apt-get update -y && apt-get install -y cron
echo "* * * * * cd /home/site/wwwroot && php artisan schedule:run >> cron.log" | crontab -
service cron start

# nginx
cp /home/site/wwwroot/server/nginx.conf /etc/nginx/sites-enabled/default
service nginx restart
