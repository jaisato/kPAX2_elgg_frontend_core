#!/bin/bash
#################################################
#
# Script to install and configure Elgg for Kpax.
# Ubuntu 16.04 LTS / Elgg 2.2.2 / Kpax 2
#
# Ernesto Olariaga Rodríguez
# Universitat Oberta de Catalunya (2016)
#
#################################################

# Recomended total system update & upgrade

echo 'System Update'
apt-get update

echo 'System Upgrade'
apt-get upgrade

# unzip & aptitude
clear
echo "About to Install unzip and aptitude utilities"
apt-get install -y unzip aptitude

# Apache
clear
echo "About to Install Apache"
apt-get install -y apache2

# MySQL
clear
echo "About to Install MySQL"
# non-interactive MySQL installation
export DEBIAN_FRONTEND=noninteractive
sudo debconf-set-selections <<< 'mysql-server-5.7 mysql-server/root_password password kpax2root'
sudo debconf-set-selections <<< 'mysql-server-5.7 mysql-server/root_password_again password kpax2root'
apt-get install -y mysql-server mysql-client

# PHP
clear
echo "About to Install PHP"
apt-get install -y php7.0 libapache2-mod-php7.0 php-mysql php-dom

# PHP Extensions
clear
echo "About to Install PHP Extensions"
aptitude install -y php-gd php-mbstring php7.0-gd php7.0-mbstring

#  Config /etc/apache2/apache2.conf
#  'AllowOverride None' with 'AllowOverride All'
clear
echo "About to configure Apache"
sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Configure Apache rewrite
a2enmod rewrite
service apache2 restart

# Create MySQL Database & tables for Elgg
clear
echo "About to Create MySQL Database & Tables for Elgg"
mysql -u root -pkpax2root -e "CREATE DATABASE elggDB;CREATE USER elgguser IDENTIFIED BY 'elggpassword';GRANT ALL ON elggDB.* TO elgguser;"

mysql -u root -pkpax2root elggDB -e \
"CREATE TABLE elggDB_access_collection_membership (user_guid int(11) NOT NULL,access_collection_id int(11) NOT NULL, PRIMARY KEY (user_guid,access_collection_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8; CREATE TABLE elggDB_access_collections (id int(11) NOT NULL AUTO_INCREMENT,name text NOT NULL,owner_guid bigint(20) unsigned NOT NULL,site_guid bigint(20) unsigned NOT NULL DEFAULT '0',PRIMARY KEY (id),KEY owner_guid (owner_guid),KEY site_guid (site_guid)) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;  CREATE TABLE elggDB_annotations (id int(11) NOT NULL AUTO_INCREMENT,entity_guid bigint(20) unsigned NOT NULL,name_id int(11) NOT NULL,value_id int(11) NOT NULL,value_type enum('integer','text') NOT NULL,owner_guid bigint(20) unsigned NOT NULL,access_id int(11) NOT NULL,time_created int(11) NOT NULL,enabled enum('yes','no') NOT NULL DEFAULT 'yes',PRIMARY KEY (id),KEY entity_guid (entity_guid),KEY name_id (name_id),KEY value_id (value_id),KEY owner_guid (owner_guid),KEY access_id (access_id)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;  CREATE TABLE elggDB_api_users (id int(11) NOT NULL AUTO_INCREMENT,site_guid bigint(20) unsigned DEFAULT NULL,api_key varchar(40) DEFAULT NULL,secret varchar(40) NOT NULL,active int(1) DEFAULT '1',PRIMARY KEY (id),UNIQUE KEY api_key (api_key)) ENGINE=MyISAM DEFAULT CHARSET=utf8;  CREATE TABLE elggDB_config (name varchar(255) NOT NULL,value text NOT NULL,site_guid int(11) NOT NULL,PRIMARY KEY (name,site_guid)) ENGINE=MyISAM DEFAULT CHARSET=utf8;  CREATE TABLE elggDB_datalists (name varchar(255) NOT NULL,value text NOT NULL,PRIMARY KEY (name)) ENGINE=MyISAM DEFAULT CHARSET=utf8;  CREATE TABLE elggDB_entities (guid bigint(20) unsigned NOT NULL AUTO_INCREMENT,type enum('object','user','group','site') NOT NULL,subtype int(11) DEFAULT NULL,owner_guid bigint(20) unsigned NOT NULL,site_guid bigint(20) unsigned NOT NULL,container_guid bigint(20) unsigned NOT NULL,access_id int(11) NOT NULL,time_created int(11) NOT NULL,time_updated int(11) NOT NULL,last_action int(11) NOT NULL DEFAULT '0',enabled enum('yes','no') NOT NULL DEFAULT 'yes',PRIMARY KEY (guid),KEY type (type),KEY subtype (subtype),KEY owner_guid (owner_guid),KEY site_guid (site_guid),KEY container_guid (container_guid),KEY access_id (access_id),KEY time_created (time_created),KEY time_updated (time_updated)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8; CREATE TABLE elggDB_entity_relationships (id int(11) NOT NULL AUTO_INCREMENT,guid_one bigint(20) unsigned NOT NULL,relationship varchar(50) NOT NULL,guid_two bigint(20) unsigned NOT NULL,time_created int(11) NOT NULL,PRIMARY KEY (id),UNIQUE KEY guid_one (guid_one,relationship,guid_two),KEY relationship (relationship),KEY guid_two (guid_two)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;  CREATE TABLE elggDB_entity_subtypes (id int(11) NOT NULL AUTO_INCREMENT,type enum('object','user','group','site') NOT NULL,subtype varchar(50) NOT NULL,class varchar(50) NOT NULL DEFAULT '',PRIMARY KEY (id),UNIQUE KEY type (type,subtype)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;  "

