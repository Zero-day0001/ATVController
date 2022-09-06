<?php

function SectionOne() { 
echo 
'<div id="buttonSection" class="cssContainer buttonSection">
	<div class="row">
		<div class="col-md-6 text-center buttonColumn">
			<div class="buttonHeaderText lead">Controller Utilities:</div>
			<div class="row">
				<div class="col-md-4 text-center">';
					deviceinfo();
					tempbutton();
				echo '</div>';
				echo '<div class="col-md-4 text-center">';
					vercheck();
					moretocome();
				echo 
				'</div>';
				echo '<div class="col-md-4 text-center">';
					moreToCome();
					moreToCome();
				echo 
				'</div>
			</div>
		</div>';

		echo
		'<div class="col-md-6 text-center buttonColumn">
			<div class="buttonHeaderText lead">Device Utilities:</div>
			<div class="row">
				<div class="col-md-4 text-center">';
					upatlas();
					uppogo();
				echo '</div>';
				echo '<div class="col-md-4 text-center">';
					startbutton();
					stopbutton();
				echo '</div>';
				echo '<div class="col-md-4 text-center">';
					rebootbutton();
					moreToCome();
				echo 
				'</div>
			</div>
		</div>
	</div>
</div>';
}
function SectionTwo() {
devicetable();
}

function SectionThree() { 
echo '<div class="SectionThree">';
//echo 'Placeholder';
echo '</div>';
}

function SectionFour() {
//echo 'Placeholder';
}

?>
