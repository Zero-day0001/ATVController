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

RDMdbhost="$(grep -oE '\$RDMservername = .*;' config.php | tail -1 | sed 's/$RDMservername = //g;s/;//g;s/^"//;s/"$//')"
RDMdbuser="$(grep -oE '\$RDMusername = .*;' config.php | tail -1 | sed 's/$RDMusername = //g;s/;//g;s/^"//;s/"$//')"
RDMdbpass="$(grep -oE '\$RDMpassword = .*;' config.php | tail -1 | sed 's/$RDMpassword = //g;s/;//g;s/^"//;s/"$//')"
RDMdb="$(grep -oE '\$RDMdbname = .*;' config.php | tail -1 | sed 's/$RDMdbname = //g;s/;//g;s/^"//;s/"$//')"
RDMport="$(grep -oE '\$RDMport = .*;' config.php | tail -1 | sed 's/$RDMport = //g;s/;//g;s/^"//;s/"$//')"

rm outputs/buildinfo.log
exec > outputs/buildinfo.log 2>&1
echo Building DB
built=1
      mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "UPDATE Updater SET BUILT = '$built';"
echo Built - $built
echo Setting Job
job=2
      mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "UPDATE Updater SET STATUS = '$job';"
echo Job - $job
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
      if [[ $dnl == "globset" ]] ; then
        name=$(adb shell settings list global | grep "device_name" | cut -d '=' -f2)
      elif [[ $dnl == "atconf" ]] ; then
        name=$(adb shell cat /data/local/tmp/atlas_config.json | grep -oP '"deviceName": *"\K[^"]*')
      fi
      atver=$(db shell dumpsys package com.pokemod.atlas | grep -E versionName | sed -e "s@    versionName=@@g")
      pokever=$(adb shell dumpsys package com.nianticlabs.pokemongo | grep -E versionName | sed -e "s@    versionName=@@g")
      anver=$(adb shell getprop ro.build.version.release)
      cputype=$(adb shell getprop ro.product.cpu.abi)
      temp=$(adb shell cat /sys/class/thermal/thermal_zone0/temp | awk '{print substr($0, 1, length($0)-3)}')
      pip=$(adb shell settings list global | grep "global_http_proxy_host" | cut -d '=' -f2)
      pipp=$(adb shell settings list global | grep "global_http_proxy_port" | cut -d '=' -f2)
      mac=$(adb shell cat /sys/class/net/eth0/address)
      deviceaccount=$(mysql -sN -u $RDMdbuser -p$RDMdbpass -h $RDMdbhost -P $RDMport -D $RDMdb -e "SELECT account_username FROM device WHERE uuid = '$name'")
      fpip="$pip:$pipp"
      echo Name Location - $dnl
      echo Name - $name
      echo Proxy - $fpip
      echo Mac - $mac
      echo Temp - $temp
      echo Atlas - $atver
      echo Pogo - $pogover
      echo Android - $anver
      echo CPU - $cputype
      echo Account - $deviceaccount
      sleep 1
      mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "INSERT INTO Devices (ATVNAME, ATVTEMP, ATVLOCALIP, ATVPROXYIP, ATVMAC, ATVACCOUNT, ATATVER, ATVPOGOVER, ANDROIDVER, CPUTPYE) VALUES ('$name', '$temp', '$ip', '$fpip', '$mac',  '$deviceaccount', '$atver', '$pogover', '$anver','$cputype') ON DUPLICATE KEY UPDATE ATVNAME = '$name';"
      adb kill-server
    done
  else
    adb start-server
    sleep 1
    ip="$lanip.$i"
    adb connect $ip:$adbport
    sleep 1
    if [[ $dnl == "globset" ]] ; then
      name=$(adb shell settings list global | grep "device_name" | cut -d '=' -f2)
    elif [[ $dnl == "atconf" ]] ; then
      name=$(adb shell cat /data/local/tmp/atlas_config.json | grep -oP '"deviceName": *"\K[^"]*')
    fi
    atver=$(adb shell dumpsys package com.pokemod.atlas | grep -E versionName | sed -e "s@    versionName=@@g")
    pogover=$(adb shell dumpsys package com.nianticlabs.pokemongo | grep -E versionName | sed -e "s@    versionName=@@g")
    anver=$(adb shell getprop ro.build.version.release)
    cputype=$(adb shell getprop ro.product.cpu.abi)
    temp=$(adb shell cat /sys/class/thermal/thermal_zone0/temp | awk '{print substr($0, 1, length($0)-3)}')
    pip=$(adb shell settings list global | grep "global_http_proxy_host" | cut -d '=' -f2)
    pipp=$(adb shell settings list global | grep "global_http_proxy_port" | cut -d '=' -f2)
    mac=$(adb shell cat /sys/class/net/eth0/address)
    deviceaccount=$(mysql -sN -u $RDMdbuser -p$RDMdbpass -h $RDMdbhost -P $RDMport -D $RDMdb -e "SELECT account_username FROM device WHERE uuid = '$name'")
    fpip="$pip:$pipp"
    echo Name Location - $dnl
    echo Name - $name
    echo Proxy - $fpip
    echo Mac - $mac
    echo Temp - $temp
    echo Atlas - $atver
    echo Pogo - $pogover
    echo Android - $anver
    echo CPU - $cputype
    echo Account - $deviceaccount
    sleep 1
    mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "INSERT INTO Devices (ATVNAME, ATVTEMP, ATVLOCALIP, ATVPROXYIP, ATVMAC, ATVACCOUNT, ATVATVER, ATVPOGOVER, ANDROIDVER, CPUTYPE) VALUES ('$name', '$temp', '$ip', '$fpip', '$mac', '$deviceaccount', '$atver', '$pogover', '$anver', '$cputype') ON DUPLICATE KEY UPDATE ATVNAME = '$name';"
    adb kill-server
  fi
done
echo Setting Job
job=0
      mysql -u $dbuser -p$dbpass -h $dbhost -P $port -D $db -e "UPDATE Updater SET STATUS = '$job';"
echo Job - $job
echo Checking ADB server was killed
adb kill-server
