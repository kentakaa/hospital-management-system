<?php
    

session_start();
include("connectionDB.php");

/* ===== ACCESS: ADMIN + STAFF ===== */
if (
    !isset($_SESSION['user_id']) ||
    ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staff')
){
    header("Location: blood_login.php");
    exit();
}

/* ===== INPUT ===== */
$id    = intval($_POST['id']);
$units = intval($_POST['units']);

/* ===== UPDATE ===== */
$q = "UPDATE blood_stock
      SET units_available = $units, last_updated = NOW()
      WHERE stock_id = $id";

if(mysqli_query($conn, $q)){
    $_SESSION['msg'] = "✅ Stock updated.";
} else {
    $_SESSION['msg'] = "⚠️ Error: " . mysqli_error($conn);
}

/* ===== ROLE BASED REDIRECT ===== */
if($_SESSION['role'] == 'admin'){
    header("Location: admin.php?page=stock");
} else {
    header("Location: staff_dashboard.php?page=stock");
}
exit();
?>
