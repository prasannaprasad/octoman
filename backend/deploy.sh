#!/bin/bash

cd ~/phpstormprojects/octoman/
rm om.tgz
tar -cvzf om.tgz backend/
scp -i ~/pems/freezingbearbackend.pem  om.tgz ubuntu@ec2-54-187-45-229.us-west-2.compute.amazonaws.com:
ssh -i ~/pems/freezingbearbackend.pem  ubuntu@ec2-54-187-45-229.us-west-2.compute.amazonaws.com './deploy2.sh'
