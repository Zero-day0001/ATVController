// Place this file next to a folder named "public"
// "public" should contain your php website. Like Opencart or Wordpress.
var express = require('express');
var php = require("node-php"); 
var path = require("path"); 

var app = express();

app.use("/", php.cgi("public")); 

app.listen(3000);

console.log("Server listening!"); 
