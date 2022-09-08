#!/bin/bash

IFS=$'\n'
lanip="$(grep -oE '\$lanip = .*;' config.php | tail -1 | sed 's/$lanip = //g;s/;//g;s/^"//;s/"$//')"
dbhost="$(grep -oE '\$servername = .*;' config.php | tail -1 | sed 's/$servername = //g;s/;//g;s/^"//;s/"$//')"
dbuser="$(grep -oE '\$username = .*;' config.php | tail -1 | sed 's/$username = //g;s/;//g;s/^"//;s/"$//')"
dbpass="$(grep -oE '\$password = .*;' config.php | tail -1 | sed 's/$password = //g;s/;//g;s/^"//;s/"$//')"
db="$(grep -oE '\$dbname = .*;' config.php | tail -1 | sed 's/$dbname = //g;s/;//g;s/^"//;s/"$//')"
port="$(grep -oE '\$port = .*;' config.php | tail -1 | sed 's/$port = //g;s/;//g;s/^"//;s/"$//')"
adbport="$(grep -oE '\$adbport = .*;' config.php | tail -1 | sed 's/$adbport = //g;s/;//g;s/^"//;s/"$//')"
rm outputs/buildinfo.log
exec > outputs/buildinfo.log 2>&1
adb kill-server
for i in `cat scripts/ips` ; do
  if [[ $i =~ "{".* ]] ; then
    first=$(echo $i | cut -d '.' -f1 | cut -d '{' -f2)
    last=$(echo $i | cut -d '.' -f3 | cut -d '}' -f1)
    for (( j = $first ; j <= $last ; j++ )) ; do
      ip="$lanip.$j"
      adb start-server
      sleep 1
      adb connect $ip:$adbport
      sleep 1
      name=$(adb shell settings list global | grep "device_name" | cut -d '=' -f2)
      atver=$(db shell dumpsys package com.pokemod.atlas | grep -E versionName | sed -e "s@    versionName=@@g")
      pokever=$(adb shell dumpsys package com.nianticlabs.pokemongo | grep -E versionName | sed -e "s@    versionName=@@g")
      anver=$(adb shell getprop ro.build.version.release)
      temp=$(adb shell cat /sys/class/thermal/thermal_zone0/temp | awk '{print substr($0, 1, length($0)-3)}')
      pip=$(adb shell settings list global | grep "global_http_proxy_host" | cut -d '=' -f2)
      pipp=$(adb shell settings list global | grep "global_http_proxy_port" | cut -d '=' -f2)
      fpip="$pip:$pipp"
      echo Name - $name
      echo Proxy - $fpip
      echo Temp - $temp
      echo Atlas - $atver
      echo Pogo - $pogover
      echo Android - $anver
      sleep 1
      mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "INSERT INTO Devices (ATVNAME, ATVTEMP, ATVLOCALIP, ATVPROXYIP, ATATVER, ATVPOGOVER, ANDROIDVER) VALUES ('$name', '$temp', '$ip', '$fpip', '$atver', '$pogover', '$anver') ON DUPLICATE KEY UPDATE ATVNAME = '$name';"
      adb kill-server
    done
  else
    adb start-server
    sleep 1
    ip="$lanip.$i"
    adb connect $ip:$adbport
    sleep 1
    name=$(adb shell settings list global | grep "device_name" | cut -d '=' -f2)
    atver=$(adb shell dumpsys package com.pokemod.atlas | grep -E versionName | sed -e "s@    versionName=@@g")
    pogover=$(adb shell dumpsys package com.nianticlabs.pokemongo | grep -E versionName | sed -e "s@    versionName=@@g")
    anver=$(adb shell getprop ro.build.version.release)
    temp=$(adb shell cat /sys/class/thermal/thermal_zone0/temp | awk '{print substr($0, 1, length($0)-3)}')
    pip=$(adb shell settings list global | grep "global_http_proxy_host" | cut -d '=' -f2)
    pipp=$(adb shell settings list global | grep "global_http_proxy_port" | cut -d '=' -f2)
    fpip="$pip:$pipp"
    echo Name - $name
    echo Proxy - $fpip
    echo Temp - $temp
    echo Atlas - $atver
    echo Pogo - $pogover
    echo Android - $anver
    sleep 1
    mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "INSERT INTO Devices (ATVNAME, ATVTEMP, ATVLOCALIP, ATVPROXYIP, ATVATVER, ATVPOGOVER, ANDROIDVER) VALUES ('$name', '$temp', '$ip', '$fpip', '$atver', '$pogover', '$anver') ON DUPLICATE KEY UPDATE ATVNAME = '$name';"
    adb kill-server
  fi
done
echo Checking ADB server was killed
adb kill-server
