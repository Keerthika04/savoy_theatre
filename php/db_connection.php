<?php  
$dbHost     = "localhost:3308";  
$dbUsername = "root";  
$dbPassword = "";  
$dbName     = "savoy";  
  
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);  
  
if ($db->connect_error) {  
    die("Connection failed: " . $db->connect_error);  
} 
 
?>