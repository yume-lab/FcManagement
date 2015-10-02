#!/usr/bin/env bash
# "vagrant up" 時に実行されるタスク

DB_NAME=agera

echo "=========================================="
echo "Set tasks to run"
echo "=========================================="
# apache の起動
sudo service mysqld start

# MySQLの起動
sudo service httpd start

# Cakeのインストール
cd /dev_root/webapps
composer install
composer update

# DBの作成
mysql -u root --execute  "create database if not exists $DB_NAME"