mysql -u root -pkpax2root elggDB -e \
"CREATE TABLE elggDB_geocode_cache (id int(11) NOT NULL AUTO_INCREMENT,location varchar(128) DEFAULT NULL,lat varchar(20) DEFAULT NULL,lng varchar(20) DEFAULT NULL,PRIMARY KEY (id),UNIQUE KEY location (location)) ENGINE=MEMORY DEFAULT CHARSET=utf8;  CREATE TABLE elggDB_groups_entity (guid bigint(20) unsigned NOT NULL,name text NOT NULL,description text NOT NULL,PRIMARY KEY (guid),KEY name (name(50)),KEY description (description(50)),FULLTEXT KEY name_2 (name,description)) ENGINE=MyISAM DEFAULT CHARSET=utf8;  CREATE TABLE elggDB_hmac_cache (hmac varchar(255) NOT NULL,ts int(11) NOT NULL,PRIMARY KEY (hmac),KEY ts (ts)) ENGINE=MEMORY DEFAULT CHARSET=utf8;  CREATE TABLE elggDB_metadata (id int(11) NOT NULL AUTO_INCREMENT,entity_guid bigint(20) unsigned NOT NULL,name_id int(11) NOT NULL,value_id int(11) NOT NULL,value_type enum('integer','text') NOT NULL,owner_guid bigint(20) unsigned NOT NULL,access_id int(11) NOT NULL,time_created int(11) NOT NULL,enabled enum('yes','no') NOT NULL DEFAULT 'yes',PRIMARY KEY (id),KEY entity_guid (entity_guid),KEY name_id (name_id),KEY value_id (value_id),KEY owner_guid (owner_guid),KEY access_id (access_id)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;  CREATE TABLE elggDB_metastrings (id int(11) NOT NULL AUTO_INCREMENT,string text NOT NULL,PRIMARY KEY (id),KEY string (string(50))) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;  CREATE TABLE elggDB_objects_entity (guid bigint(20) unsigned NOT NULL,title text NOT NULL,description text NOT NULL,PRIMARY KEY (guid),FULLTEXT KEY title (title,description)) ENGINE=MyISAM DEFAULT CHARSET=utf8;  CREATE TABLE elggDB_private_settings (id int(11) NOT NULL AUTO_INCREMENT,entity_guid int(11) NOT NULL,name varchar(128) NOT NULL,value text NOT NULL,PRIMARY KEY (id),UNIQUE KEY entity_guid (entity_guid,name),KEY name (name),KEY value (value(50))) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;  CREATE TABLE elggDB_queue (id int(11) NOT NULL AUTO_INCREMENT,name varchar(255) NOT NULL,data mediumblob NOT NULL,timestamp int(11) NOT NULL,worker varchar(32) NULL,PRIMARY KEY (id),KEY name (name),KEY retrieve (timestamp,worker)) ENGINE=MyISAM DEFAULT CHARSET=utf8;  "

