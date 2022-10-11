<!DOCTYPE html>
<html>

<?php

require('header.php');

Menu();

devicelogViewer();

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
</script>

</html>
