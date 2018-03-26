#!/usr/bin/env bash

sudo chmod 777 -R /var/www/storage

cp /var/www/provision/monitor-worker.conf /etc/supervisor/conf.d/monitor-worker.conf

supervisorctl reread
supervisorctl update
supervisorctl restart monitor-worker:*

echo "Done"