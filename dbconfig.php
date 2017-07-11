<?php
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Karachi');
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300);
/*  ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
 */
$host = "localhost";
$username = "app";
$password = "abcd1234";
$database = "seroafgh";
// create connection 
   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'app');
   define('DB_PASSWORD', 'abcd1234');
   define('DB_DATABASE', 'seroafgh');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);


$con = new mysqli($host, $username, $password, $database); 
 
// check connection 
if($con->connect_error) {
    die("connection failed : " . $con->connect_error);
} else {
    //echo "Successfully Connected";
}
 
?>
