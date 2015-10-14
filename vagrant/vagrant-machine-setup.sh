#!/usr/bin/env bash

# CentOS6.5 でのミドルウェアインストール

LOCAL_ROOT=/dev_root/vagrant

sudo yum -y update

# PHP依存モジュール
# CakePHP 3.x はPHP5.4.16以上のため、リポジトリ追加していれる
echo "== start PHP =="
sudo rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-6.rpm
sudo yum -y install --enablerepo=remi --enablerepo=remi-php56 php php-intl php-mbstring php-mysqlnd

# iniの書き換え
sudo mv /etc/php.ini /etc/php.ini.back
sudo cp $LOCAL_ROOT/conf/php.ini /etc/php.ini

# composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# apache
echo "== start apache =="
sudo yum -y install httpd

sudo mv /etc/httpd/conf/httpd.conf /etc/httpd/conf/httpd.conf.back
sudo cp $LOCAL_ROOT/conf/httpd.conf /etc/httpd/conf/httpd.conf

# MySQL
echo "== start MySQL =="
sudo yum remove mysql*
sudo yum -y install http://dev.mysql.com/get/mysql-community-release-el6-5.noarch.rpm
sudo yum -y install yum-utils
sudo yum-config-manager --disable mysql56-community
sudo yum-config-manager --enable mysql57-community-dmr
sudo yum -y install mysql mysql-devel mysql-server mysql-utilities

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
