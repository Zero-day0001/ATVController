<?php

// SCRIPTS TO RUN ON ALL DEVICES

function deviceinfo() {
	echo '<form id="deviceinfo" action="index.php" method="post" onsubmit="return confirmscreen()">' .
	'<button name="deviceinfo" type="submit" class="btn btn-primary menuButton">Build Info</button>' .
	'</form>';
	if(isset($_POST['deviceinfo'])){
		echo $res=shell_exec('scripts/deviceinfo.sh > /dev/null 2>&1 &');
	}
}

function tempbutton() {
	echo
	'<form id="temp" action="index.php" method ="post" onsubmit="return confirmscreen()">' .
		'<button name="temp" type="submit" class="btn btn-primary menuButton">Recollect Temps</button>' .
	'</form>';
	if(isset($_POST['temp'])){
		echo $res=shell_exec('scripts/tempcheck.sh > /dev/null 2>&1 &');
	}
}

function rebootbutton() {
	echo 
	'<form id="reboot" action="index.php" method ="post" onsubmit="return confirmscreen()">' . 
		'<button name="reboot" type="submit" class="btn btn-primary menuButton">Reboot ALL</button>' .
	'</form>';
	if(isset($_POST['reboot'])){
		echo $res=shell_exec('scripts/reboot.sh > /dev/null 2>&1 &');
	}
}

function vercheck() {
        echo
        '<form id="vercheck" action="index.php" method ="post" onsubmit="return confirmscreen()">' .
                '<button name="vercheck" type="submit" class="btn btn-primary menuButton">Recollect Versions</button>' .
        '</form>';
        if(isset($_POST['vercheck'])){
                echo $res=shell_exec('scripts/vercheck.sh > /dev/null 2>&1 &');
        }
}

function allscreenshot() {
        echo
        '<form id="allscreenshot" action="index.php" method ="post" onsubmit="return confirmscreen()">' .
                '<button name="allscreenshot" type="submit" class="btn btn-primary menuButton">Recollect Screenshots</button>' .
        '</form>';
        if(isset($_POST['allscreenshot'])){
                echo $res=shell_exec('scripts/allscreenshot.sh > /dev/null 2>&1 &');
        }
}

function anvercheck() {
        echo
        '<form id="anvercheck" action="index.php" method ="post" onsubmit="return confirmscreen()">' .
                '<button name="anvercheck" type="submit" class="btn btn-primary menuButton">Recollect Android Version</button>' .
        '</form>';
        if(isset($_POST['anvercheck'])){
                echo $res=shell_exec('scripts/anvercheck.sh > /dev/null 2>&1 &');
        }
}

function upatlas() {
	echo 
	'<form id="upatlas" action="index.php" method ="post" onsubmit="return confirmscreen()">' . 
		'<button name="upatlas" type="submit" class="btn btn-primary menuButton">Update Atlas ALL</button>' .
	'</form>';
	if(isset($_POST['upatlas'])){
		echo $res=shell_exec('scripts/upat.sh > /dev/null 2>&1 &');
	}
}

function startbutton() {
	echo 
	'<form id="start" action="index.php" method ="post" onsubmit="return confirmscreen()">' . 
		'<button name="start" type="submit" class="btn btn-primary menuButton">Start Scanning ALL</button>' .
	'</form>';
	if(isset($_POST['start'])){
		echo $res=shell_exec('scripts/start.sh > /dev/null 2>&1 &');
	}
}

