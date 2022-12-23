#!/bin/bash

# from a ubuntu ami

echo "SETTING UP CONTABLE BACKEND"

sudo apt update
sudo apt install -y nginx-core \
  php8.1-fpm

wget https://raw.githubusercontent.com/andreuramos/accounting/master/ops/prod.conf
sudo mv prod.conf /etc/nginx/sites-available/default
sudo service nginx start
