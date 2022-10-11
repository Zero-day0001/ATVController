#!/bin/bash

IFS=$'\n'
dbhost="$(grep -oE '\$servername = .*;' config.php | tail -1 | sed 's/$servername = //g;s/;//g;s/^"//;s/"$//')"
dbuser="$(grep -oE '\$username = .*;' config.php | tail -1 | sed 's/$username = //g;s/;//g;s/^"//;s/"$//')"
dbpass="$(grep -oE '\$password = .*;' config.php | tail -1 | sed 's/$password = //g;s/;//g;s/^"//;s/"$//')"
db="$(grep -oE '\$dbname = .*;' config.php | tail -1 | sed 's/$dbname = //g;s/;//g;s/^"//;s/"$//')"
port="$(grep -oE '\$port = .*;' config.php | tail -1 | sed 's/$port = //g;s/;//g;s/^"//;s/"$//')"

rm outputs/resetdb.log
exec > outputs/resetdb.log 2>&1

echo Checking Status
    statcheck=$(mysql -sN -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "SELECT status FROM Updater WHERE id = '1'")
    if [[ $statcheck == 1 ]] ; then
        echo Updater Already Running. Stopping
        exit 0
    elif [[ $statcheck == 2 ]] ; then
        echo Job Currently Running
        exit 0
    fi

echo Changing Status to Running
status=1
mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "UPDATE Updater SET STATUS = '$status';"
echo Status - $status

echo Resetting Database.

echo Trucating Devices Table.
mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "TRUNCATE Devices;"

echo Resetting Built Status
built=0
mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "UPDATE Updater SET BUILT = '$built';"
echo Built - $built
echo Done.







