#!/bin/bash

# from a ubuntu ami

echo "SETTING UP CONTABLE BACKEND"

sudo apt update
sudo apt install -y nginx-core

sudo service nginx start
