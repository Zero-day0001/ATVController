#!/bin/bash

rm outputs/killadb.log
exec > outputs/killadb.log 2>&1
echo Killing ADB Service.
killall ADB
