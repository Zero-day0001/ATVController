#!/bin/bash

IFS=$'\n'
lanip="$(grep -oE '\$lanip = .*;' public/config.php | tail -1 | sed 's/$lanip = //g;s/;//g;s/^"//;s/"$//')"
dbhost="$(grep -oE '\$servername = .*;' public/config.php | tail -1 | sed 's/$servername = //g;s/;//g;s/^"//;s/"$//')"
dbuser="$(grep -oE '\$username = .*;' public/config.php | tail -1 | sed 's/$username = //g;s/;//g;s/^"//;s/"$//')"
dbpass="$(grep -oE '\$password = .*;' public/config.php | tail -1 | sed 's/$password = //g;s/;//g;s/^"//;s/"$//')"
db="$(grep -oE '\$dbname = .*;' public/config.php | tail -1 | sed 's/$dbname = //g;s/;//g;s/^"//;s/"$//')"
port="$(grep -oE '\$port = .*;' public/config.php | tail -1 | sed 's/$port = //g;s/;//g;s/^"//;s/"$//')"
adbport="$(grep -oE '\$adbport = .*;' public/config.php | tail -1 | sed 's/$adbport = //g;s/;//g;s/^"//;s/"$//')"
dnl="$(grep -oE '\$namelocation = .*;' public/config.php | tail -1 | sed 's/$namelocation = //g;s/;//g;s/^"//;s/"$//')"

RDMdbhost="$(grep -oE '\$RDMservername = .*;' public/config.php | tail -1 | sed 's/$RDMservername = //g;s/;//g;s/^"//;s/"$//')"
RDMdbuser="$(grep -oE '\$RDMusername = .*;' public/config.php | tail -1 | sed 's/$RDMusername = //g;s/;//g;s/^"//;s/"$//')"
RDMdbpass="$(grep -oE '\$RDMpassword = .*;' public/config.php | tail -1 | sed 's/$RDMpassword = //g;s/;//g;s/^"//;s/"$//')"
RDMdb="$(grep -oE '\$RDMdbname = .*;' public/config.php | tail -1 | sed 's/$RDMdbname = //g;s/;//g;s/^"//;s/"$//')"
RDMport="$(grep -oE '\$RDMport = .*;' public/config.php | tail -1 | sed 's/$RDMport = //g;s/;//g;s/^"//;s/"$//')"

rm public/outputs/updater.log
exec > public/outputs/updater.log 2>&1
echo Changing Status to Running
status=1
echo Status - $status
      mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "UPDATE Updater SET status = '$status';"
adb kill-server
for i in `cat public/scripts/ips` ; do
  if [[ $i =~ "{".* ]] ; then
    first=$(echo $i | cut -d '.' -f1 | cut -d '{' -f2)
    last=$(echo $i | cut -d '.' -f3 | cut -d '}' -f1)
    for (( j = $first ; j <= $last ; j++ )) ; do
      ip="$lanip.$j" 
      adb start-server
      sleep .5
      adb connect $ip:$adbport
      sleep .5
      if [[ $dnl == "globset" ]] ; then
        name=$(adb shell settings list global | grep "device_name" | cut -d '=' -f2)
      elif [[ $dnl == "atconf" ]] ; then
        name=$(adb shell cat /data/local/tmp/atlas_config.json | grep -oP '"deviceName": *"\K[^"]*')
      fi
      atver=$(adb shell dumpsys package com.pokemod.atlas | grep -E versionName | sed -e "s@    versionName=@@g")
      pokever=$(adb shell dumpsys package com.nianticlabs.pokemongo | grep -E versionName | sed -e "s@    versionName=@@g")
      temp=$(adb shell cat /sys/class/thermal/thermal_zone0/temp | awk '{print substr($0, 1, length($0)-3)}')
      pip=$(adb shell settings list global | grep "global_http_proxy_host" | cut -d '=' -f2)
      pipp=$(adb shell settings list global | grep "global_http_proxy_port" | cut -d '=' -f2)
      fpip="$pip:$pipp"
      deviceaccount=$(mysql -sN -u $RDMdbuser -p$RDMdbpass -h $RDMdbhost -P $RDMport -D $RDMdb -e "SELECT account_username FROM device WHERE uuid = '$name'")
      echo Name Location - $dnl
      echo Name - $name
      echo Proxy - $fpip
      echo Temp - $temp
      echo Atlas - $atver
      echo Pogo - $pokever
      echo Account - $deviceaccount
      echo Taking screenshot of device - $ip - $name
      adb shell screencap -p /sdcard/screen.png
      adb pull /sdcard/screen.png public/screenshot/$name.png
      adb shell rm /sdcard/screen.png
      adb kill-server
      sleep 1
      mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "UPDATE Devices SET ATVNAME = '$name', ATVTEMP = '$temp', ATVPROXYIP = '$fpip', ATVACCOUNT = '$deviceaccount', ATVATVER = '$atver', ATVPOGOVER = '$pokever' WHERE ATVNAME = '$name';"
      adb kill-server
    done
  else
    ip="$lanip.$i"
    adb start-server
    sleep .5
    adb connect $ip:$adbport
    sleep .5
      if [[ $dnl == "globset" ]] ; then
        name=$(adb shell settings list global | grep "device_name" | cut -d '=' -f2)
      elif [[ $dnl == "atconf" ]] ; then
        name=$(adb shell cat /data/local/tmp/atlas_config.json | grep -oP '"deviceName": *"\K[^"]*')
      fi
      atver=$(adb shell dumpsys package com.pokemod.atlas | grep -E versionName | sed -e "s@    versionName=@@g")
      pokever=$(adb shell dumpsys package com.nianticlabs.pokemongo | grep -E versionName | sed -e "s@    versionName=@@g")
      temp=$(adb shell cat /sys/class/thermal/thermal_zone0/temp | awk '{print substr($0, 1, length($0)-3)}')
      pip=$(adb shell settings list global | grep "global_http_proxy_host" | cut -d '=' -f2)
      pipp=$(adb shell settings list global | grep "global_http_proxy_port" | cut -d '=' -f2)
      fpip="$pip:$pipp"
      deviceaccount=$(mysql -sN -u $RDMdbuser -p$RDMdbpass -h $RDMdbhost -P $RDMport -D $RDMdb -e "SELECT account_username FROM device WHERE uuid = '$name'")
      echo Name Location - $dnl
      echo Name - $name
      echo Proxy - $fpip
      echo Temp - $temp
      echo Atlas - $atver
      echo Pogo - $pokever
      echo Account - $deviceaccount
      echo Taking screenshot of device - $ip - $name
      adb shell screencap -p /sdcard/screen.png
      adb pull /sdcard/screen.png public/screenshot/$name.png
      adb shell rm /sdcard/screen.png
      adb kill-server
      sleep 1
      mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "UPDATE Devices SET ATVNAME = '$name', ATVTEMP = '$temp', ATVPROXYIP = '$fpip', ATVACCOUNT = '$deviceaccount', ATVATVER = '$atver', ATVPOGOVER = '$pokever' WHERE ATVNAME = '$name';"
    adb kill-server
  fi
done
echo Checking ADB server was killed
echo Changing Status to Idle
status=0
echo Status - $status
      mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "UPDATE Updater SET status = '$status';"
adb kill-server
