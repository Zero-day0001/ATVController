# ATVController - WIP - Much to add and fix. 

ATVController for android devices running with RDM > POGO > ATLAS. 

Quick, simple and dirty adb controls with a GUI view and MYSQL Storage. 

## WARNING. POTENTIAL SECURUITY RISKS INVOLED.

Use at your own risk of course.  
Do not publicly expose the port.  
Local use only.  
(On page shell. Unsanitized mysql inputs, This will change over time).   

## TESTED SETUP.  

Rasp pi - Ubuntu   
Rasp pi - Raspbian
  
## REQUIREMENTS.  

Clone the repo with.  
```
git clone https://github.com/lilcezz/ATVController.git
```

Enter the directory with.  
```
cd ATVController
```

Download and install Node.js and PHP With  
```
curl -sL https://deb.nodesource.com/setup_18.x | sudo -E bash -
```

```
sudo apt-get install -y nodejs php
```

```
sudo apt install php-fpm php-common php-mbstring php-xmlrpc php-soap php-gd php-xml php-intl php-mysql php-cli php-ldap php-zip php-curl php-cgi
```

Install PM2 if you need it with  
```
npm install pm2 -g
```

Install ADB if you need it with  
```
sudo apt-get install android-tools-adb android-tools-fastboot
```

Install MYSQL CLIENT if you need it with
```
sudo apt-get install mysql-client  
```

## FILES AND THINGS TO EDIT.  

#### Edit the updater script sleep timer  
#### "ATVController.js"
Set this the time based on your number of devices. 

20 seconds per devices
6 devices = 2
```
var minute = 3
```

#### Copy the config.   
#### "public/config.php"  
```
cp config.php.example config.php
```

With a text editor   

Set this to the starting 3 ranges of your ip  
```
$lanip=0.0.0 
```

Edit the DB info to connect  

### important option
Only use "globset" if you set the device name in the device settings.
Otherwise use "atconf" to get the device name from your atlas conf. 
```
$namelocation = "globset" or "atconf"
```

Enable or Disable wth true=off/false=off  
```
$devicecount = true;
$noRegister = false;
```

You shouldn't need to edit the ADB port  

#### IPS file (has no extention).  
#### Copy the ips  
"public/scripts/ips"  
```
cp example.ips ips
```

Edit with a text editor like vim or nano and make a list with the ip ends of each device to finsh the ip address from config.php file  
```
$ vim ips
```
Place your ip ends like this  
```
32  
65
132
165
```
(This Part should be done automatically on pull. If your scripts doesnt seem to be executed then try this, but dont do this on the first setup.)
### MAKE THE SHELLS EXECUTABLE FILES.  
### "/public/scripts/"  
```
chmod +x *.sh
```

## Start ATVController with PM2 / DOCKER BELOW

BEFORE YOU START THIS WITH PM2.  

Fix a depreciation warning in node-php after your do the steps above and have installed node.  
```
cd /path/to/ATVController/node_modules/node-php
```

Replace the main.js file here by running this command.  
```
wget -O main.js https://raw.githubusercontent.com/Zero-day0001/node-php-fix/main/main.js
```

Enter the ATVcontroller directory.  
```
cd ATVController
```

For good measure? and only needs to be run ONCE!
```
npm install
```

Start the service with PM2.  
```
pm2 start ATVController.js
```

## DOCKER INSTALLATION
```
1. Clone repository with git clone https://github.com/Zero-day0001/ATVController.git
```
```
2. Copy content of docker-compose.yml.example into your running docker-compose.yml
  - Adjust ports if necessary "3000:3000" to e.g. "3010:3000" (leave right one as it is!!!)
  - Adjust in depends_on "dbcontainer" to name of your dbcontainer service, if you use a db in docker container, else if you use a normal DB, uncomment Rows "depends_on" and "- dbcontainer" with #.
  ```
  ```
3. Copy+Rename and fill out the config files:
cd ATVController/public
cp config.php.example config.php
edit -> fill in your data
cd ATVController/public/scripts
cp example.ips ips
edit -> fill in your data
```
```
4. Build your container with:
docker-compose build atvc
docker-compose up -d atvc
```
get logs: docker-compose logs -f atvc

