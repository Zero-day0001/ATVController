#!/bin/bash

IFS=$'\n'
lanip="$(grep -oE '\$lanip = .*;' config.php | tail -1 | sed 's/$lanip = //g;s/;//g;s/^"//;s/"$//')"
dbhost="$(grep -oE '\$servername = .*;' config.php | tail -1 | sed 's/$servername = //g;s/;//g;s/^"//;s/"$//')"
dbuser="$(grep -oE '\$username = .*;' config.php | tail -1 | sed 's/$username = //g;s/;//g;s/^"//;s/"$//')"
dbpass="$(grep -oE '\$password = .*;' config.php | tail -1 | sed 's/$password = //g;s/;//g;s/^"//;s/"$//')"
db="$(grep -oE '\$dbname = .*;' config.php | tail -1 | sed 's/$dbname = //g;s/;//g;s/^"//;s/"$//')"
port="$(grep -oE '\$port = .*;' config.php | tail -1 | sed 's/$port = //g;s/;//g;s/^"//;s/"$//')"
adbport="$(grep -oE '\$adbport = .*;' config.php | tail -1 | sed 's/$adbport = //g;s/;//g;s/^"//;s/"$//')"
rm outputs/gettemp.log
exec > outputs/gettemp.log 2>&1
adb kill-server
for i in `cat scripts/ips` ; do
  if [[ $i =~ "{".* ]] ; then
    first=$(echo $i | cut -d '.' -f1 | cut -d '{' -f2)
    last=$(echo $i | cut -d '.' -f3 | cut -d '}' -f1)
    for (( j = $first ; j <= $last ; j++ )) ; do
      ip="$lanip.$j" 
      adb start-server
      sleep .5
      adb connect $ip:$adbport
      sleep .5
      temp=$(adb shell cat /sys/class/thermal/thermal_zone0/temp | awk '{print substr($0, 1, length($0)-3)}')
      echo Checking TEMP at 
      echo Device - $ip:$adbport
      echo Temp - $temp
      mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "UPDATE Devices SET ATVTEMP = '$temp' WHERE ATVLOCALIP = '$ip';"
      adb kill-server
    done
  else
    ip="$lanip.$i"
    adb start-server
    sleep .5
    adb connect $ip:$adbport
    sleep .5
    temp=$(adb shell cat /sys/class/thermal/thermal_zone0/temp | awk '{print substr($0, 1, length($0)-3)}')
     echo Checking TEMP at 
     echo Device - $ip:$adbport
     echo Temp - $temp
    mysql -u $dbuser -p$dbpass -h $dbhost -P $port  -D $db -e "UPDATE Devices SET ATVTEMP = '$temp' WHERE ATVLOCALIP = '$ip';"
    adb kill-server
  fi
done
echo Checking ADB server was killed
adb kill-server