mysql -u root -pkpax2root elggDB -e \
"CREATE TABLE elggDB_river (id int(11) NOT NULL AUTO_INCREMENT,type varchar(8) NOT NULL,subtype varchar(32) NOT NULL,action_type varchar(32) NOT NULL,access_id int(11) NOT NULL,view text NOT NULL,subject_guid int(11) NOT NULL,object_guid int(11) NOT NULL,target_guid int(11) NOT NULL,annotation_id int(11) NOT NULL,posted int(11) NOT NULL,enabled enum('yes','no') NOT NULL DEFAULT 'yes',PRIMARY KEY (id),KEY type (type),KEY action_type (action_type),KEY access_id (access_id),KEY subject_guid (subject_guid),KEY object_guid (object_guid),KEY target_guid (target_guid),KEY annotation_id (annotation_id),KEY posted (posted)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8; CREATE TABLE elggDB_sites_entity (guid bigint(20) unsigned NOT NULL,name text NOT NULL,description text NOT NULL,url varchar(255) NOT NULL,PRIMARY KEY (guid),UNIQUE KEY url (url),FULLTEXT KEY name (name,description,url)) ENGINE=MyISAM DEFAULT CHARSET=utf8; CREATE TABLE elggDB_system_log (id int(11) NOT NULL AUTO_INCREMENT,object_id int(11) NOT NULL,object_class varchar(50) NOT NULL,object_type varchar(50) NOT NULL,object_subtype varchar(50) NOT NULL,event varchar(50) NOT NULL,performed_by_guid int(11) NOT NULL,owner_guid int(11) NOT NULL,access_id int(11) NOT NULL,enabled enum('yes','no') NOT NULL DEFAULT 'yes',time_created int(11) NOT NULL,ip_address varchar(46) NOT NULL,PRIMARY KEY (id),KEY object_id (object_id),KEY object_class (object_class),KEY object_type (object_type),KEY object_subtype (object_subtype),KEY event (event),KEY performed_by_guid (performed_by_guid),KEY access_id (access_id),KEY time_created (time_created),KEY river_key (object_type,object_subtype,event)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8; CREATE TABLE elggDB_users_apisessions (id int(11) NOT NULL AUTO_INCREMENT,user_guid bigint(20) unsigned NOT NULL,site_guid bigint(20) unsigned NOT NULL,token varchar(40) DEFAULT NULL,expires int(11) NOT NULL,PRIMARY KEY (id),UNIQUE KEY user_guid (user_guid,site_guid),KEY token (token)) ENGINE=MEMORY DEFAULT CHARSET=utf8; CREATE TABLE elggDB_users_entity (guid bigint(20) unsigned NOT NULL,name text NOT NULL,username varchar(128) NOT NULL DEFAULT '',password varchar(32) NOT NULL DEFAULT '' COMMENT 'Legacy password hashes',salt varchar(8) NOT NULL DEFAULT '' COMMENT 'Legacy password salts',password_hash varchar(255) NOT NULL DEFAULT '',email text NOT NULL,language varchar(6) NOT NULL DEFAULT '',banned enum('yes','no') NOT NULL DEFAULT 'no',admin enum('yes','no') NOT NULL DEFAULT 'no',last_action int(11) NOT NULL DEFAULT '0',prev_last_action int(11) NOT NULL DEFAULT '0',last_login int(11) NOT NULL DEFAULT '0',prev_last_login int(11) NOT NULL DEFAULT '0',PRIMARY KEY (guid),UNIQUE KEY username (username),KEY password (password),KEY email (email(50)),KEY last_action (last_action),KEY last_login (last_login),KEY admin (admin),FULLTEXT KEY name (name),FULLTEXT KEY name_2 (name,username)) ENGINE=MyISAM DEFAULT CHARSET=utf8; CREATE TABLE elggDB_users_remember_me_cookies (code varchar(32) NOT NULL,guid bigint(20) unsigned NOT NULL,timestamp int(11) unsigned NOT NULL,PRIMARY KEY (code),KEY timestamp (timestamp)) ENGINE=MyISAM DEFAULT CHARSET=utf8;  CREATE TABLE elggDB_users_sessions (session varchar(255) NOT NULL,ts int(11) unsigned NOT NULL DEFAULT '0',data mediumblob,PRIMARY KEY (session),KEY ts (ts)) ENGINE=MyISAM DEFAULT CHARSET=utf8"

service mysql restart

# Downloading Elgg and unzip
clear
echo "Downloading Elgg and unzip"

cd /var/www/
wget https://elgg.org/getelgg.php?forward=elgg-2.2.2.zip -O elgg.zip> /dev/null
unzip elgg.zip -d /var/www/html > /dev/null
rm elgg.zip

# Setting data directory and writeable by the webserver (www-data = Apache user).
echo "Setting data directory and writeable by the webserver"
mkdir -p /var/elggdata
chown www-data:www-data /var/elggdata

# Make a link to Kpax2
echo "Make a link to Kpax2"
ln -sf /var/www/html/elgg-2.2.2 /var/www/html/kpax2

# Configure settings.php
echo "About to configure settings.php"
# chown www-data:www-data /var/www/html/kpax2/elgg-config

cp /var/www/html/elgg-2.2.2/vendor/elgg/elgg/elgg-config/settings.example.php /var/www/html/kpax2/elgg-config/settings.php
cd /var/www/html/kpax2/elgg-config/
sed -i 's/{{timezone}}/Europe\/Amsterdam/g' settings.php
sed -i 's/{{dbuser}}/elgguser/g' settings.php
sed -i 's/{{dbpassword}}/elggpassword/g' settings.php
sed -i 's/{{dbname}}/elggDB/g' settings.php
sed -i 's/{{dbhost}}/localhost/g' settings.php
sed -i 's/{{dbprefix}}/elggDB_/g' settings.php

# Install Elgg
clear
echo "Navigate to http://localhost/kpax2/install.php to install"       
echo "End front-end installation script"

exit 0
