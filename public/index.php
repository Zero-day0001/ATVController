<!DOCTYPE html>
<html>

<?php

require('header.php');

Menu();

SectionOne();
SectionTwo();
SectionThree();
SectionFour();

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
        if (confirm("This process could take up to 5 minutes.") == true) {
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

</html>
