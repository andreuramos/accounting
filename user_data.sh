#!/bin/bash

# from a ubuntu ami

echo "SETTING UP CONTABLE BACKEND"

sudo apt update
sudo apt install -y nginx-core \
  php8.1-fpm \
  rbenv

rbenv install 2.7.1
rbenv global 2.7.1

wget https://aws-codedeploy-eu-west-1.s3.amazonaws.com/latest/install
chmod +x ./install
./install auto

wget https://raw.githubusercontent.com/andreuramos/accounting/master/ops/prod.conf
sudo mv prod.conf /etc/nginx/sites-available/default
sudo service nginx start
