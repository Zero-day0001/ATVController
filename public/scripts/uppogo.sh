#!/bin/bash

IFS=$'\n'
lanip="$(grep -oE '\$lanip = .*;' config.php | tail -1 | sed 's/$lanip = //g;s/;//g;s/^"//;s/"$//')"
dbhost="$(grep -oE '\$servername = .*;' config.php | tail -1 | sed 's/$servername = //g;s/;//g;s/^"//;s/"$//')"
dbuser="$(grep -oE '\$username = .*;' config.php | tail -1 | sed 's/$username = //g;s/;//g;s/^"//;s/"$//')"
dbpass="$(grep -oE '\$password = .*;' config.php | tail -1 | sed 's/$password = //g;s/;//g;s/^"//;s/"$//')"
db="$(grep -oE '\$dbname = .*;' config.php | tail -1 | sed 's/$dbname = //g;s/;//g;s/^"//;s/"$//')"
port="$(grep -oE '\$port = .*;' config.php | tail -1 | sed 's/$port = //g;s/;//g;s/^"//;s/"$//')"
adbport="$(grep -oE '\$adbport = .*;' config.php | tail -1 | sed 's/$adbport = //g;s/;//g;s/^"//;s/"$//')"
dnl="$(grep -oE '\$namelocation = .*;' config.php | tail -1 | sed 's/$namelocation = //g;s/;//g;s/^"//;s/"$//')"
rm outputs/update-pogo.log
exec > outputs/update-pogo.log 2>&1
echo Setting Job
job=2
      mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "UPDATE Updater SET STATUS = '$job';"
echo Job - $job
for i in `cat scripts/ips` ; do
  if [[ $i =~ "{".* ]] ; then
    first=$(echo $i | cut -d '.' -f1 | cut -d '{' -f2)
    last=$(echo $i | cut -d '.' -f3 | cut -d '}' -f1)
    for (( j = $first ; j <= $last ; j++ )) ; do
      ip="$lanip.$j"
      adb start-server
      adb connect $ip:$adbport
      echo Updating Pokemon on $ip
      sleep 1
      adb install -r apps/pokemongo.apk
      adb kill-server
    done
  else
    ip="$lanip.$i"
    adb start-server
    adb connect $ip:$adbport
    echo Updating Pokemon on $ip
    sleep 1
    adb install -r apps/pokemongo.apk
    adb kill-server
  fi
done
echo Setting Job
job=0
      mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "UPDATE Updater SET STATUS = '$job';"
echo Job - $job
echo Checking ADB server was killed
adb kill-server
