<?php
    // DON'T TOUCH ANYTHING BELOW HERE
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    // Check Connection
    if (!$conn) {
     die("Connection failed: " . mysqli_connect_error());
    }else{
    //Create Table Devices
        $alter = " SHOW COLUMNS FROM `Devices` LIKE 'ATVMAC'; ";
        $res = $conn->query($alter);
        $check = mysqli_num_rows($res);
        if($check == "0"){
        $conn = new mysqli($servername, $username, $password, $dbname, $port);
            // Check Connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }else{
                //Create Table Devices
                $alter_table = " ALTER TABLE `Devices` ADD `ATVMAC` VARCHAR(25) AFTER `ATVPROXYIP`; ";
                $conn->query($alter_table);
                }
        }
        $conn->close();
    }

?>
