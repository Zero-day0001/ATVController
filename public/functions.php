<?php

// SCRIPTS TO RUN ON ALL DEVICES

function auth() {
    $urlparse = $_GET["type"];
    if($urlparse == "register"){
        require("config.php");
        if($noRegister == "true"){
            echo '<div class="cssContainer text-center">' .
                 '<h5 style="color:#fff;">Registration is disabled. Contact the admin or check your config.</h5>' .
                 '</div>';
        }else{
    echo '<div class="cssContainer text-center">'.
         '<form action="auth.php?type=register" method="post" style="border:1px solid #ccc">' .
         '<div class="container" style="color:#fff;">' .
            '<h1 style="color:#fff;">Create Admin Account</h1>' .
            '<hr>' .
                '<label for="username"><b>Username</b></label></br></br>' .
                '<input type="text" placeholder="Enter Username" name="username" required></br></br>' .
                    '<label for="psw"><b>Password</b></label></br>' .
                    '<input type="password" placeholder="Enter Password" name="psw" required></br></br>' .
                        '<label for="psw-repeat"><b>Repeat Password</b></label></br>' .
                        '<input type="password" placeholder="Repeat Password" name="psw-repeat" required></br></br>' .

         '<div class="clearfix">' .
         '<button name="signupbtn" id="signupbtn" type="submit" class="btn btn-primary">Sign Up</button></br></br>' .
         '</div>' .
         '</div>' .
         '</form>';
    
    if(isset($_POST['signupbtn'])){
        $userName = trim($_POST["username"]);
        if(!preg_match('/^[a-zA-Z0-9_]+$/', $userName)){
        $userName_err = 1;
        echo '<p style="color:#fff;">Username can only contain letters, numbers, and underscores.</p>';
        }
        
        $psw = trim($_POST["psw"]);
        if(strlen($psw) < 6){
                $password_err = 1;
                echo '<p style="color:#fff;">Password must have atleast 6 characters.</p>';
            }
        
        $pswrepeat = trim($_POST["psw-repeat"]);
        if($psw != $pswrepeat){
            $password_err = 1;
            echo '<p style="color:#fff;">Passwords do not match.</p>';
        }
        
        if($userName_err == 1 || $password_err == 1){
            session_destroy();
            echo '<script>' .
                 'location.replace("/auth.php?type=register");' .
                 '</script>';
        }else{
            require("config.php");
            $hashed_password = password_hash($psw, PASSWORD_DEFAULT);
            
            $conn = new mysqli($servername, $username, $password, $dbname, $port);
            //Check Connection
            if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
            }else{
                $sql = " INSERT INTO Users (USERNAME, PASSWORD) VALUES ('$userName', '$hashed_password'); ";
                $result = $conn->query($sql);
                $conn->close();
            }
            echo '<script>' .
                 'location.replace("/auth.php?type=login");' .
                 '</script>';
        }
    }
  echo '</div>';

}}elseif($urlparse == "login"){
    require("config.php");
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    //Check Connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }else{
        $sql = " SELECT * FROM Users; ";
        $result = $conn->query($sql);
        $conn->close();
        while($rows=$result->fetch_assoc()){
            $userIDcount = $rows['ID'];
        }
        if(empty($userIDcount)){
            echo '<script>' .
                 'location.replace("/auth.php?type=register");' .
                 '</script>';
        }
    }
    echo '<div class="cssContainer text-center">'.
         '<form method="post" style="border:1px solid #ccc">' .
         '<div class="container" style="color:#fff;">' .
            '<h1 style="color:#fff;">Login</h1>' .
            '<hr>' .
                '<label for="username"><b>Username</b></label></br></br>' .
                '<input type="text" placeholder="Enter Username" name="username_login" required></br></br>' .
                    '<label for="psw"><b>Password</b></label></br>' .
                    '<input type="password" placeholder="Enter Password" name="psw_login" required></br></br>' .
         '<div class="clearfix">' .
         '<button name="loginbtn" id="loginbtn" type="submit" class="btn btn-primary">Login</button></br></br>' .
         '</div>' .
         '</div>' .
         '</form>';
    if(isset($_POST['loginbtn'])){
        $userName = trim($_POST["username_login"]);
        if(!preg_match('/^[a-zA-Z0-9_]+$/', $userName)){
        $userName_err = 1;
        }
        
        $psw_login = trim($_POST["psw_login"]);
        
        if($userName_err == 1){
        session_destroy();
        echo '<script>' .
             'location.replace("/auth.php?type=login");' .
             '</script>';
        }else{
            require("config.php");
            $conn = new mysqli($servername, $username, $password, $dbname, $port);
            //Check Connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }else{
                $sql = " Select * from Users WHERE USERNAME = '$userName'; ";
                $result = $conn->query($sql);
                $conn->close();
                while($rows=$result->fetch_assoc()){
                    $loginUserID = $rows['ID'];
                    $loginUser = $rows['USERNAME'];
                    $loginUserPass = $rows['PASSWORD'];
                }
                if(empty($loginUser)){
                    session_destroy();
                    echo '<p style="color:#fff;">User Not Found.</p>' .
                         '<script>' .
                         'location.replace("/auth.php?type=login");' .
                         '</script>';
                }
                if($loginUser == $userName){
                    if(password_verify($psw_login, $loginUserPass)) {
                        $_SESSION['UserID']=$loginUserID;
                        $_SESSION['LoggedinUser']=$loginUser;
                        $_SESSION['sess_id']=session_id();
                        echo '<p style="color:#fff;">Logging in!</p>' .
                             '<script>' .
                             'location.replace("/auth.php?type=startsession");' .
                             '</script>';
                    }else{
                        session_destroy();
                        echo '<p style="color:#fff;">Password incorrect.</p>' .
                             '<script>' .
                             'location.replace("/auth.php?type=login");' .
                             '</script>';
                    }
                }
            }
        }
    }
