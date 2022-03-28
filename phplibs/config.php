<?php

/* Database credentials */
getenv('MYSQL_DBHOST') ? $DB_HOST=getenv('MYSQL_DBHOST') : $DB_HOST="localhost"; 
getenv('MYSQL_DBPORT') ? $DB_PORT=getenv('MYSQL_DBPORT') : $DB_PORT="3306";
getenv('MYSQL_DBNAME') ? $DB_NAME=getenv('MYSQL_DBNAME') : $DB_NAME="pastebin";
getenv('MYSQL_DBUSER') ? $DB_USER=getenv('MYSQL_DBUSER') : $DB_USER="root";
getenv('MYSQL_DBPASS') ? $DB_PASS=getenv('MYSQL_DBPASS') : $DB_PASS="";
 
/* Attempt to connect to MySQL database */
$conn = mysqli_connect("$DB_HOST:$DB_PORT", $DB_USER, $DB_PASS, $DB_NAME);
 
// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
} 

?>
