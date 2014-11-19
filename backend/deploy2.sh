#!/bin/bash
mv om.tgz /var/www
cd /var/www
tar -xvzf om.tgz
rm -rf /var/www/octoman/backend/
mv /var/www/backend /var/www/octoman/backend/