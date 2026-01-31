<?php
session_start();
include("connectionDB.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donor'){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$camp_id = intval($_POST['camp_id']);


$res = mysqli_query($conn, "SELECT donor_id FROM donors WHERE user_id = $user_id");
$donor = mysqli_fetch_assoc($res);

if(!$donor){
    $_SESSION['msg'] = "⚠️ Donor profile not found. Please complete donor details first.";
    header("Location: donor.php");
    exit();
}

$donor_id = intval($donor['donor_id']);


$chk = mysqli_query($conn, "SELECT reg_id FROM camp_registrations WHERE camp_id=$camp_id AND donor_id=$donor_id");
if($chk && mysqli_num_rows($chk) > 0){
    $_SESSION['msg'] = "⚠️ You are already registered for this camp.";
    header("Location: donor.php");
    exit();
}


$sql = "INSERT INTO camp_registrations (camp_id, donor_id, reg_date) 
        VALUES ($camp_id, $donor_id, NOW())";

if(mysqli_query($conn, $sql)){
    $_SESSION['msg'] = "✅ Registered for camp successfully!";
} else {
    $_SESSION['msg'] = "❌ Error: " . mysqli_error($conn);
}

header("Location: donor.php");
exit();
?>