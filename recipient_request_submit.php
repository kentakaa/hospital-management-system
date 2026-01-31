<?php
session_start();
include("connectionDB.php");
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'recipient'){
    header("Location: login.php");
    exit();
}

$recipient_id    = intval($_SESSION['user_id']);
$blood_group     = mysqli_real_escape_string($conn, $_POST['blood_group']);
$units_requested = intval($_POST['units_requested']);

$q = "INSERT INTO requests (recipient_id, blood_group, units_requested, status, request_date) 
      VALUES ($recipient_id, '$blood_group', $units_requested, 'pending', NOW())";
if(mysqli_query($conn, $q)){
    $_SESSION['msg'] = "Request submitted.";
} else {
    $_SESSION['msg'] = "Error: " . mysqli_error($conn);
}

header("Location: recipient.php");
exit();