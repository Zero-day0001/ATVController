<?php
require('config.php');
require('sections.php');
require('functions.php');
require('footer.php');

function Menu() { 
	
echo '<head>' .
	'<title>ATV Controller</title>' .
	'<meta name="viewport" content="width=device-width, initial-scale=1">' .
	'<link rel="icon" type="image/png" href="/favicon.png">' .
	'<link rel="shortcut icon" href ="favicon.png">' .
	'<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">' .
	'<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">' .
	'<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script> ' .
	'<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>' .
	'<link rel="stylesheet" href="css/style.css">' .
'</head>' .

'<header>'.

'<div class="topnav" id="TopnavMenu">' .
  '<a href="#" class="logo">ATV Controller</a>' .
  '<a href="index.php">Home</a>' .
  '<a href="editor.php">Atlas Config Creator</a>' .
  '<a href="javascript:void(0);" class="icon" onclick="menu()">' .
    '<i class="fa fa-bars"></i>' .
  '</a>' .
'</div>' .

'</header>' . 

'<body>'; 
 } ?>