echo '</div>';
}elseif($urlparse == "startsession"){
    require("config.php");
            $uid = $_SESSION['UserID'];
            $sess_id = $_SESSION['sess_id'];
            $conn = new mysqli($servername, $username, $password, $dbname, $port);
            //Check Connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }else{
                $sql = " UPDATE Users SET SESSION = '$sess_id' WHERE ID = '$uid'; ";
                $result = $conn->query($sql);
                $conn->close();
                echo '<script>' .
                     'location.replace("/index.php?");' .
                     '</script>';
            }
}elseif($urlparse == "killsession"){
    require("config.php");
    $urlparseuser = $_SESSION['UserID'];
            $conn = new mysqli($servername, $username, $password, $dbname, $port);
            //Check Connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }else{
                $sess_id = "";
                $sql = " UPDATE Users SET SESSION = '$sess_id' WHERE ID = '$urlparseuser'; ";
                $result = $conn->query($sql);
                $conn->close();
                session_destroy();
                echo '<script>' .
                     'location.replace("/");' .
                     '</script>';
            }
    echo '<script>' .
         'location.replace("/");' .
         '</script>';
}elseif($urlparse == "changepassword"){
    require("config.php");
    if(!isset($_SESSION['UserID']))
    {
    header("Location: /auth.php?type=login");
        exit;
    }
    $uid = $_SESSION['UserID'];
    $uname = $_SESSION['LoggedinUser'];
    echo '<div class="cssContainer text-center">';
            $conn = new mysqli($servername, $username, $password, $dbname, $port);
            //Check Connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }else{
                $sess_id = "";
                $sql = " Select * from Users WHERE ID = '$uid'; ";
                $result = $conn->query($sql);
                $conn->close();
                while($rows=$result->fetch_assoc()){
                    $uidc = $rows['ID'];
                    $uname = $rows['USERNAME'];
                    $upass = $rows['PASSWORD'];
                }
                
           echo '<form method="post" style="border:1px solid #ccc">' .
                '<div class="container" style="color:#fff;">' .
                   '<h1 style="color:#fff;">Change Password</h1>' .
                   '<hr>' .
                       //'<label for="uname-change"><b>Change Username</b></label></br></br>' .
                       //'<input type="text" placeholder="'.$uname.'" name="uname-change"></br></br>' .
                           
                           '<label for="psw_change"><b>New Password</b></label></br>' .
                           '<input type="password" placeholder="Enter New Password" name="psw_change" required></br></br>' .
                
                               '<label for="psw_repeat_change"><b>Confirm Password</b></label></br>' .
                               '<input type="password" placeholder="Confirm Password" name="psw_repeat_change" required></br></br>' .

                '<div class="clearfix">' .
                '<button name="updatepassword" id="updatepassword" type="submit" class="btn btn-primary">Update</button></br></br>' .
                '</div>' .
                '</div>' .
                '</form>';
                
                if(isset($_POST['updatepassword'])){
                    //$userName = trim($_POST["username"]);
                    //if(!preg_match('/^[a-zA-Z0-9_]+$/', $userName)){
                    //$userName_err = 1;
                    //echo '<p style="color:#fff;">Username can only contain letters, numbers, and underscores.</p>';
                    //}
                    
                    $psw_change = trim($_POST["psw_change"]);
                    if(strlen($psw_change) < 6){
                            $password_err = 1;
                            echo '<p style="color:#fff;">Password must have atleast 6 characters.</p>';
                        }
                    
                    $pswrepeatchange = trim($_POST["psw_repeat_change"]);
                    if($psw_change != $pswrepeatchange){
                        $password_err = 1;
                        echo '<p style="color:#fff;">Passwords do not match.</p>';
                    }
                    
                    if($password_err == 1){
                        echo '<script>' .
                             'location.replace("/auth.php?type=changepassword&job=Failed");' .
                             '</script>';
                    }else{
                        require("config.php");
                        $hashed_password_change = password_hash($psw_change, PASSWORD_DEFAULT);
                        
                        $conn = new mysqli($servername, $username, $password, $dbname, $port);
                        //Check Connection
                        if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                        }else{
                            $sql = " UPDATE Users SET PASSWORD = '$hashed_password_change' WHERE ID = '$uidc'; ";
                            $result = $conn->query($sql);
                            $conn->close();
                        
                        echo '<script>' .
                             'location.replace("/auth.php?type=changepassword&job=Success");' .
                             '</script>';
                        }
                    }
                }
                
            }
    $urlparsepswc = $_GET["job"];
    echo '<p style="color:#fff;">'.$urlparsepswc.'</p>';
    echo '</div>';
}else{
    session_destroy();
    echo '<script>' .
         'location.replace("/");' .
         '</script>';
}
}

function deviceinfo() {
    require("config.php");
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    //Check Connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }else{
    $lastseen = " SELECT * FROM Updater WHERE ID = '1'; ";
    $res = $conn->query($lastseen);
    $conn->close();
    while($rows=$res->fetch_assoc()){
        $status = $rows['STATUS'];
    }
    if($status === 1 || $status == 2){
        echo '<button class="btn btn-primary menuButtonbulk">Waiting for Updater</button>';
    }else{
        echo '<form id="deviceinfo" action="index.php" method="post" onsubmit="return confirmscreen()">' .
        '<button name="deviceinfo" type="submit" class="btn btn-primary menuButtonbulk">Build Info</button>' .
        '</form>';
        if(isset($_POST['deviceinfo'])){
            echo $res=shell_exec('scripts/deviceinfo.sh > /dev/null 2>&1 &') .
            '<script>' .
            'window.location.reload();' .
            '</script>';
            
        }
    }
    }
	
}

function tempbutton() {
    require("config.php");
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    //Check Connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }else{
    $lastseen = " SELECT * FROM Updater WHERE ID = '1'; ";
    $res = $conn->query($lastseen);
    $conn->close();
    while($rows=$res->fetch_assoc()){
        $status = $rows['STATUS'];
    }
    if($status == 1){
        echo '<button class="btn btn-primary menuButtonbulk">Waiting for Updater</button>';
    }elseif($status == 2){
        echo '<button class="btn btn-primary menuButtonbulk">Waiting for Job</button>';
    }else{
        echo '<form id="temp" action="index.php" method ="post" onsubmit="return confirmscreen()">' .
            '<button name="temp" type="submit" class="btn btn-primary menuButtonbulk">Recollect Temps</button>' .
            '</form>';
        if(isset($_POST['temp'])){
            echo $res=shell_exec('scripts/tempcheck.sh > /dev/null 2>&1 &') .
            '<script>' .
            'window.location.reload();' .
            '</script>';
            
        }
    }
  }
}

function rebootbutton() {
    require("config.php");
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    //Check Connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }else{
    $lastseen = " SELECT * FROM Updater WHERE ID = '1'; ";
    $res = $conn->query($lastseen);
    $conn->close();
    while($rows=$res->fetch_assoc()){
        $status = $rows['STATUS'];
    }
        if($status == 1){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Updater</button>';
        }elseif($status == 2){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Job</button>';
        }else{
	echo 
	'<form id="reboot" action="index.php" method ="post" onsubmit="return confirmscreen()">' . 
		'<button name="reboot" type="submit" class="btn btn-primary menuButtonbulk">Reboot ALL</button>' .
	'</form>';
	if(isset($_POST['reboot'])){
		echo $res=shell_exec('scripts/reboot.sh > /dev/null 2>&1 &').
        '<script>' .
        'window.location.reload();' .
        '</script>';
	}
   }
  }
}

function vercheck() {
    require("config.php");
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    //Check Connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }else{
    
    $lastseen = " SELECT * FROM Updater WHERE ID = '1'; ";
    $res = $conn->query($lastseen);
    $conn->close();
    while($rows=$res->fetch_assoc()){
        $status = $rows['STATUS'];
    }
        if($status == 1){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Updater</button>';
        }elseif($status == 2){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Job</button>';
        }else{
        echo
        '<form id="vercheck" action="index.php" method ="post" onsubmit="return confirmscreen()">' .
                '<button name="vercheck" type="submit" class="btn btn-primary menuButtonbulk">Recollect Versions</button>' .
        '</form>';
        if(isset($_POST['vercheck'])){
                echo $res=shell_exec('scripts/vercheck.sh > /dev/null 2>&1 &').
            '<script>' .
            'window.location.reload();' .
            '</script>';
    }
   }
  }
}

