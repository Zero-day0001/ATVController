<?php
require('header.php');
    if(!isset($_SESSION['UserID']))
    {
    header("Location: /auth.php?type=login");
        exit;
    }
Menu();

editemconf();

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
        if (confirm("This process could take up to 5 minutes.\n\nThe Page will refresh while data is collected in the background.\n\nYou may refresh the page to view the collected data.") == true) {
        return true;
        } else {
        return false;
        }
        }

function confirmsingle(){
        if (confirm("Are you sure?") == true) {
        return true;
        } else {
        return false;
        }
        }
</script>

