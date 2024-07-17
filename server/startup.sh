#!/bin/bash

# this setup is necessary for cronjob and its env variables reading process
env > /tmp/environment_tmp
mv /tmp/environment_tmp /etc/environment

# cron
apt-get update -y && apt-get install -y cron
echo "* * * * * cd /home/site/wwwroot && /usr/local/bin/php artisan schedule:run >> cron.log" | crontab -
service cron start

# nginx
cp /home/site/wwwroot/server/nginx.conf /etc/nginx/sites-enabled/default
service nginx restart