function stopbutton() {
	echo 
	'<form id="stop" action="index.php" method ="post" onsubmit="return confirmscreen()">' . 
		'<button name="stop" type="submit" class="btn btn-primary menuButton">Stop Scanning ALL</button>' .
	'</form>';
	if(isset($_POST['stop'])){
		echo $res=shell_exec('scripts/stop.sh > /dev/null 2>&1 &');
	}
}
function uppogo() {
	echo 
	'<form id="uppogo" action="index.php" method ="post" onsubmit="return confirmscreen()">' .
                '<button name="uppogo" type="submit" class="btn btn-primary menuButton">Update Pokemon ALL</button>' .
        '</form>';	
	if(isset($_POST['uppogo'])){
		echo $res=shell_exec('scripts/uppogo.sh > /dev/null 2>&1 &');
	}
}
function moreToCome() {
	echo
	'<form>' .
		'<button class="btn btn-secondary menuButton">More Soon ➜</button>' .
	'</form>';
	//if(isset($_POST['NotSetYet'])){
	//	echo $res=shell_exec('scripts/stop.sh');
	//}
}

// TABLE DATA DISPLAY AND PER DEVICE CONTROLLER

function devicetable() {
include("config.php");

//MYSQLI CONNECTION

$conn = new mysqli($servername, $username, $password, $dbname, $port);
// Checking for connections
if ($conn->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
}
$sql = " SELECT * FROM Devices; ";
$result = $conn->query($sql);
$conn->close();

//START OF TABLE INFO
echo '<div class="cssContainer">' . 
	'<table class="table table-dark table-striped">' .
		'<thead class="text-center">' . 
			'<tr>' .
				'<th>Device Name</th>' .
				'<th>Temp</th>' .
				'<th>Local IP</th>';
	
				if($noProxy === false){
				echo '<th>Proxy IP</th>';
				}

				if($noAccount === false){
				echo 'Account';
				}

				if($noLastSeen === false){
                                echo 'Last Seen';
                                } 				

				echo '<th>PoGo Version</th>' .
				'<th>Atlas Version</th>' .
				'<th>Android Version</th>' .
				'<th>Controls</th>';
	
				if($noScreenshot === false){
				echo '<th>Screenshot</th>';
				}	
			echo '</tr>' .
		'</thead>';
		echo '<tbody class="text-center">';
		while($rows=$result->fetch_assoc()){
			$id = $rows['ID'];	
			$name = $rows['ATVNAME'];
			$atvtemp = $rows['ATVTEMP'];
			$localip = $rows['ATVLOCALIP'];
			$atvproxy = $rows['ATVPROXYIP'];
			$atvpogover = $rows['ATVPOGOVER'];
			$atvatver = $rows['ATVATVER'];
			$anver = $rows['ANDROIDVER'];
			if(empty($name)){
				$name = "N/A";
			}
			if(empty($atvtemp)){
                                $atvtemp = "N/A";
			}
			if(empty($atvproxy) || $atvproxy == ":" || $atvproxy == ":0"){
                                $atvproxy = "N/A";
			}
			if(empty($atvpogover)){
                                $atvpogover = "N/A";
			}
			if(empty($atvatver)){
                                $atvatver = "N/A";
			}
			if(empty($anver)){
                                $anver = "N/A";
                        }
			echo '<tr id=device-' . $name . '>' .
				'<td class="align-middle">' . $name . '</td>' .
				'<td class="align-middle">' . $atvtemp . '°C</td>' .
				'<td class="align-middle">' . $localip . '</td>';
				if($noProxy === false){
					echo '<td class="align-middle">' . $atvproxy . '</td>';
				}

				if($noAccount === false){
				// Get Device Account
				echo '<td class="align-middle">';
					$conn = new mysqli($RDMservername, $RDMusername, $RDMpassword, $RDMdbname, $RDMport);
                                                        // Checking for connections
                                                if ($conn->connect_error) {
                                                        die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
                                                }else{
                                                        $acct = " SELECT account_username FROM device WHERE uuid = '$name'; ";
							$res = $conn->query($acct);
							$conn->close();
							while($rows=$res->fetch_assoc()){
							$account = $rows['account_username'];
							}
							echo "$account";
						}
				echo '</td>';
				}

				if($noLastSeen === false){
				 echo '<td class="align-middle">';
                                        $conn = new mysqli($RDMservername, $RDMusername, $RDMpassword, $RDMdbname, $RDMport);
                                                        // Checking for connections
                                                if ($conn->connect_error) {
                                                        die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
                                                }else{
                                                        $lastseen = " SELECT last_seen FROM device WHERE uuid = '$name'; ";
                                                        $res = $conn->query($lastseen);
                                                        $conn->close();
                                                        while($rows=$res->fetch_assoc()){
                                                        $lastseentime = $rows['last_seen'];
							}
							$timeconvert = date("d-m-Y\TH:i:s",$lastseentime);
                                                        echo "$timeconvert";
                                                }
                                echo '</td>';
				}

				// Get PoGo Version
				echo '<td class="text-center align-middle"> ' . $atvpogover . '</td>';
				
				// Get Atlas Version
				echo '<td class="align-middle">' . $atvatver . '</td>';

				// Get Android Version
                                echo '<td class="text-center align-middle"> ' . $anver . '</td>';

				echo '<td class="controlTable">'; // Device Options for Users ---
				
					// Reboot Device
					echo
					'<div class="tab">
						<button class="tablink tablinks-' . $name . '" onclick="openTab(event, \'tabGeneral-' . $name .'\', \'' . $name . '\')">General</button>
						<button class="tablink tablinks-' . $name . '" onclick="openTab(event, \'tabAtlas-' . $name .'\', \'' . $name . '\')">Atlas</button>
						<button class="tablink tablinks-' . $name . '" onclick="openTab(event, \'tabAPKs-' . $name .'\', \'' . $name . '\')">APKs</button>
						<button class="tablink tablinks-' . $name . '" onclick="openTab(event, \'tabMisc-' . $name .'\', \'' . $name . '\')">Misc</button>
					</div>	';
					
					//General TAB
					echo '<div id="tabGeneral-' . $name .'" class="tabcontent tabcontent-' . $name .'">';

							// Reboot Single device 
							echo '<form class="d-inline" id="reboot-' . $name . '" action="index.php#device-' . $name . '" method ="post" onsubmit="return confirmsingle()">' .
								'<button name="reboot-' . $name . '" type="submit" class="btn btn-danger controlButton">Reboot</button>' .
							'</form>';
							if(isset($_POST["reboot-$name"])){
								echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
								echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
								echo $res=shell_exec('adb shell reboot > /dev/null 2>&1');
								echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							}
							
						// Get Logfile
							echo
	                        '<form class="d-inline" id="logfile-' . $name . '" action="index.php#device-' . $name . '" method ="post" align="center">' .
                                '<button name="logfile-' . $name . '" type="submit" class="btn btn-warning controlButton">Pull Logfile</button>' .
        	                '</form>';
                	        if(isset($_POST["logfile-$name"])){
                                echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                                echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                                echo $res=shell_exec("adb pull /data/local/tmp/atlas.log deviceLogs/$name.log > /dev/null 2>&1");
                                echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        	}
							
						// Open Logfile
							$filename = __DIR__ .'/deviceLogs/' . $name . '.log';
							if(file_exists($filename)){
								echo
								'<form class="d-inline" id="logfile-' . $name . '" align="center">' .
									'<button onclick="viewLogs(\'' . $name . '\')" type="button" class="btn btn-warning controlButton">View Logfile</button>' .
								'</form>';
								if(isset($_POST["logfile-$name"])){
									echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
									echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
									echo $res=shell_exec("adb pull /data/local/tmp/atlas.log deviceLogs/$name.log > /dev/null 2>&1");
									echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
								}
							}

							
							// Get Screenshot
							echo
	                        '<form class="d-inline" id="scrshot-' . $name . '" action="index.php#device-' . $name . '" method ="post" align="center">' .
                                '<button name="scrshot-' . $name . '" type="submit" class="btn btn-success controlButton">Pull Screenshot</button>' .
        	                '</form>';
                	        if(isset($_POST["scrshot-$name"])){
                                echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                                echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                                echo $res=shell_exec('adb shell screencap -p /sdcard/screen.png > /dev/null 2>&1');
                                echo $res=shell_exec("adb pull /sdcard/screen.png screenshot/$name.png > /dev/null 2>&1");
                                echo $res=shell_exec("adb shell rm /sdcard/screen.png > /dev/null 2>&1");
                                echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
				}
				if($noProxy === false){
				echo '<br><br>Change Proxy' .
					'<form id="proxy" method="post" onsubmit="return confirmsingle()">' .
                                                                '<textarea name="proxy-' . $name . '" placeholder="IP:PORT" rows="1" style="resize:none"></textarea><br>' .
                                                                '<input type="submit" value="Change">' .
                                                                '</form>';
                                                        if(isset($_POST["proxy-$name"])){
                                                        $text = $_POST["proxy-$name"];
                                                        if(empty($text)){
                                                                echo "No proxy set";
                                                                }else{
                                                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                                                        echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                                                        echo $res=shell_exec("adb shell settings put global http_proxy $text > /dev/null 2>&1");
                                                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                                                        $conn = new mysqli($servername, $username, $password, $dbname, $port);
                                                        // Checking for connections
                                                        if ($conn->connect_error) {
                                                        die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
                                                        }else{
                                                        $sql = " UPDATE Devices SET ATVPROXYIP = '$text' WHERE ID = $id; ";
                                                        $conn->query($sql);
                                                        $conn->close();
                                                        ?>
                                                        <script>
                                                        window.location.reload();
                                                        </script>
                                                        <?php
                                                        }
                                                        }
                                                        }
				}


					echo '</div>';
							
					//Atlas TAB
					echo '<div id="tabAtlas-' . $name .'" class="tabcontent tabcontent-' . $name .'">';
						
						// Start Atlas
						echo '<form class="d-inline" id="start-' . $name . '" action="index.php#device-' . $name . '" method ="post" onsubmit="return confirmsingle()">' .
							'<button name="start-' . $name . '" type="submit" class="btn btn-success controlButton">Start Atlas</button>' .
						'</form>';
						if(isset($_POST["start-$name"])){
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
							echo $res=shell_exec('adb shell "am startservice com.pokemod.atlas/com.pokemod.atlas.services.MappingService" > /dev/null 2>&1');
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
						}
						// Stop Pogo & Atlas
						echo '<form class="d-inline" id="stop-' . $name . '" action="index.php#device-' . $name . '" method ="post" onsubmit="return confirmsingle()">' .
							'<button name="stop-' . $name . '" type="submit" class="btn btn-danger controlButton">Stop Atlas</button>' .
						'</form>';
						if(isset($_POST["stop-$name"])){
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
							echo $res=shell_exec('adb shell "su -c am force-stop com.nianticlabs.pokemongo & am force-stop com.pokemod.atlas" > /dev/null 2>&1');
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
						}
						// Update Atlas Config
						echo '<form class="d-inline" id="config-atlas-' . $name . '" action="index.php#device-' . $name . '" method ="post" onsubmit="return confirmsingle()">' .
							'<button name="update-atlas-' . $name . '" type="submit" class="btn btn-warning controlButton">Push Atlas Config</button>' .
						'</form>';
						if(isset($_POST["config-atlas-$name"])){
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
							echo $res=shell_exec("adb push app/$name_atlas_config.json /data/local/tmp/atlas_config.json > /dev/null 2>&1");
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
						}
					echo '</div>';
					
					// APKs TAB
					echo '<div id="tabAPKs-' . $name .'" class="tabcontent tabcontent-' . $name .'">';

						// Update PoGo APK
						echo '<form class="d-inline" id="update-pogo-' . $name . '" action="index.php#device-' . $name . '" method ="post" onsubmit="return confirmsingle()">' .
							'<button name="update-pogo-' . $name . '" type="submit" class="btn btn-primary controlButton">Push PoGo APK</button>' .
						'</form>';
						if(isset($_POST["update-pogo-$name"])){
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
							echo $res=shell_exec('adb install -r app/pokemongo.apk > /dev/null 2>&1');
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
						}	

						// Update Atlas APK
						echo '<form class="d-inline" id="update-atlas-' . $name . '" action="index.php#device-' . $name . '" method ="post" onsubmit="return confirmsingle()">' .
							'<button name="update-atlas-' . $name . '" type="submit" class="btn btn-primary controlButton">Push Atlas APK</button>' .
						'</form>';
						if(isset($_POST["update-atlas-$name"])){
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
							echo $res=shell_exec('adb install -r app/atlas.apk > /dev/null 2>&1');
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
						}
					echo '</div>';// End of Device Options Tablerow
					
					// Misc TAB
					echo '<div id="tabMisc-' . $name .'" class="tabcontent tabcontent-' . $name .'">';

						// Get PoGo Version
							echo '<form class="d-inline" id="version-pogo-' . $name . '" action="index.php#device-' . $name . '" method ="post">' .
								'<button name="version-pogo-' . $name . '" type="submit" class="btn btn-primary controlButton">Get Version PoGo</button>' .
                            '</form>';
							if(isset($_POST["version-pogo-$name"])){
								echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
								echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
								$pogver = shell_exec('adb shell dumpsys package com.nianticlabs.pokemongo | grep -E versionName | sed -e "s@    versionName=@@g"');
								$conn = new mysqli($servername, $username, $password, $dbname, $port);
								//Checking for connections
								if ($conn->connect_error) {
										die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
								}else {
										$sql = " UPDATE Devices SET ATVPOGOVER = '$pogver' WHERE ID = $id; ";
										$conn->query($sql);
										echo "Checking PoGo Version";
										$conn->close();
										echo $res=shell_exec('adb kill-server > /dev/null 2>&1'); ?>
										<script>
										window.location.reload();
										</script>
								<?php
								}
							}

						// Get Atlas Version
							echo '<form class="d-inline" id="version-atlas-' . $name . '" action="index.php#device-' . $name . '" method ="post">' .
								'<button name="version-atlas-' . $name . '" type="submit" class="btn btn-primary controlButton">Get Version Atlas</button>' .
                            '</form>';
                            if(isset($_POST["version-atlas-$name"])){
                                echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                                echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                                $atver = shell_exec('adb shell dumpsys package com.pokemod.atlas | grep -E versionName | sed -e "s@    versionName=@@g"');
                                $conn = new mysqli($servername, $username, $password, $dbname, $port);
                                //Checking for connections
                                if ($conn->connect_error) {
                                        die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
                                }else {
                                        $sql = " UPDATE Devices SET ATVATVER = '$atver' WHERE ID = $id; ";
                                        $conn->query($sql);
                                        echo "Checking Atlas Version";
                                        $conn->close();
                                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1'); ?>
                                        <script>
                                        window.location.reload();
                                        </script>
                                <?php
                                }
                            }	
							
						// get Android Version
							echo '<form class="d-inline" id="version-android-' . $name . '" action="index.php#device-' . $name . '" method ="post">' .
								'<button name="version-android-' . $name . '" type="submit" class="btn btn-primary controlButton">Get Version Android</button>' .
                            '</form>';
                            if(isset($_POST["version-android-$name"])){
                                echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                                echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                                $anvers= shell_exec('adb shell getprop ro.build.version.release');
                                $conn = new mysqli($servername, $username, $password, $dbname, $port);
                                //Checking for connections
                                if ($conn->connect_error) {
                                        die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
                                }else {
                                        $sql = " UPDATE Devices SET ANDROIDVER = '$anvers' WHERE ID = $id; ";
                                        $conn->query($sql);
                                        echo "Checking Android Version";
                                        $conn->close();
                                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1'); ?>
                                        <script>
                                        window.location.reload();
                                        </script>
                                <?php
                                }
							}
					
						// Push eMagisk.zip to Device
							echo '<form class="d-inline" id="push-emagisk-' . $name . '" action="index.php#device-' . $name . '" method ="post" onsubmit="return confirmsingle()">' .
								'<button name="push-emagisk-' . $name . '" type="submit" class="btn btn-primary controlButton">Push eMagisk.zip</button>' .
							'</form>';
							if(isset($_POST["push-emagisk-$name"])){
								echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
								echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
								echo $res=shell_exec('adb push app/eMagisk.zip /sdcard > /dev/null 2>&1');
								echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							}
							
						// Push eMagisk Config to Device
							echo '<form class="d-inline" id="config-emagisk-' . $name . '" action="index.php#device-' . $name . '" method ="post" onsubmit="return confirmsingle()">' .
								'<button name="config-emagisk-' . $name . '" type="submit" class="btn btn-primary controlButton">Push eMagisk Config</button>' .
							'</form>';
							if(isset($_POST["config-emagisk-$name"])){
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
							echo $res=shell_exec('adb push app/emagisk.congig /data/local/tmp > /dev/null 2>&1');
							echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
							}	
							
							
					echo '</div>';// End of Device Options Tablerow
					
				echo '</td>';?> 

				<script>
					function openTab(evt, tabName, deviceName) {
						var i, tabcontent, tablinks;
						if(evt.currentTarget.classList.contains("active")){
							document.getElementById(tabName).style.display = "none";
							tablinks = document.getElementsByClassName("tablinks-" + deviceName);
							for (i = 0; i < tablinks.length; i++) {
								tablinks[i].className = tablinks[i].className.replace(" active", "");
							}
						}else {
							tabcontent = document.getElementsByClassName("tabcontent-" + deviceName);
							for (i = 0; i < tabcontent.length; i++) {
								tabcontent[i].style.display = "none";
							}
							tablinks = document.getElementsByClassName("tablinks-" + deviceName);
							for (i = 0; i < tablinks.length; i++) {
								tablinks[i].className = tablinks[i].className.replace(" active", "");
							}
							document.getElementById(tabName).style.display = "block";
							evt.currentTarget.className += " active";
						}
					}
					
					// Get the element with id="defaultOpen" and click on it
					//document.getElementById("defaultOpen").click();
				</script>
				<?php
				if($noScreenshot === false){
					echo '<td class="align-middle;">';
					$filename = __DIR__ .'/screenshot/' . $name . '.png';
					if(file_exists($filename)){
						echo 
						'<div class="imageContainer">' .
							'<a href="screenshot/' . $name . '.png" target="_blank" >' .
								'<img src="screenshot/' . $name . '.png" width="25" height="40" />' .
							'</a>' .
						'</div>';
					}
					else{
						echo 'No Screenshot Found.';
					}
					echo '</td>';
				}
			echo '</tr>';
		}
	echo '</tbody>
	</table>
	<div class="modal fade" id="modalLogFile" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 id="modalTitle" class="modal-title text-center">Logfile for Device </h5> (Last 200 Lines)
				</div>
				<div class="modal-body">
					<div class="input-group mb-4">
						<textarea id="logFileContent" style="height:500px;" class="form-control" readonly></textarea>
					</div>
					<div class="row mb-4">
						<div class="col-md-4">
							<label class="mb-1">Avg. Jumpspeed:</label>
							<div class="form-group mb-2">
								<input id="avgJumpSpeed" class="form-control" type="text" placeholder="" readonly>
							</div>
						</div>
						<div class="col-md-4">
							<label class="mb-1">Avg. Time per Monster:</label>
							<div class="form-group mb-2">
								<input id="avgTimePerMonster" class="form-control" type="text" placeholder="" readonly>
							</div>
						</div>
						<div class="col-md-4">
							<label class="mb-1">Avg. IV-Checks per 15m</label>
							<div class="form-group mb-2">
								<input id="avgMonsterPerTime" class="form-control" type="text" placeholder="" readonly>
							</div>
						</div>
					</div>
					<div class="modal-footer pb-1">
							<a id="fullLogsButton" href="" target="_blank" >
								<button type="button" class="btn btn-secondary" >Full Logfile</button>
							</a>
						<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>';
	?>
	<script>
		function populateLogfileModal(url,device) {
			var xhr = new XMLHttpRequest();
			xhr.onload = function () {
				//document.getElementById('logFileContent').textContent = this.responseText;
				var text = this.responseText;
				text = text.split("\n");
				var outputText = '----- Trimmed Logfile for Device ' + device + ' -----\n\n';
				for (var i=0;i<text.length-1;i++){
					if(i > text.length-202){
						text[i] = text[i].replace('[32m[1m', ' ');
						text[i] = text[i].replace('[22m[39m', ' ');
						text[i] = text[i].replace('[0;31m', ' ');
						text[i] = text[i].replace('[0;33m', ' ');
						text[i] = text[i].replace('[0m', ' ');
						outputText +=  text[i] + '\n';
					}
					if(i == text.length-2){
						var avj = text[i].substring(text[i].indexOf('avj:') + 4, text[i].indexOf('avj:') + 8);
						var avj = parseFloat(avj);
						var avm = text[i].substring(text[i].indexOf('avm:') + 4, text[i].indexOf('avm:') + 8);
						var avm = parseFloat(avm);
						var avgChecks = Math.floor((3600/4)/avm);
					}
				}
				$('#avgJumpSpeed').val(avj + 's');
				$('#avgTimePerMonster').val(avm + 's');
				$('#avgMonsterPerTime').val('~' + avgChecks);
				$('#logFileContent').val(outputText);
				$('#fullLogsButton').attr("href", url);
				$('#modalTitle').text('Logfile for Device ' + device)
			};
			xhr.open('GET', url);
			xhr.send();
		}
		function viewLogs(device){
			var logPath = 'deviceLogs/' + device + '.log';
			populateLogfileModal(logPath,device);
			$('#modalLogFile').modal('show');
		}
		$('#modalLogFile').on('hidden.bs.modal', function(event) {
			$('#logFileContent').val('');
			$('#avgJumpSpeed').val('');
			$('#avgTimePerMonster').val('');
			$('#avgMonsterPerTime').val('');
		});
		
	</script>

<?php
echo '</div>';
}

