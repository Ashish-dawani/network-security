#!/bin/bash
# Ensure the script is run as root
if [ "$(id -u)" != "0" ]; then
   echo "This script must be run as root" 1>&2
   exit 1
fi
# Update all installed packages
yum update -y
# Install Apache Web Server
yum install -y httpd
systemctl start httpd.service
systemctl enable httpd.service
# Set up the MariaDB repository and install MariaDB
tee /etc/yum.repos.d/MariaDB.repo <<EOF
[mysql57-community]
name=MySQL 5.7 Community Server
baseurl=http://repo.mysql.com/yum/mysql-5.7-community/el/7/x86_64/
enabled=1
gpgcheck=1
gpgkey=https://repo.mysql.com/RPM-GPG-KEY-mysql-2022
EOF
rpm --import https://repo.mysql.com/RPM-GPG-KEY-mysql-2022
yum clean all
yum install -y mysql-community-server
systemctl start mysqld
systemctl enable mysqld
# Install PHP and necessary modules
yum install -y php php-mysqlnd php-fpm
systemctl restart httpd.service

# Install and configure firewalld
yum install -y firewalld
systemctl start firewalld
systemctl enable firewalld
firewall-cmd --permanent --zone=public --add-service=http
firewall-cmd --permanent --zone=public --add-service=https
firewall-cmd --reload

# Create a test PHP file
echo "<?php phpinfo(); ?>" > /var/www/html/phpinfo.php
