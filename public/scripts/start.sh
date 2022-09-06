#!/bin/bash

IFS=$'\n'
lanip="$(grep -oE '\$lanip = .*;' config.php | tail -1 | sed 's/$lanip = //g;s/;//g;s/^"//;s/"$//')"
adbport="$(grep -oE '\$adbport = .*;' config.php | tail -1 | sed 's/$adbport = //g;s/;//g;s/^"//;s/"$//')"
rm outputs/startapps.log
exec > outputs/startapps.log 2>&1
for i in `cat scripts/ips` ; do
  if [[ $i =~ "{".* ]] ; then
    first=$(echo $i | cut -d '.' -f1 | cut -d '{' -f2)
    last=$(echo $i | cut -d '.' -f3 | cut -d '}' -f1)
    for (( j = $first ; j <= $last ; j++ )) ; do
      ip="$lanip.$j"
      adb start-server
      adb connect $ip:$adbport
      echo Starting Atlas Service on device - $ip
      sleep 1
      adb shell "am startservice com.pokemod.atlas/com.pokemod.atlas.services.MappingService"
      adb kill-server
    done
  else
    ip="$lanip.$i"
    adb start-server
    adb connect $ip:$adbport
    echo Starting Atlas Service on device - $ip
    sleep 1
    adb shell "am startservice com.pokemod.atlas/com.pokemod.atlas.services.MappingService"
    adb kill-server
  fi
done
echo Checking ADB server was killed
adb kill-server
