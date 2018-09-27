#!/usr/bin/env bash
echo "Save dump database..."
db=$(awk -F "=" '/db/ {print $2}' ./../../config.ini)
user_db=$(awk -F "=" '/user/ {print $2}' ./../../config.ini)
pass_db=$(awk -F "=" '/pass/ {print $2}' ./../../config.ini)
mysqldump -u ${user_db} -p${pass_db} ${user_db} > ./../sql/backup-with-data.sql
