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

Enable or Disable wth true=off/false=off  
```
$noScreenshot = true;
$noProxy = false;
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

## Start ATVController.  

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
-------------------------------------------------------------------

## HOW TO USE  

### THESE COMMANDS ONCE HIT CAN TAKE A MINUTE OR MORE TO RUN DEPENDING ON DEVICE COUNT!.  
When you hit a button below. It is safe to hit the "HOME" button in the menu to trigger a refresh.  
These scripts will continue to run in the background until there done, you may refresh the page after to see the updates.  

Load the page. (http://localhost:3000) (Change port in ATVController.js). 
On first page load it will create the table in the DB you selected.  

The db table and on page device table will be blank.  
The first option to the run and build the database with your device info is "Build Info".  

"Build Info"  
This will take your 'ips' list and do the following.  
Get the Name, Proxy (if any) and store this infomation the the db along with the local ip from the 'config and ip files set'.  

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

Built by @zero-day-#0001  

Scripts provided by @Xerock  

 
