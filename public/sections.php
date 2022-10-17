<?php

function SectionOne() { 
echo 
'<div id="buttonSection" class="cssContainer buttonSection">
	<div class="row">
		<div class="col-md-6 text-center buttonColumn">
			<div class="buttonHeaderText lead">Controller Utilities:</div>
			<div class="rowmenu">
                <div class="columnmenu">';
                    tempbutton();
                    pushemag();
                echo '</div>
                <div class="columnmenu">';
                    vercheck();
                    updatemagisk();
               echo '</div>
                <div class="columnmenu">';
                    allscreenshot();
                    moretocome();
               echo '</div>
			</div>
		</div>';

		echo
		'<div class="col-md-6 text-center buttonColumn">
			<div class="buttonHeaderText lead">Device Utilities:</div>
    
                <div class="rowmenu">
                    <div class="columnmenu">';
                        upatlas();
                        uppogo();
                    echo '</div>
                    <div class="columnmenu">';
                        startbutton();
                        stopbutton();
                echo '</div>
                    <div class="columnmenu">';
                        restartbutton();
                        rebootbutton();
                echo '</div>
            </div>
		</div>
	</div>
</div>';
}

function SectionTwo() {
    echo
    '<div id="buttonSection" class="cssContainer buttonSection">' .
        '<div class="row">' .
            '<div class="col-md-6 text-center buttonColumn">' .
                '<div class="buttonHeaderText lead">Total Devices:</div>' .
                '<div class="row">' .
                    '<div class="col-md text-center">';
                        totalcount();
                    echo '</div>' .
                '</div>' .
            '</div>';

            echo
            '<div class="col-md-6 text-center buttonColumn">' .
                '<div class="buttonHeaderText lead">Online Devices:</div>' .
                '<div class="row">' .
                    '<div class="col-md text-center">';
                        onlinecount();
                    echo '</div>' .
                '</div>' .
            '</div>' .
        '</div>' .
    '</div>';
}
    
function SectionThree() {
devicetable();
}

function SectionFour() {
//echo 'Placeholder';
}

?>