function allscreenshot() {
    require("config.php");
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    //Check Connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }else{
    
    $lastseen = " SELECT * FROM Updater WHERE ID = '1'; ";
    $res = $conn->query($lastseen);
    $conn->close();
    while($rows=$res->fetch_assoc()){
        $status = $rows['STATUS'];
    }
        if($status == 1){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Updater</button>';
        }elseif($status == 2){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Job</button>';
        }else{
        echo
        '<form id="allscreenshot" action="index.php" method ="post" onsubmit="return confirmscreen()">' .
                '<button name="allscreenshot" type="submit" class="btn btn-primary menuButtonbulk">Recollect Screenshots</button>' .
        '</form>';
        if(isset($_POST['allscreenshot'])){
                echo $res=shell_exec('scripts/allscreenshot.sh > /dev/null 2>&1 &').
            '<script>' .
            'window.location.reload();' .
            '</script>';
    }
   }
  }
}

function anvercheck() {
    require("config.php");
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    //Check Connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }else{
    
    $lastseen = " SELECT * FROM Updater WHERE ID = '1'; ";
    $res = $conn->query($lastseen);
    $conn->close();
    while($rows=$res->fetch_assoc()){
        $status = $rows['STATUS'];
    }
        if($status == 1){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Updater</button>';
        }elseif($status == 2){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Job</button>';
        }else{
        echo
        '<form id="anvercheck" action="index.php" method ="post" onsubmit="return confirmscreen()">' .
                '<button name="anvercheck" type="submit" class="btn btn-primary menuButtonbulk">Recollect Android Version</button>' .
        '</form>';
        if(isset($_POST['anvercheck'])){
                echo $res=shell_exec('scripts/anvercheck.sh > /dev/null 2>&1 &').
            '<script>' .
            'window.location.reload();' .
            '</script>';
    }
   }
  }
}

function upatlas() {
    require("config.php");
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    //Check Connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }else{
    
    $lastseen = " SELECT * FROM Updater WHERE ID = '1'; ";
    $res = $conn->query($lastseen);
    $conn->close();
    while($rows=$res->fetch_assoc()){
        $status = $rows['STATUS'];
    }
        if($status == 1){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Updater</button>';
        }elseif($status == 2){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Job</button>';
        }else{
	echo 
	'<form id="upatlas" action="index.php" method ="post" onsubmit="return confirmscreen()">' . 
		'<button name="upatlas" type="submit" class="btn btn-primary menuButtonbulk">Update Atlas ALL</button>' .
	'</form>';
	if(isset($_POST['upatlas'])){
		echo $res=shell_exec('scripts/upat.sh > /dev/null 2>&1 &').
        '<script>' .
        'window.location.reload();' .
        '</script>';
	}
   }
  }
}
    
function startbutton() {
    require("config.php");
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    //Check Connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }else{
    
    $lastseen = " SELECT * FROM Updater WHERE ID = '1'; ";
    $res = $conn->query($lastseen);
    $conn->close();
    while($rows=$res->fetch_assoc()){
        $status = $rows['STATUS'];
    }
        if($status == 1){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Updater</button>';
        }elseif($status == 2){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Job</button>';
        }else{
	echo 
	'<form id="start" action="index.php" method ="post" onsubmit="return confirmscreen()">' . 
		'<button name="start" type="submit" class="btn btn-primary menuButtonbulk">Start Scanning ALL</button>' .
	'</form>';
	if(isset($_POST['start'])){
		echo $res=shell_exec('scripts/start.sh > /dev/null 2>&1 &').
        '<script>' .
        'window.location.reload();' .
        '</script>';
	}
   }
  }
}

function stopbutton() {
    require("config.php");
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    //Check Connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }else{
    
    $lastseen = " SELECT * FROM Updater WHERE ID = '1'; ";
    $res = $conn->query($lastseen);
    $conn->close();
    while($rows=$res->fetch_assoc()){
        $status = $rows['STATUS'];
    }
        if($status == 1){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Updater</button>';
        }elseif($status == 2){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Job</button>';
        }else{
	echo 
	'<form id="stop" action="index.php" method ="post" onsubmit="return confirmscreen()">' . 
		'<button name="stop" type="submit" class="btn btn-primary menuButtonbulk">Stop Scanning ALL</button>' .
	'</form>';
	if(isset($_POST['stop'])){
		echo $res=shell_exec('scripts/stop.sh > /dev/null 2>&1 &').
        '<script>' .
        'window.location.reload();' .
        '</script>';
	}
   }
  }
}
    
function restartbutton() {
    require("config.php");
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    //Check Connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }else{
    
    $lastseen = " SELECT * FROM Updater WHERE ID = '1'; ";
    $res = $conn->query($lastseen);
    $conn->close();
    while($rows=$res->fetch_assoc()){
        $status = $rows['STATUS'];
    }
        if($status == 1){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Updater</button>';
        }elseif($status == 2){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Job</button>';
        }else{
    echo
    '<form id="restart" action="index.php" method ="post" onsubmit="return confirmscreen()">' .
        '<button name="restart" type="submit" class="btn btn-primary menuButtonbulk">Restart Scanning ALL</button>' .
    '</form>';
    if(isset($_POST['stop'])){
        echo $res=shell_exec('scripts/stop.sh > /dev/null 2>&1 &');
        echo $res=shell_exec('scripts/start.sh > /dev/null 2>&1 &') .
        '<script>' .
        'window.location.reload();' .
        '</script>';
    }
   }
  }
}
    
function uppogo() {
    require("config.php");
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    //Check Connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }else{
    
    $lastseen = " SELECT * FROM Updater WHERE ID = '1'; ";
    $res = $conn->query($lastseen);
    $conn->close();
    while($rows=$res->fetch_assoc()){
        $status = $rows['STATUS'];
    }
        if($status == 1){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Updater</button>';
        }elseif($status == 2){
            echo '<button class="btn btn-primary menuButtonbulk">Waiting for Job</button>';
        }else{
	echo 
	'<form id="uppogo" action="index.php" method ="post" onsubmit="return confirmscreen()">' .
                '<button name="uppogo" type="submit" class="btn btn-primary menuButtonbulk">Update Pokemon ALL</button>' .
        '</form>';	
	if(isset($_POST['uppogo'])){
		echo $res=shell_exec('scripts/uppogo.sh > /dev/null 2>&1 &').
        '<script>' .
        'window.location.reload();' .
        '</script>';
	}
   }
  }
}
    
