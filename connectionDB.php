<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "blood_recovery"; // Set your DB name

$conn = mysqli_connect($host, $user, $pass, $db);
if(!$conn){
    die("DB Connection failed: " . mysqli_connect_error());
}
else{
    // echo "DB Connected successfully";
}
?>