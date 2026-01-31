<?php
session_start();
include("connectionDB.php");

if (
    !isset($_SESSION['user_id']) ||
    ($_SESSION['role'] != 'staff' && $_SESSION['role'] != 'admin')
) {
    header("Location: home.php");
    exit();
}

if(!isset($_GET['id']) || !isset($_GET['status'])){
    $_SESSION['msg'] = "⚠️ Invalid request.";
    header("Location: admin.php?page=donors");
    exit();
}

$id   = intval($_GET['id']);
$st   = intval($_GET['status']);
$page = isset($_GET['page']) ? $_GET['page'] : 'donors';

if($st !== 0 && $st !== 1){
    $_SESSION['msg'] = "⚠️ Invalid status value.";
    header("Location: admin.php?page=$page");
    exit();
}

$exists = mysqli_query($conn, "SELECT user_id FROM users WHERE user_id = $id");
if(!$exists || mysqli_num_rows($exists) === 0){
    $_SESSION['msg'] = "⚠️ User not found.";
    header("Location: admin.php?page=$page");
    exit();
}

$sql = "UPDATE users SET is_active = $st WHERE user_id = $id";
if(mysqli_query($conn, $sql)){
    $_SESSION['msg'] = ($st === 1) ? "✅ User Activated" : "❌ User Deactivated";
} else {
    $_SESSION['msg'] = "⚠️ DB Error: " . mysqli_error($conn);
}

if($_SESSION['role'] == 'admin'){
    header("Location: admin.php?page=$page");
} else {
    header("Location: staff_dashboard.php?page=$page");
}
exit();
exit();
?>