Load the page. (http://localhost:port)


-------------------------------------------------------------------

## HOW TO USE  

Load the page. (http://localhost:3000) (Change port in ATVController.js). 

On the first page load it will create the tables in the DB you selected.  

If no account is made yet, it will force you to create one.

Login to the controller.

On first sign in it will start to build the db with the info from devices based on your IPS file

You can refresh the page after its loaded to the empty table. You can view the build logs via the menu Log viewer.

Once the first account is made you may edit the config option to disable register.


-------------------------------------------------------------------

## BULK CONTROLS
### THESE COMMANDS ONCE HIT CAN TAKE A MINUTE OR MORE TO RUN DEPENDING ON DEVICE COUNT!.  
When you hit a button below. It is safe to hit the "HOME" button in the menu to trigger a refresh.  
These scripts will continue to run in the background until there done, you may refresh the page after to see the updates. 
You may view the logs of these buttons in the Log Viewer via the menu.

"Get all temps".  
Gets all temps from ALL devices and will build this info to display in the table.  

"Update All Devices".  
Will Push the .apks from the folder location apps/ .  
Installs the "pokemongo.apk" (rename the apk the match this title) app located inside folder /apps to the device.  
Installs the "atlas.apk" (rename the apk the match this title) app located inside folder /apps to the device.

"Reboot All Devices".   
Will Reboot all devices defined in 'ips'.  

"Start All Atlas".  
Will Start the Atlas Mapping Service on all devices defined in 'ips'.  

"Stop All Apps".  
Will Stop Atlas Mapping Mervice And Pokemon on all devices defined in 'ips'.  

-------------------------------------------------------------------

## PER DEVICE CONTROLS

Click a device to find these controls. 

"Reboot".  
Reboots device.

"start Atlas". 
Start Atlas Mapping Service on device.

"Stop Atlas/Pogo".  
Stop both Atlas and Pogo Services on device.

"Update Pokemon".  
Installs the "pokemongo.apk" (rename the apk the match this title) app located inside folder /apps to the device.  

"Updated Atlas".  
Installs the "atlas.apk" (rename the apk the match this title) app located inside /apps folder to the device.  

"Update Atlas Config".  
Pushes the "atlas.config" located inside /apps folder to the device. (EDIT THIS FILE TO YOUR NEEDS).  

"Push eMagisk.zip".  
Pushes the "eMagisk.zip" located inside /apps folder to the device.

"Push emagisk.config".  
Pushes the "emagisk.config" located inside /apps folder to the device. (EDIT THIS FILE TO YOUR NEEDS).  

*** PROXY ***  
"Change".  
In the text area place your new proxy ip in the format.  
```
IP:PORT i.e 123.456.789.123:98765
```
(Does not support username/password proxys yet, whitelist your IP).  
 
*** SCREENSHOT ***  
"Get Screen Shot".  
This section only builds on press of the button atm.



-------------------------------------------------------------------

## CONFIG CREATOR 

Found in the menu

"Atlas creator".
```
You can create an atlas config or generator a config for all your devices. 
```


"eMagisk creator".
```
You can create an eMagisk config. 
```

These must be done for the buttons Update atlas or emagisk config to work in single device controls. 

-------------------------------------------------------------------

## SERVER CONTROLS 

Found in the menu

"Device Scanner (WIP)".
```
Scans your network for devices with port 5555 open. 
```

"Reset DB".
```
Will rebuild the DB device table.  
```

"Reboot Server".
```
Will reboot the host machine
```

"Kill ADB".
```
Will adb the ADB service if it got stuck
```

"Update Apps(WIP)".
```
Will doenload the supported version of either pogo and atlas apks.
```

These must be done for the buttons Update atlas or emagisk config to work in single device controls. 

-------------------------------------------------------------------

## LOG VIEWER

Found in the menu

"Shows logs for the select the script ".
```
Will load and view the currect logs for bulk jobs in the browser
```

-------------------------------------------------------------------

Built by @zero-day-#0001  

Scripts provided by @Xerock 
Dockerfiles provided by  @ReuschelCologne

Join the Discord!  
https://discord.gg/XRTxWzYXtb

 
