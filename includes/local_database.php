<?php
date_default_timezone_set("Europe/Amsterdam");



$dbHost = "localhost";

$dbUser = "root";

$dbPass = "";

$dbName = "movies";



// Create db connection

$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
?>