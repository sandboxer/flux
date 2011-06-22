#!/bin/bash

db_dbname="testfluxAPI"
source EDITME_config

echo "====BEGIN TABLES INSTALLATION====="
echo "* Creating database '$db_dbname'"

#destroy and then create the database:
mysql -u $db_username -p"$db_password" -e "DROP DATABASE IF EXISTS $db_dbname;CREATE DATABASE $db_dbname;"

#
# TABLE users::::::::::::
#
echo "* Creating TABLE: users"
query="CREATE TABLE users(
user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
email VARCHAR(32),
password VARCHAR(32),
cellphone VARCHAR(32),
confirmed BOOL DEFAULT 0,
PRIMARY KEY (user_id)
) ENGINE = InnoDB;"
mysql -u $db_username -p"$db_password" -e "use $db_dbname;$query"

#
# TABLE fluxes:::::::::::
#
echo "* Creating TABLE: fluxes"
query="CREATE TABLE fluxes(
flux_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
PRIMARY KEY (flux_id)
) ENGINE = InnoDB;"
mysql -u $db_username -p"$db_password" -e "use $db_dbname;$query"

#
# TABLE routes:::::::::::
#

echo "* Creating TABLE: routes"
#create the table with the 'routes'
query="CREATE TABLE routing(
flux_from_id INT UNSIGNED NOT NULL,
flux_to_id INT UNSIGNED NOT NULL,
share INT UNSIGNED NOT NULL DEFAULT 0,
INDEX (flux_from_id),
INDEX (flux_to_id),
PRIMARY KEY (flux_from_id,flux_to_id)
) ENGINE = InnoDB;"
mysql -u $db_username -p"$db_password" -e "use $db_dbname;$query"

echo "* Populating    : routes"
#randomly populate table 'routes'
query="INSERT INTO routing VALUES
(1,1,10), (2,2,20), (3,3,30), (1,2,30);
"
mysql -u $db_username -p"$db_password" -e "use $db_dbname;$query"


echo "======INSTALLATION COMPLETE!======"