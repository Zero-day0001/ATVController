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
</script>

</html>