function serverControls() {
    require("config.php");
    $urlparse = $_GET["control"];
    
    echo '<div class="cssContainer"><center>';
    
    if($urlparse == "resetdb"){
        $controlname = "Reset Database";
        echo '<h4 style="color:#fff;">' . $controlname . '</h4></br>' .
             '<form id="resetDB" action="index.php" method ="post" onsubmit="return confirmscreen()">' .
               '<button name="resetDB" type="submit" class="btn btn-primary menuButton">Reset DB</button>' .
             '</form>';
        if(isset($_POST['resetDB'])){
            echo $res=shell_exec('scripts/resetdb.sh > /dev/null 2>&1 &');
            sleep(5);
            echo  '<script>' .
            'window.location.reload();' .
            '</script>';
        }
    }
    
    elseif($urlparse == "reboot"){
        $controlname = "Reboot Server";
        echo '<h4 style="color:#fff;">' . $controlname . '</h4></br>' .
                '<form id="rebootServer" action="rebootserver.php" method ="post" onsubmit="return confirmscreen()">' .
                '<button name="rebootServer" type="submit" class="btn btn-primary menuButton">Reboot Server</button>' .
                '</form>';
                    if(isset($_POST['rebootServer'])){
                        echo $res=shell_exec('scripts/rebootserver.sh > /dev/null 2>&1 &');
                    }
    }
    
    elseif($urlparse == "killadb"){
        $controlname = "Kill ADB";
        echo '<h4 style="color:#fff;">' . $controlname . '</h4></br>' .
             '<form id="killADB" action="killadb.php" method ="post" onsubmit="return confirmscreen()">' .
               '<button name="killADB" type="submit" class="btn btn-primary menuButton">Kill ADB</button>' .
             '</form>';
            if(isset($_POST['killADB'])){
                echo $res=shell_exec('scripts/killadb.sh > /dev/null 2>&1 &');
            }
    }
    
    elseif($urlparse == "updateapps"){
        $controlname = "Update Apps";
        require("updatelinks.php");
        echo '<h4 style="color:#fff;">' . $controlname . '</h4></br>' .
             '<form id="UpdatePokemonGo" method ="post">' .
               '<button name="UpdatePokemonGo" type="submit" class="btn btn-primary menuButton">Download Pogo APK</button>' .
             '</form>';
            if(isset($_POST['UpdatePokemonGo'])){
                echo shell_exec('wget -b -O apps/pokemongo.apk '.$pokemongoURL.'#'.$pokemongoURLauth.' ');
            }
    }
    
    
    echo '</center></div>';
         
}
    
function devicelogViewer() {
    require("config.php");
    $urlparsename = $_GET["devicename"];
    $urlparseip = $_GET["localip"];
    
    echo '<div class="cssContainer"><center>';
    
    echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
    
    echo '<p style="color:#fff;">Opening ADB Connection with '.$urlparseip.'<p>';
    echo $res=shell_exec("adb connect $urlparseip:$adbport > /dev/null 2>&1");
    
    $devicelog=shell_exec("adb shell tail /data/local/tmp/atlas.log -n 25");
    
    echo '<h4 style="color:#fff;">Viewing ' . $urlparsename . ' Logs</h4></br>' .
         '<textarea rows="25" style="resize:none;width:80%;height:auto;background-color:#6c757d;" readonly>'.$devicelog.'</textarea></br></br>';
         '</center></div>' .
         '<p style="color:#fff;">Closeing ADB Connection with '.$urlparseip.'<p>';
    echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
    echo '</div>';
}
    
function logViewer() {
    require("config.php");
    $urlparse = $_GET["logtoview"];
    
    if($urlparse == "buildinfo"){
        $filePath = "outputs/buildinfo.log";
        $lines = count(file("$filePath"));
        $uplog = file_get_contents("$filePath");
        $logname = "Build";
    }
    elseif($urlparse == "gettemp"){
        $filePath = "outputs/gettemp.log";
        $lines = count(file("$filePath"));
        $uplog = file_get_contents("$filePath");
        $logname = "Temp";
    }
    elseif($urlparse == "getversion"){
        $filePath = "outputs/getversion.log";
        $lines = count(file("$filePath"));
        $uplog = file_get_contents("$filePath");
        $logname = "Version";
    }
    elseif($urlparse == "screenshot"){
        $filePath = "outputs/screenshot.log";
        $lines = count(file("$filePath"));
        $uplog = file_get_contents("$filePath");
        $logname = "Screenshot";
    }
    elseif($urlparse == "updatepogo"){
        $filePath = "outputs/update-pogo.log";
        $lines = count(file("$filePath"));
        $uplog = file_get_contents("$filePath");
        $logname = "Update Pogo";
    }
    elseif($urlparse == "updateatlas"){
        $filePath = "outputs/update-atlas.log";
        $lines = count(file("$filePath"));
        $uplog = file_get_contents("$filePath");
        $logname = "Update Atlas";
    }
    elseif($urlparse == "stop"){
        $filePath = "outputs/stop.log";
        $lines = count(file("$filePath"));
        $uplog = file_get_contents("$filePath");
        $logname = "Stop";
    }
    elseif($urlparse == "start"){
        $filePath = "outputs/start.log";
        $lines = count(file("$filePath"));
        $uplog = file_get_contents("$filePath");
        $logname = "Start";
    }
    elseif($urlparse == "updater"){
        $filePath = "outputs/updater.log";
        $lines = count(file("$filePath"));
        $uplog = file_get_contents("$filePath");
        $logname = "Updater";
    }
         
    echo '<div class="cssContainer"><center>' .
    
         '<h4 style="color:#fff;">Viewing ' . $logname . ' Logs</h4></br>' .
    
         '<textarea rows="'.$lines.'" style="resize:none;width:80%;height:auto;background-color:#6c757d;" readonly>'.$uplog.'</textarea></br></br>';
    
         '</center></div>';
}

function moreToCome() {
    require("config.php");
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    //Check Connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }else{
    
    $lastseen = " SELECT * FROM Updater WHERE ID = '1'; ";
    $res = $conn->query($lastseen);
    $conn->close();
    while($rows=$res->fetch_assoc()){
        $status = $rows['STATUS'];
    }
        if($status == 1){
            echo '<button class="btn btn-primary menuButtonbulk" style="background-color:#aaa;">More Soon ➜</button>';
        }elseif($status == 2){
            echo '<button class="btn btn-primary menuButtonbulk" style="background-color:#aaa;">More Soon ➜</button>';
        }else{
    echo
    '<form>' .
        '<button class="btn btn-primary menuButtonbulk" style="background-color:#aaa;">More Soon ➜</button>' .
    '</form>';
    //if(isset($_POST['NotSetYet'])){
    //    echo $res=shell_exec('scripts/stop.sh');
    //}
}
}
}
    
function totalcount() {
        require("config.php");
        $totalcount = 0;
        $conn = new mysqli($servername, $username, $password, $dbname, $port);

        //Check Connection
        if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
        }else{
        $sqltotal = " SELECT * FROM Devices; ";
        $restotal = $conn->query($sqltotal);
        $totalcount=mysqli_num_rows($restotal);
        $conn->close();
        echo '<h1 style="color:#fff !important;">' .$totalcount. '</h1>';
      }
    }
    
