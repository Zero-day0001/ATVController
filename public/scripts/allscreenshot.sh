#!/bin/bash

IFS=$'\n'
lanip="$(grep -oE '\$lanip = .*;' config.php | tail -1 | sed 's/$lanip = //g;s/;//g;s/^"//;s/"$//')"
adbport="$(grep -oE '\$adbport = .*;' config.php | tail -1 | sed 's/$adbport = //g;s/;//g;s/^"//;s/"$//')"
dnl="$(grep -oE '\$namelocation = .*;' config.php | tail -1 | sed 's/$namelocation = //g;s/;//g;s/^"//;s/"$//')"
rm outputs/screenshot.log
exec > outputs/screenshot.log 2>&1
for i in `cat scripts/ips` ; do
  if [[ $i =~ "{".* ]] ; then
    first=$(echo $i | cut -d '.' -f1 | cut -d '{' -f2)
    last=$(echo $i | cut -d '.' -f3 | cut -d '}' -f1)
    for (( j = $first ; j <= $last ; j++ )) ; do
      ip="$lanip.$j"
      adb start-server
      adb connect $ip:$adbport
      if [[ $dnl == "globset" ]] ; then
        name=$(adb shell settings list global | grep "device_name" | cut -d '=' -f2)
      elif [[ $dnl == "atconf" ]] ; then
        name=$(adb shell cat /data/local/tmp/atlas_config.json | grep -oP '"deviceName": *"\K[^"]*')
      fi
      sleep 1
      echo Name Location - $dnl
      echo Taking screenshot of device - $ip - $name
      adb shell screencap -p /sdcard/screen.png
      adb pull /sdcard/screen.png screenshot/$name.png 
      adb shell rm /sdcard/screen.png
      adb kill-server
    done
  else
    ip="$lanip.$i"
    adb start-server
    adb connect $ip:$adbport
    if [[ $dnl == "globset" ]] ; then
      name=$(adb shell settings list global | grep "device_name" | cut -d '=' -f2)
    elif [[ $dnl == "atconf" ]] ; then
      name=$(adb shell cat /data/local/tmp/atlas_config.json | grep -oP '"deviceName": *"\K[^"]*')
    fi
    sleep 1
      echo Name Location - $dnl
      echo Taking screenshot of device - $ip - $name
      adb shell screencap -p /sdcard/screen.png
      adb pull /sdcard/screen.png screenshot/$name.png                          
      adb shell rm /sdcard/screen.png
      adb kill-server
  fi
done
echo Checking ADB server was killed
adb kill-server
