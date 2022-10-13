<?php
    // DON'T TOUCH ANYTHING BELOW HERE

    $version = "1";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    // Check Connection
    if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
    }else{

    // Create Table Devices
    $create_table = "CREATE TABLE IF NOT EXISTS `Devices`
    (
    `ID` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ATVNAME` VARCHAR(50) NULL UNIQUE,
    `ATVTEMP` VARCHAR(5) NULL,
    `ATVLOCALIP` VARCHAR(50) NULL UNIQUE,
    `ATVPROXYIP` VARCHAR(50) NULL,
    `ATVACCOUNT` VARCHAR(50) NULL,
    `ATVATVER` VARCHAR(15) NULL,
    `ATVPOGOVER` VARCHAR(15) NULL,
    `ANDROIDVER` VARCHAR(15) NULL,
    `CPUTYPE` VARCHAR(25) NULL,
    `LASTUPDATED` TIMESTAMP DEFAULT now() ON UPDATE now()
    );";

    $conn->query($create_table);
    $conn->close();

    }
        
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    // Check Connection
    if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
    }else{

    // Create Table updater
    $create_updater = "CREATE TABLE IF NOT EXISTS `Updater`
    (
    `ID` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `STATUS` INT UNSIGNED NOT NULL DEFAULT '0',
    `LASTCHECK` TIMESTAMP DEFAULT now() ON UPDATE now(),
    `VERSION` INT UNSIGNED NOT NULL DEFAULT '0',
    `BUILT` INT UNSIGNED NOT NULL DEFAULT '0'
    );";

    $conn->query($create_updater);
    $conn->close();
    }
    
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    // Check Connection
    if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
    }else{
    $create_users = "CREATE TABLE IF NOT EXISTS `Users`
    (
     `ID` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
     `USERNAME` VARCHAR(50) NOT NULL UNIQUE,
     `PASSWORD` VARCHAR(255) NOT NULL,
     `SESSION` VARCHAR(50) NULL,
     `CREATED_AT` DATETIME DEFAULT CURRENT_TIMESTAMP
    );";

    $conn->query($create_users);
    $conn->close();
    }
        
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    // Check Connection
    if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
    }else{

    // insert updater options
    $sql = " INSERT IGNORE INTO Updater (ID, STATUS, VERSION, BUILT) VALUES ('1', '0', '$version', '0')";
    $conn->query($sql);
    $conn->close();
    }
        
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    // Checking for connections
    if ($conn->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
    }else{
    $sql = " SELECT * FROM Updater; ";
    $result = $conn->query($sql);
    $conn->close();
        while($rows=$result->fetch_assoc()){
            $built = $rows['BUILT'];
            if($built == 0){
                echo "<center><p style='color:#fff'>BUILDING</p></center>";
                echo $res=shell_exec('scripts/deviceinfo.sh > /dev/null 2>&1 &');
                sleep(5);
                ?>
                <script>
                window.location.reload();
                </script>
                <?php
            }
        }
    }
include("sql/alterdb.php");
?>
