<?php
date_default_timezone_set("Europe/Amsterdam");



$dbHost = "localhost";

$dbUser = "triest_triestje";

$dbPass = "triestje";

$dbName = "triest_uren";



// Create db connection
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
?>