function onlinecount() {
        require("config.php");
        $conn = new mysqli($servername, $username, $password, $dbname, $port);
        //Check Connection
        if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
        }else{
            $sqlonline = " SELECT ATVNAME FROM Devices; ";
            $resonline = $conn->query($sqlonline);
            $conn->close();
            $count = 0;
                while($rows=$resonline->fetch_assoc()){
                    $nameonline = $rows['ATVNAME'];
                    $conn = new mysqli($RDMservername, $RDMusername, $RDMpassword, $RDMdbname, $RDMport);
                    if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                    }else{
                        $sqlonlinerdm = " SELECT * FROM device WHERE uuid='$nameonline' AND last_seen < now() - interval 5 minute; ";
                        $resonlinerdm = $conn->query($sqlonlinerdm);
                        $conn->close();
                        while($rows=$resonlinerdm->fetch_assoc()){
                            $count++;
                        }
                    }
            }
            }
    echo '<h1 style="color:#fff !important;">' .$count. '</h1>';
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
     '<div class="row">';
                while($rows=$result->fetch_assoc()){
                    $id = $rows['ID'];
                    $name = $rows['ATVNAME'];
                    $ipss = $rows['ATVLOCALIP'];
                    if(empty($name)){
                        $name = "N/A";
                    }
                    echo '<div class="column" id ="'.$name.'" style="text-align:center;">' .
                         '<a href="singledevice.php?devicename=' . $name . '" style="color:#fff;text-decoration:none;">' ;
                    $filename = __DIR__ .'/screenshot/' . $ipss . '.png';
                    if(file_exists($filename)){
                        echo '<div class="imageContainer">' .
                             '<img src="screenshot/' . $ipss . '.png" width="100px" height="auto" />' .
                             '</a>' .
                             '</div>';
                             }else{
                                  echo 'No Screenshot Found.';
                             }
                    
                    echo '<h5 style="color:#aaa;">'.$name.'<h5>' .
                         '</a>'.
                         '</div>';
                }
    echo '</div>';
    
}
    
    function SingleDeviceViewer() {
        require("config.php");
        $urlparsename = $_GET["devicename"];
        
        echo '<h3 style="color:#fff;text-align:center;padding-top:25px;">About Device '.$urlparsename.'</h3>' .
             '<div class="cssContainer">' ;
            
        $conn = new mysqli($servername, $username, $password, $dbname, $port);
        // Checking for connections
        if ($conn->connect_error) {
            die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
        }else{
        $sql = " SELECT * FROM Devices WHERE ATVNAME = '$urlparsename'; ";
        $result = $conn->query($sql);
        $conn->close();
        
        while($rows=$result->fetch_assoc()){
            $id = $rows['ID'];
            $name = $rows['ATVNAME'];
            $atvtemp = $rows['ATVTEMP'];
            $localip = $rows['ATVLOCALIP'];
            $atvproxy = $rows['ATVPROXYIP'];
            $atvmac = $rows['ATVMAC'];
            $atvhostname = $rows['ATVHOSTNAME'];
            $atvacc = $rows['ATVACCOUNT'];
            $atvpogover = $rows['ATVPOGOVER'];
            $atvmagver = $rows['ATVMAGVER'];
            $atvatver = $rows['ATVATVER'];
            $anver = $rows['ANDROIDVER'];
            $cputype = $rows['CPUTYPE'];
            $lastupdated = $rows['LASTUPDATED'];
            if(empty($name)){
                $name = "N/A";
            }
            if(empty($atvtemp)){
                $atvtemp = "N/A";
            }
            if(empty($atvproxy) || $atvproxy == ":" || $atvproxy == ":0"){
                $atvproxy = "N/A";
            }
            if(empty($atvmac)){
                $atvmac = "N/A";
            }
            if(empty($atvhostname)){
                $atvhostname = "N/A";
            }
            if(empty($atvpogover)){
                $atvpogover = "N/A";
            }
            if(empty($atvmagver)){
                $atvmagver = "N/A";
            }
            if(empty($atvatver)){
                $atvatver = "N/A";
            }
            if(empty($anver)){
               $anver = "N/A";
                        }
            if(empty($cputype)){
                $cputype = "N/A";
                        }
            if($atvtemp >= 85){
                $tempcolor = '#950000';
                $tempsize = '900';
            }elseif($atvtemp >= 80){
                $tempcolor = '#950000';
                $tempsize = '600';
            }elseif($atvtemp >= 75){
                $tempcolor = 'orange';
                $tempsize = '400';
            }else{
                $tempcolor = '#00e06d';
                $tempsize = '400';
            }
            
            echo '<div class="row">' .
                 '<div class="col-md-3 text-center buttonColumn" style="padding: 25px 0;margin: 0px 0px 0px 5%">' .
                        '<img src="screenshot/'.$localip.'.png" class="singlesviewss" style="height:500px;width:auto;border: 1px solid #aaa"></br></br>';
            $conn = new mysqli($servername, $username, $password, $dbname, $port);

            //Check Connection
            if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
            }else{
            
            $lastseen = " SELECT * FROM Updater WHERE ID = '1'; ";
            $res = $conn->query($lastseen);
            $conn->close();
            while($rows=$res->fetch_assoc()){
                $status = $rows['STATUS'];
            }
                if($status == 1){
                    echo '<h3 style="color:#fff;">Waiting for Updater</h3>';
                }elseif($status == 2){
                    echo '<h3style="color:#fff;">Waiting for Job</h3>';
                }else{
                echo
                '<div class="tab">
                    <button class="tablink tablinks-' . $name . '" onclick="openTab(event, \'tabGeneral-' . $name .'\', \'' . $name . '\')">General</button>
                    <button class="tablink tablinks-' . $name . '" onclick="openTab(event, \'tabAtlas-' . $name .'\', \'' . $name . '\')">Atlas</button>
                    <button class="tablink tablinks-' . $name . '" onclick="openTab(event, \'tabAPKs-' . $name .'\', \'' . $name . '\')">APKs</button>
                    <button class="tablink tablinks-' . $name . '" onclick="openTab(event, \'tabMisc-' . $name .'\', \'' . $name . '\')">Misc</button>
                </div>';
            }
            }
                
                    // Reboot Device
                    
                    
                    //General TAB
                       echo '<div id="tabGeneral-' . $name .'" class="tabcontent tabcontent-' . $name .'">' .
                            '<form class="d-inline" id="reboot-' . $name . '" action="singledevice.php?devicename=' . $name . '" method ="post" onsubmit="return confirmsingle()">' .
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
                            '<form class="d-inline" id="logfile-' . $name . '" action="singledevice.php?devicename='.$name.'" method ="post" align="center">' .
                                '<button name="logfile-' . $name . '" type="submit" class="btn btn-warning controlButton">Pull Logfile</button>' .
                            '</form>';
                            if(isset($_POST["logfile-$name"])){
                                echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                                echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                                echo $res=shell_exec("adb pull /data/local/tmp/atlas.log deviceLogs/$name.log > /dev/null 2>&1");
                                echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                            }
            
                            echo
                            '<form class="d-inline" id="logfile-' . $name . '" action="/devicelogviewer.php?devicename='.$name.'&localip='.$localip.'" method ="post" align="center">' .
                                '<button name="viewlogs" type="submit" class="btn btn-warning controlButton">Watch Logs</button>' .
                            '</form>';
                            
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
                            '<form class="d-inline" id="scrshot-' . $name . '" action="singledevice.php?devicename='.$name.' method ="post" align="center">' .
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
                


                    echo '</div>' .
                            
                    //Atlas TAB
                         '<div id="tabAtlas-' . $name .'" class="tabcontent tabcontent-' . $name .'">' .
                        
                        // Start Atlas
                            '<form class="d-inline" id="start-' . $name . '" action="singledevice.php?devicename='.$name.'" method ="post" onsubmit="return confirmsingle()">' .
                            '<button name="start-' . $name . '" type="submit" class="btn btn-success controlButton">Start Atlas</button>' .
                            '</form>';
                        if(isset($_POST["start-$name"])){
                            echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                            echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                            echo $res=shell_exec('adb shell "am startservice com.pokemod.atlas/com.pokemod.atlas.services.MappingService" > /dev/null 2>&1');
                            echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        }
                        // Stop Pogo & Atlas
                        echo '<form class="d-inline" id="stop-' . $name . '" action="singledevice.php?devicename='.$name.'" method ="post" onsubmit="return confirmsingle()">' .
                            '<button name="stop-' . $name . '" type="submit" class="btn btn-danger controlButton">Stop Atlas</button>' .
                        '</form>';
                        if(isset($_POST["stop-$name"])){
                            echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                            echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                            echo $res=shell_exec('adb shell "su -c am force-stop com.nianticlabs.pokemongo & am force-stop com.pokemod.atlas" > /dev/null 2>&1');
                            echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        }
                        // Update Atlas Config
                        echo '<form class="d-inline" id="config-atlas-' . $name . '" action="singledevice.php?devicename='.$name.'" method ="post" onsubmit="return confirmsingle()">' .
                            '<button name="update-atlas-' . $name . '" type="submit" class="btn btn-warning controlButton">Push Atlas Config</button>' .
                        '</form>';
                        if(isset($_POST["config-atlas-$name"])){
                            echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                            echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                            echo $res=shell_exec("adb push apps/$name_atlas_config.json /data/local/tmp/atlas_config.json > /dev/null 2>&1");
                            echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        }
                    echo '</div>' .
                    
                    // APKs TAB
                         '<div id="tabAPKs-' . $name .'" class="tabcontent tabcontent-' . $name .'">' .

                        // Update PoGo APK
                         '<form class="d-inline" id="update-pogo-' . $name . '" action="singledevice.php?devicename='.$name.'" method ="post" onsubmit="return confirmsingle()">' .
                            '<button name="update-pogo-' . $name . '" type="submit" class="btn btn-primary controlButton">Push PoGo APK</button>' .
                         '</form>';
                        if(isset($_POST["update-pogo-$name"])){
                            echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                            echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                            echo $res=shell_exec('adb install -r apps/pokemongo.apk > /dev/null 2>&1');
                            echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        }

                        // Update Atlas APK
                        echo '<form class="d-inline" id="update-atlas-' . $name . '" action="singledevice.php?devicename='.$name.'" method ="post" onsubmit="return confirmsingle()">' .
                            '<button name="update-atlas-' . $name . '" type="submit" class="btn btn-primary controlButton">Push Atlas APK</button>' .
                        '</form>';
                        if(isset($_POST["update-atlas-$name"])){
                            echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                            echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                            echo $res=shell_exec('adb install -r apps/atlas.apk > /dev/null 2>&1');
                            echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                        }
                    echo '</div>'.// End of Device Options Tablerow
                    
                    // Misc TAB
                            '<div id="tabMisc-' . $name .'" class="tabcontent tabcontent-' . $name .'">' .

                        // Get PoGo Version
                            '<form class="d-inline" id="version-pogo-' . $name . '" action="singledevice.php?devicename='.$name.'" method ="post">' .
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
                            echo '<form class="d-inline" id="version-atlas-' . $name . '" action="singledevice.php?devicename='.$name.'" method ="post">' .
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
                            echo '<form class="d-inline" id="version-android-' . $name . '" action="singledevice.php?devicename='.$name.'" method ="post">' .
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
                        
                            // get CPU TYPE
                            echo '<form class="d-inline" id="cputype-' . $name . '" action="singledevice.php?devicename='.$name.'" method ="post">' .
                                '<button name="cputype-' . $name . '" type="submit" class="btn btn-primary controlButton">Check CPU type</button>' .
                            '</form>';
                            if(isset($_POST["cputype-$name"])){
                                echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                                echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                                $cputype= shell_exec('adb shell getprop ro.product.cpu.abi');
                                $conn = new mysqli($servername, $username, $password, $dbname, $port);
                                //Checking for connections
                                if ($conn->connect_error) {
                                        die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
                                }else {
                                        $sql = " UPDATE Devices SET CPUTYPE = '$cputype' WHERE ID = $id; ";
                                        $conn->query($sql);
                                        echo "Checking CPU";
                                        $conn->close();
                                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1'); ?>
                                        <script>
                                        window.location.reload();
                                        </script>
                                <?php
                                }
                            }
                            
                            // get temp TYPE
                            echo '<form class="d-inline" id="temp-' . $name . '" action="singledevice.php?devicename='.$name.'" method ="post">' .
                                '<button name="temp-' . $name . '" type="submit" class="btn btn-primary controlButton">Recheck Temp</button>' .
                            '</form>';
                            if(isset($_POST["temp-$name"])){
                                echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                                echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                                $temp = shell_exec("adb shell cat /sys/class/thermal/thermal_zone0/temp | awk '{print substr($0, 1, length($0)-3)}'");
                                $conn = new mysqli($servername, $username, $password, $dbname, $port);
                                //Checking for connections
                                if ($conn->connect_error) {
                                        die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
                                }else {
                                        $sql = " UPDATE Devices SET ATVTEMP = '$temp' WHERE ID = $id; ";
                                        $conn->query($sql);
                                        echo "Checking Device temp";
                                        $conn->close();
                                        echo $res=shell_exec('adb kill-server > /dev/null 2>&1'); ?>
                                        <script>
                                        window.location.reload();
                                        </script>
                                <?php
                                }
                            }
                                    
                            
                            
                    
                        // Push eMagisk.zip to Device
                            echo '<form class="d-inline" id="push-emagisk-' . $name . '" action="singledevice.php?devicename='.$name.'" method ="post" onsubmit="return confirmsingle()">' .
                                '<button name="push-emagisk-' . $name . '" type="submit" class="btn btn-primary controlButton">Push eMagisk.zip</button>' .
                            '</form>';
                            if(isset($_POST["push-emagisk-$name"])){
                                echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                                echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                                echo $res=shell_exec('adb push apps/eMagisk.zip /sdcard > /dev/null 2>&1');
                                echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                            }
                            
                        // Push eMagisk Config to Device
                            echo '<form class="d-inline" id="config-emagisk-' . $name . '" action="singledevice.php?devicename='.$name.'" method ="post" onsubmit="return confirmsingle()">' .
                                '<button name="config-emagisk-' . $name . '" type="submit" class="btn btn-primary controlButton">Push eMagisk Config</button>' .
                            '</form>';
                            if(isset($_POST["config-emagisk-$name"])){
                            echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                            echo $res=shell_exec("adb connect $localip:$adbport > /dev/null 2>&1");
                            echo $res=shell_exec('adb push apps/emagisk.congig /data/local/tmp > /dev/null 2>&1');
                            echo $res=shell_exec('adb kill-server > /dev/null 2>&1');
                            }
                            
                            
                    echo '</div>';// End of Device Options Tablerow
                    
                ?>

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
                    
                echo    '<div class="modal fade" id="modalLogFile" tabindex="-1" role="dialog">
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
                    
                    
                echo  '</div>' .
                    '<div class="col-md-8 text-center buttonColumn" style="padding: 25px 0;">' .
                    '<div class="row">' .
                        '<div class="col-md text-center" style="color:#fff;">' .
                        '<table style="width:80%;margin: 0px 0px 0px 10%;" class="table table-dark table-striped">' .
                            '<tr>' .
                            '<h5 style="text-align:left;">NETWORKING</h5>' .
                            '</tr>' .
                            '<tr>' .
                            '<th style="text-align:right;padding-right:20px;width:50%;">Local IP:</th>' .
                            '<td>'.$localip.'</td>' .
                            '</tr>' .
                            '<tr>' .
                            '<th style="text-align:right;padding-right:20px;width:50%;">Mac:</th>' .
                            '<td>'.$atvmac.'</td>' .
                            '</tr>' .
                            '<tr>' .
                            '<th style="text-align:right;padding-right:20px;width:50%;">Proxy:</th>' .
                            '<td>'.$atvproxy.'</td>' .
                            '</tr>' .
                            '<tr>' .
                            '<th style="text-align:right;padding-right:20px;width:50%;">Hostname:</th>' .
                            '<td>'.$atvhostname.'</td>' .
                            '</tr>' .
                            '</table></br>' .
                            '<table style="width:80%;margin: 0px 0px 0px 10%;" class="table table-dark table-striped">' .
                            '<tr>' .
                            '<h5 style="text-align:left;">INFORMATION</h5>' .
                            '</tr>' .
                            '<tr>' .
                            '<th style="text-align:right;padding-right:20px;width:50%;">Android Version:</th>' .
                            '<td>'.$anver.'</td>' .
                            '</tr>' .
                            '<tr>' .
                            '<th style="text-align:right;padding-right:20px;width:50%;">CPU:</th>' .
                            '<td>'.$cputype.'</td>' .
                            '</tr>' .
                            '<tr>' .
                            '<th style="text-align:right;padding-right:20px;width:50%;">Temp:</th>' .
                            '<td style="color:'.$tempcolor.';font-weight:'.$tempsize.'">'.$atvtemp.'°C</td>' .
                            '</tr>' .
                            '</table></br>' .
                            '<table style="width:80%;margin: 0px 0px 0px 10%;" class="table table-dark table-striped">' .
                            '<tr>' .
                            '<h5 style="text-align:left;">APP VERSIONS</h5>' .
                            '</tr>' .
                            '<tr>' .
                            '<th style="text-align:right;padding-right:20px;width:50%;">Pokemon Version:</th>' .
                            '<td>'.$atvpogover.'</td>' .
                            '</tr>' .
                            '<tr>' .
                            '<th style="text-align:right;padding-right:20px;width:50%;">Atlas Version:</th>' .
                            '<td>'.$atvatver.'</td>' .
                            '</tr>' .
                            '<tr>' .
                            '<th style="text-align:right;padding-right:20px;width:50%;">Magisk Version:</th>' .
                            '<td>'.$atvmagver.'</td>' .
                            '</tr>' .
                            '</table></br>' .
                            '<table style="width:80%;margin: 0px 0px 0px 10%;" class="table table-dark table-striped">' .
                            '<tr>' .
                            '<h5 style="text-align:left;">CURRENT VIEW</h5>' .
                            '</tr>' .
                            '<tr>' .
                            '<th style="text-align:right;padding-right:20px;width:50%;">Active Account:</th>' .
                            '<td>'.$atvacc.'</td>' .
                            '</tr>' .
                            '<tr>' .
                            '<th style="text-align:right;padding-right:20px;width:50%;">' .
                            "Last Seen:" .
                            '</th>' .
                            '<td>';
                            $conn = new mysqli($RDMservername, $RDMusername, $RDMpassword, $RDMdbname, $RDMport);
                            // Checking for connections
                            if ($conn->connect_error) {
                                die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
                            }else{
                                $lastseen = " SELECT last_seen FROM device WHERE uuid = '$name'; ";
                                $res = $conn->query($lastseen);
                                $conn->close();
                                $lastSeenResult = 0;
                                while($rows=$res->fetch_assoc()){
                                    $lastseentime = $rows['last_seen'];
                                    if(!empty($lastseentime)) {
                                        $lastSeenResult = 1;
                                    }
                                }
                                if($lastSeenResult === 1){
                                    $timeDiff = (time() - $lastseentime) +1;
                                    //Convert to seconds, minutes, hours
                                    $seconds = $timeDiff % 60;
                                    $minutes = floor(($timeDiff % 3600) / 60);
                                    $hours = floor($timeDiff / 3600);
                                    if($hours > 0) echo "$hours" . "h, ";
                                    if($minutes > 0) echo "$minutes" . "m, ";
                                    echo "$seconds" . "s";
                                } else{
                                    echo "No Last Seen Found";
                                }
                            }
                            echo '</td></tr>' .
            
                            '<tr><th style="text-align:right;padding-right:20px;width:50%;">' .
                            "Updated" .
                            '</th>' .
                            '<td>' ;
                                $timecon = strtotime($lastupdated);
                                $timeDiff = (time() - $timecon) +1;
                                //Convert to seconds, minutes, hours
                                $seconds = $timeDiff % 60;
                                $minutes = floor(($timeDiff % 3600) / 60);
                                $hours = floor($timeDiff / 3600);
                                if($hours > 0) echo "$hours" . "h, ";
                                if($minutes > 0) echo "$minutes" . "m, ";
                                echo "$seconds" . "s" .
                            '</td></tr>' .
            
                        '</table>' .
                        '</div>' .
                    '</div>' .
                '</div>' .
            '</div>';
            
        }
        echo '</div>';
        }
    }
    
    function scanner(){
    include("config.php");
    
    echo '<div class="cssContainer">' .
         '<div style="color:#fff;"><center>';
        
         $filePath = "scripts/ips";
         $lines = count(file("$filePath"));
         $ips = file_get_contents("$filePath");
         echo '<h4>Existing IPS file</h4>' .
              '<textarea rows="'.$lines.'" style="resize:none;width:80%;height:auto;text-align:center;background-color:#6c757d;" readonly>'.$ips.'</textarea></br></br>';
         
         $newfilePath = "scripts/new_ips";
         $newips = file_get_contents("$newfilePath");
         if(!empty($newips)){
             $newlines = count(file("$newfilePath"));
             echo '</br><h4>New Devices Found</h4>' .
                  '<form class="d-inline" id="newDevice" method ="post">' .
                  '<textarea rows="'.$newlines.'" style="resize:none;width:80%;text-align:center;background-color:#6c757d;" readonly>';
             echo "$newips";
             echo '</textarea></br>' .
                  '</form>';
         }
        
    echo 'Scan for new network devices?<br>'.
         '<form method="post" >' .
         '<input type="submit" name="scanner" value="Scan" />' .
         '</form></br>';
        
     if(!empty($_POST['scanner'])) {
          
          //list of port numbers to scan
          $existcount = "0";
          $foundcount = "0";
          $startrange = "2";
          $countrange = "$startrange";
          $endrange = "255";
          $port = "5555";
          
          $checknewips = file_get_contents("scripts/new_ips");
          if(!empty($checknewips)){
              echo $res=shell_exec('rm scripts/new_ips > /dev/null 2>&1 &');
          }
         
          while($countrange <= $endrange){
              $activeip = "$lanip.$countrange";
              $conn = new mysqli($servername, $username, $password, $dbname, $port);
              // Checking for connections
              if ($conn->connect_error) {
                  die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
              }
              $sql = " SELECT * FROM Devices WHERE ATVLOCALIP = '$activeip'; ";
              $result = $conn->query($sql);
              $conn->close();
              while($rows=$result->fetch_assoc()){
                      $atvlocalip = $rows['ATVLOCALIP'];
              }
              if($activeip != $atvlocalip){
                  $fp = fsockopen("$activeip", "$port", $errno, $errstr, 1);
                  if ($fp) {
                      $file = fopen("scripts/new_ips","a");
                      fwrite($file,"$countrange\n");
                      fclose($file);
                      fclose($fp);
                      
                      $foundcount++;
                  }
              }else{
                  $existcount++;
              }
          $countrange++;
          }
         
          if($foundcount == "0"){
          $final = $foundcount + $existcount;
          echo '</br>Range Checked: ' . $lanip . '.' . $startrange . ' - ' . $endrange . '</br>' .
               'Total New: ' . $foundcount . '</br>' .
               'Total Existing: ' . $existcount . '</br>' .
               'Total Found: ' . $final . '</br>' .
               'Total Checked: ' . $countrange . '</br>' .
               'No new devices found';
               sleep(5);
              ?>
              <script>
              window.location.reload();
              </script>
              <?php
          } else {
          $final = $foundcount + $existcount;
          echo '</br>Range Checked: ' . $lanip . '.' . $startrange . '/' . $endrange . '</br>' .
               'Total New: ' . $foundcount . '</br>' .
               'Total Existing: ' . $existcount . '</br>' .
               'Total Found: ' . $final . '</br>' .
               'Total Checked: ' . $countrange . '</br>' ;
               sleep(5);
              ?>
              <script>
              window.location.reload();
              </script>
              <?php
          }
     }
                  
     
        
    echo '</div></div>';
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
     '<textarea row=2 style="resize:none;width:80%;text-align:center;background-color:#6c757d;" readonly>'.$atconfig.'</textarea><br>' .
     '<a href="/apps/atlas_config.json" download><button name="export" type="submit" class="btn btn-primary">Export .Json</button></a>' .
     
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
        '<input type="text" id="authBearer" name="authBearer" placeholder="'.$authBearer.'" value="'.$authBearer.'" style="background-color:#6c757d;"><br>' .
        
        '<label for="deviceAuthToken">deviceAuthToken</label><br>' .
        '<input type="text" id="deviceAuthToken" name="deviceAuthToken" placeholder="'.$deviceAuthToken.'" value="'.$deviceAuthToken.'" style="background-color:#6c757d;" required><br>' .
    
        '<label for="deviceName">deviceName</label><br>' .
        '<input type="text" id="deviceName" name="deviceName" placeholder="'.$deviceName.'" value="'.$deviceName.'" style="background-color:#6c757d;"><br>' .
        
        '<label for="email">email</label><br>' .
        '<input type="text" id="email" name="email" placeholder="'.$email.'" value="'.$email.'" style="background-color:#6c757d;" required><br>' .
        
        '<label for="rdmUrl">rdmUrl</label><br>' .
        '<input type="text" id="rdmUrl" name="rdmUrl" placeholder="'.$rdmUrl.'" value="'.$rdmUrl.'" style="background-color:#6c757d;" required><br><br>' .

        '<label for="runOnBoot">Run On Boot:</label><br>' .
        '<select id="runOnBoot" name="runOnBoot" style="background-color:#6c757d;" required>' .
        '<option value="" disabled selected hidden>--</option>' .
        '<option value="true">true</option>' .
        '<option value="false">false</option>' .
        '</select><br><br>' .
    
    '<button name="atconfcreator" type="submit" class="btn btn-primary">Save</button><br><br>' .
    'Or Generate for all<br>(Set everything above but the name!)<br>' .
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
                    

                    function editemconf(){
                    include("config.php");
                    echo '<div class="cssContainer">' .
                         '<div style="color:#fff;"><center>';
                        // Select Json File
                        $emconfig = file_get_contents("apps/emagisk.config");

                    if(empty($emconfig)){
                    echo 'No eMagisk Config File Found, Would you like to make one?<br>' .
                         '<form class="d-inline" id="emconfcreate" action="emagiskeditor.php" method ="post">' .
                         '<button name="emconfcreate" type="submit" class="btn btn-primary">Make eMagisk config</button>' .
                         '</form>';
                        if(isset($_POST['emconfcreate'])){
                                    echo $res=shell_exec('cp apps/emagisk.config.example apps/emagisk.config> /dev/null 2>&1 &');
                            ?>
                            <script>
                            window.location.reload();
                            </script>
                            <?php
                        }
                    }else{
                    echo '<h3>Current Config</h3>' .
                         '<textarea rows=3 style="resize:none;width:80%;text-align:center;background-color:#6c757d;" readonly>'.$emconfig.'</textarea><br>' .
                         '<a href="/apps/emagisk.config" download><button name="export" type="submit" class="btn btn-primary">Export .config</button></a>' .
                         
                        '<h4>Edit eMagisk Config</h4>';
                        
                        $fp    = 'apps/emagisk.config';

                                // get the contents of file in array
                                $conents_arr   = file($fp,FILE_IGNORE_NEW_LINES);

                                foreach($conents_arr as $key=>$value)
                                {
                                    $conents_arr[$key]  = rtrim($value, "\r");
                                }

                                $json_contents = json_encode($conents_arr);
                        
                        $array = json_decode($json_contents, true);
                        extract($array);
                        
                        if(empty($rdm_user)){
                           $rdm_user = "";
                        }
                        if(empty($rdm_password)){
                           $rdm_password = "";
                        }
                        if(empty($rdm_backendURL)){
                           $rdm_backendURL = "";
                        }
                        
                        echo '<form id="emconfcreator" method="post">' .
                        
                            '<label for="rdm_user">RDM User</label><br>' .
                            '<input type="text" id="rdm_user" name="rdm_user" placeholder="'.$rdm_user.'" value="'.$rdm_user.'" style="background-color:#6c757d;" required><br>' .
                            
                            '<label for="rdm_password">RDM Password</label><br>' .
                            '<input type="text" id="rdm_password" name="rdm_password" placeholder="'.$rdm_password.'" value="'.$rdm_password.'" style="background-color:#6c757d;" required><br>' .
                        
                            '<label for="rdm_backendURL">RDM Backend URL</label><br>' .
                            '<input type="text" id="rdm_backendURL" name="rdm_backendURL" placeholder="'.$rdm_backendURL.'" value="'.$rdm_backendURL.'" style="background-color:#6c757d;" required><br>' .
                        
                        '<button name="emconfcreator" type="submit" class="btn btn-primary">Save</button><br><br>' .
                        '</form>';
                        
                        if(isset($_POST['emconfcreator'])){
                            $RDMUser = $_POST["rdm_user"];
                            if(empty($RDMUser)){
                                $RDMUser = "";
                            }
                            
                            $RDMPass = $_POST["rdm_password"];
                            if(empty($RDMPass)){
                                $RDMPass = "";
                            }
                            
                            $RDMUrl = $_POST["rdm_backendURL"];
                            if(empty($RDMUrl)){
                                $RDMUrl = "";
                            }
                            
                            $file = fopen("apps/emagisk.config","w");
                            fwrite($file,'rdm_user="'.$RDMUser.'"');
                            fwrite($file, "\n");
                            fwrite($file,'rdm_password="'.$RDMPass.'"');
                            fwrite($file, "\n");
                            fwrite($file,'rdm_backendURL="'.$RDMUrl.'"');
                            fclose($file);
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
