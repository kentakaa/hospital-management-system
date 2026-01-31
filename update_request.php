<?php
session_start();
include("connectionDB.php");
if (
    !isset($_SESSION['user_id']) ||
    ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staff')
){
    header("Location: blood_login.php");
    exit();
}


$id     = intval($_GET['id']);
$status = mysqli_real_escape_string($conn, $_GET['status']); // 'approved' or 'fulfilled'

// If fulfilling, check stock before deduction
if ($status === 'fulfilled') {
    $rq = mysqli_query($conn, "SELECT blood_group, units_requested FROM requests WHERE request_id=$id");
    if ($rq && mysqli_num_rows($rq) === 1) {
        $r = mysqli_fetch_assoc($rq);
        $bg    = mysqli_real_escape_string($conn, $r['blood_group']);
        $units = intval($r['units_requested']);

        // check current stock
        $stockRes = mysqli_query($conn, "SELECT units_available FROM blood_stock WHERE blood_group='$bg'");
        if ($stockRes && mysqli_num_rows($stockRes) === 1) {
            $stockRow  = mysqli_fetch_assoc($stockRes);
            $available = intval($stockRow['units_available']);

            if ($available >= $units) {
                // enough stock → deduct and mark fulfilled
                mysqli_query($conn, "UPDATE blood_stock 
                                     SET units_available = units_available - $units,
                                         last_updated = NOW()
                                     WHERE blood_group = '$bg'");
                $q = "UPDATE requests SET status='fulfilled' WHERE request_id=$id";
                mysqli_query($conn, $q);
                $_SESSION['msg'] = "✅ Request fulfilled. $units units deducted.";
            } else {
                // not enough stock → do not fulfill
                $_SESSION['msg'] = "⚠️ Not enough stock! Available: $available, Requested: $units.";
            }
        }
    }
} else {
    // For approved or other statuses
    $q = "UPDATE requests SET status='$status' WHERE request_id=$id";
    if (mysqli_query($conn, $q)) {
        $_SESSION['msg'] = "✅ Request $status.";
    } else {
        $_SESSION['msg'] = "⚠️ Error: " . mysqli_error($conn);
    }
}

if($_SESSION['role'] == 'admin'){
    header("Location: admin.php?page=requests");
} else {
    header("Location: staff_dashboard.php?page=requests");
}
exit();
?>