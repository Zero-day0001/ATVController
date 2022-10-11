#!/bin/bash

rm outputs/rebootserver.log
exec > outputs/rebootserver.log 2>&1
echo Rebooting Server.
reboot
