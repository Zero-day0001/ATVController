<?php
require('config.php');
require('sections.php');
require('functions.php');
require('footer.php');

function Menu() {
require('config.php');
echo '<head>' .
	'<title>ATV Controller</title>' .
	'<meta name="viewport" content="width=device-width, initial-scale=1">' .
	'<link rel="icon" type="image/png" href="/favicon.png">' .
	'<link rel="shortcut icon" href ="favicon.png">' .
	'<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">' .
	'<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">' .
	'<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script> ' .
	'<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>' .
	'<link rel="stylesheet" href="css/style.css">' .
'</head>' .

'<header>'.

'<div class="topnav" id="TopnavMenu">' .
  '<a href="#" class="logo">ATV Controller</a>' .
  '<a href="index.php">Home</a>' .
    
    '<div class="dropdown">' .
      '<button onclick="dropbuttoneditor()" class="dropbtn">Config Creator</button>' .
      '<div id="editor" class="dropdown-content">' .
      '<a href="editor.php">Atlas Config Creator</a>' .
      '<a href="emagiskeditor.php">eMagisk Config Creator</a>' .
      '</div>' .
    '</div>' .
    '<div class="dropdown">' .
      '<button onclick="dropbuttonSC()" class="dropbtn">Server Controls</button>' .
      '<div id="serverControl" class="dropdown-content">' .
        '<a href="scanner.php">Device Scanner(WIP)</a>' .
        '<a href="servercontrols.php?control=resetdb">Reset DB</a>' .
        '<a href="servercontrols.php?control=reboot">Reboot Server</a>' .
        '<a href="servercontrols.php?control=killadb">Kill ADB</a>' .
      '</div>' .
  '</div>' .
  '<div class="dropdown">' .
      '<button onclick="dropbuttonlogs()" class="dropbtn">Log Viewer</button>' .
      '<div id="logviewer" class="dropdown-content">' .
      '<a href="logviewer.php?logtoview=buildinfo">Build Logs</a>' .
    '<a href="logviewer.php?logtoview=gettemp">Temp Logs</a>' .
    '<a href="logviewer.php?logtoview=getversion">Version Logs</a>' .
    '<a href="logviewer.php?logtoview=screenshot">Screenshot Logs</a>' .
    '<a href="logviewer.php?logtoview=updatepogo">Update Pogo Logs</a>' .
    '<a href="logviewer.php?logtoview=updateatlas">Updater Atlas Logs</a>' .
    '<a href="logviewer.php?logtoview=stop">Stop Logs</a>' .
    '<a href="logviewer.php?logtoview=start">Start Logs</a>' .
    '<a href="logviewer.php?logtoview=updater">Updater Logs</a>' .
      '</div>' .
   '</div>' ;
    
?>
<script>
function dropbuttoneditor() {
  document.getElementById("editor").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}

function dropbuttonSC() {
  document.getElementById("serverControl").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}

function dropbuttonlogs() {
  document.getElementById("logviewer").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>
<?php
    
  echo '<a href="javascript:void(0);" class="icon" onclick="menu()">' .
    '<i class="fa fa-bars"></i>' .
  '</a>';

    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    //Check Connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }else{
    
    $lastseen = " SELECT * FROM Updater WHERE ID = '1'; ";
    $res = $conn->query($lastseen);
    $conn->close();
    while($rows=$res->fetch_assoc()){
        $gstatus = $rows['STATUS'];
        $lastcheck = $rows['LASTCHECK'];
        $lastc = strtotime($lastcheck);
    }
    if($gstatus == 1){
        $stat = "UPDATING";
    }elseif($gstatus == 2){
        $stat = "JOB";
    }else{
        $stat = "IDLE";
    }
    }
    
  echo '<a href="editor.php" style="float:right;">STATUS: '.$stat.'(';
    $timeDiff = (time() - $lastc) +1;
    //Convert to seconds, minutes, hours
    $seconds = $timeDiff % 60;
    $minutes = floor(($timeDiff % 3600) / 60);
    $hours = floor($timeDiff / 3600);
    if($hours > 0) echo "$hours" . "h, ";
    if($minutes > 0) echo "$minutes" . "m, ";
    echo "$seconds" . "s ago";
       echo ')</a>' .
       '</div>';

'</header>' . 

'<body>';
 } ?>