function editatconf(){
include("config.php");
echo '<div class="cssContainer">' .
     '<div style="color:#fff;"><center>';
    // Select Json File
    $atconfig = file_get_contents("apps/atlas_config.json");

if(empty($atconfig)){
echo 'No Atlas Config File Found, Would you like to make one?<br>' .
     '<form class="d-inline" id="atconfcreate" action="editor.php" method ="post">' .
     '<button name="atconfcreate" type="submit" class="btn btn-primary">Make Atlas config</button>' .
     '</form>';
	if(isset($_POST['atconfcreate'])){
                echo $res=shell_exec('cp apps/atlas_config.json.example apps/atlas_config.json> /dev/null 2>&1 &');
		?>
		<script>
		window.location.reload();
		</script>
		<?php
	}	
}else{
echo '<h3>Current Config</h3>' .
     '<textarea row=2 style="resize:none;width:75%;" readonly>'.$atconfig.'</textarea><br><br>' .
     '<h4>Edit Atlas Config</h4>';

    $array = json_decode($atconfig, true);
    extract($array);
    
    if(empty($authBearer)){
       $authBearer = "";
    }
    if(empty($deviceAuthToken)){
       $deviceAuthToken = "";
    }
    if(empty($deviceName)){
       $deviceName = "";
    }
    if(empty($email)){
       $email = "";
    }
    if(empty($rdmUrl)){
       $rdmUrl = "";
    }
    if($runOnBoot == "1"){
       $runOnBoot = "true";
    }else{
       $runOnBoot = "false";
    }
    echo '<form id="atconfcreator" method="post">' .
    
        '<label for="authBearer">authBearer</label><br>' .
        '<input type="text" id="authBearer" name="authBearer" placeholder="'.$authBearer.'" value="'.$authBearer.'"><br>' .
        
        '<label for="deviceAuthToken">deviceAuthToken</label><br>' .
        '<input type="text" id="deviceAuthToken" name="deviceAuthToken" placeholder="'.$deviceAuthToken.'" value="'.$deviceAuthToken.'" required><br>' .
    
        '<label for="deviceName">deviceName</label><br>' .
        '<input type="text" id="deviceName" name="deviceName" placeholder="'.$deviceName.'" value="'.$deviceName.'"><br>' .
        
        '<label for="email">email</label><br>' .
        '<input type="text" id="email" name="email" placeholder="'.$email.'" value="'.$email.'" required><br>' .
        
        '<label for="rdmUrl">rdmUrl</label><br>' .
        '<input type="text" id="rdmUrl" name="rdmUrl" placeholder="'.$rdmUrl.'" value="'.$rdmUrl.'" required><br><br>' .

        '<label for="runOnBoot">Run On Boot:</label><br>' .
        '<select id="runOnBoot" name="runOnBoot" required>' .
        '<option value="" disabled selected hidden>--</option>' .
        '<option value="true">true</option>' .
        '<option value="false">false</option>' .
        '</select><br><br>' .
    
    '<button name="atconfcreator" type="submit" class="btn btn-primary">Save</button><br>' .
    'Or Generate for all(Name does not matter!)<br>' .
    '<button name="atconfbulkcreator" type="submit" class="btn btn-primary">Generate</button>' .
    '</form>';
    
    if(isset($_POST['atconfcreator'])){
        $AB = $_POST["authBearer"];
        if(empty($AB)){
            $AB = "";
        }
        
        $DAT = $_POST["deviceAuthToken"];
        if(empty($DAT)){
            $DAT = "";
        }
        
        $DN = $_POST["deviceName"];
        if(empty($DN)){
            $DN = "";
        }
        
        $EM = $_POST["email"];
        if(empty($EM)){
            $EM = "";
        }
        
        $RURL = $_POST["rdmUrl"];
        if(empty($RURL)){
            $RURL = "";
        }
        
        $ROB = $_POST["runOnBoot"];
        if(empty($ROB)){
            echo "";
        }
        
        $file = fopen("apps/atlas_config.json","w");
        fwrite($file,'{"authBearer":"'.$AB.'","deviceAuthToken":"'.$DAT.'","deviceName":"'.$DN.'","email":"'.$EM.'","rdmUrl":"'.$RURL.'","runOnBoot":'.$ROB.'}');
        fclose($file);
        ?>
        <script>
        window.location.reload();
        </script>
        <?php
    }
            
            if(isset($_POST['atconfbulkcreator'])){
                $AB = $_POST["authBearer"];
                if(empty($AB)){
                    $AB = "";
                }
                
                $DAT = $_POST["deviceAuthToken"];
                if(empty($DAT)){
                    $DAT = "";
                }
                
                $EM = $_POST["email"];
                if(empty($EM)){
                    $EM = "";
                }
                
                $RURL = $_POST["rdmUrl"];
                if(empty($RURL)){
                    $RURL = "";
                }
                
                $ROB = $_POST["runOnBoot"];
                if(empty($ROB)){
                    echo "";
                }
                
                $conn = new mysqli($servername, $username, $password, $dbname, $port);
                // Checking for connections
                if ($conn->connect_error) {
                    die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
                }
                $sql = " SELECT * FROM Devices; ";
                $result = $conn->query($sql);
                $conn->close();
                
                while($rows=$result->fetch_assoc()){
                    $name = $rows['ATVNAME'];
                    $file = fopen('apps/'.$name.'_atlas_config.json',"w");
                    fwrite($file,'{"authBearer":"'.$AB.'","deviceAuthToken":"'.$DAT.'","deviceName":"'.$name.'","email":"'.$EM.'","rdmUrl":"'.$RURL.'","runOnBoot":'.$ROB.'}');
                    fclose($file);
                    }
                ?>
                <script>
                window.location.reload();
                </script>
                <?php
            }
            
            
}
echo '</center></div>' .
     '</div>';

}
?>
