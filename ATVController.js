// Place this file next to a folder named "public"
// "public" should contain your php website. Like Opencart or Wordpress.
var express = require('express');
var php = require("node-php"); 
var path = require("path"); 

var app = express();

app.use("/", php.cgi("public")); 

//Listen port - You can change this if you need too
app.listen(3000);

//Print the server has started
console.log("Server listening!");

//Change minute to edit how often the updater runs
var minute = 3


//DON't TOUCH UNLESS YOU KNOW WHAT YOUR DOING
the_interval = minute * 60 * 1000;
setInterval(function() {
    console.log("Waking up Updater!");
    
    const { exec } = require('child_process');
    var yourscript = exec('./public/scripts/updater.sh',
            (error, stdout, stderr) => {
                console.log(stdout);
                console.log(stderr);
                if (error !== null) {
                    console.log(`exec error: ${error}`);
                }
            });
    
    console.log("Updater going to sleep for " + minute + " Minutes.");
    console.clear();
}, the_interval);

