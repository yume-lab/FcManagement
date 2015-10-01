# "vagrant up" 時に実行されるタスク

echo "=========================================="
echo "Set tasks to run"
echo "=========================================="
# apache の起動
sudo service mysqld start

# MySQLの起動
sudo service httpd start

