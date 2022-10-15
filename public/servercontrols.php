<?php
require('header.php');
    if(!isset($_SESSION['UserID']))
    {
    header("Location: /auth.php?type=login");
        exit;
    }
Menu();

serverControls();

Footer();

?>

<script>
function menu() {
  	var x = document.getElementById("TopnavMenu");
  	if (x.className === "topnav") {
    	x.className += " responsive";
  	} else {
    	x.className = "topnav";
	}
	}

function confirmscreen(){
        if (confirm("Are you sure?.") == true) {
        return true;
        } else {
        return false;
        }
        }
</script>

</html>
