#!/usr/bin/env bash

# CentOS6.5 でのミドルウェアインストール

sudo yum -y update

# PHP依存モジュール
# CakePHP 3.x はPHP5.4.16以上のため、リポジトリ追加していれる
echo "== start PHP =="
sudo rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm
sudo yum -y install --enablerepo=remi --enablerepo=remi-php56 php php-intl php-mbstring php-mysqlnd

# iniの書き換え
sudo mv /etc/php.ini /etc/php.ini.back
sudo cp /dev_root/vagrant/conf/php.ini /etc/php.ini

# composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# apache
echo "== start apache =="
sudo yum -y install httpd

sudo mv /etc/httpd/conf/httpd.conf /etc/httpd/conf/httpd.conf.back
sudo cp /dev_root/vagrant/conf/httpd.conf /etc/httpd/conf/httpd.conf

# apache ドキュメントルート書き換え
sudo sed -i 's_DocumentRoot \"/var/www/html\"_DocumentRoot \"/dev_root/webapps\"_' /etc/httpd/conf/httpd.conf
# apache ServerName変更
sudo sed -i 's/#ServerName www.vagrant.com:80/ServerName 127.0.0.1/' /etc/httpd/conf/httpd.conf
# apache アプリケーションで設定される.htaccess許可
sudo sed -i '/<Directory \"\/var\/www\/html\">/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/httpd/conf/httpd.conf

# MySQL
echo "== start MySQL =="
sudo yum -y install mysql-server

# MongoDB
echo "== start MongoDB =="
sudo cp /dev_root/vagrant/repo/mongodb.repo /etc/yum.repos.d/mongodb.repo
sudo yum -y install --enablerepo=mongodb mongodb-org

# インストール確認
echo "======================"
echo "== インストール確認 =="
echo "======================"
echo " PHP"
php -v
echo " "
echo " Apache"
httpd -v
echo " "
echo " MySQL"
mysql --version
echo " "
echo "== Complete !! =="
