#!/usr/bin/env bash
echo "Install database scheme"
db=$(awk -F "=" '/db/ {print $2}' ./../../config.ini)
user_db=$(awk -F "=" '/user/ {print $2}' ./../../config.ini)
pass_db=$(awk -F "=" '/pass/ {print $2}' ./../../config.ini)
echo "Are you want install dump with demo data for database? (y/n)"
read is_install_full_dump
if [ "$is_install_full_dump" = "y" ]
then
    mysql -u ${user_db} -p${pass_db} ${db} < ./../sql/backup-with-data.sql
else
    mysql -u ${user_db} -p${pass_db} ${db} < ./../sql/backup-scheme.sql
fi
cd ./../../
composer install
