<!DOCTYPE html>
<html>

<?php

require('header.php');

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
        if (confirm("Are you sure you want to reset and rebuild the database?.") == true) {
        return true;
        } else {
        return false;
        }
        }
</script>

</html>
