<?php
session_start();
include("connectionDB.php"); // Assuming $conn is the mysqli connection object




// --- 2. Input Validation ---
if(!isset($_GET['id']) || !isset($_GET['action'])){
    $_SESSION['msg'] = "⚠️ Invalid request. Missing Appointment ID or Action.";
    header("Location: admin.php?page=appointments");
    exit();
}

$appt_id = intval($_GET['id']);
$action = mysqli_real_escape_string($conn, $_GET['action']);
$new_status = '';

// --- 3. Determine New Status ---
if ($action === 'approve') {
    $new_status = 'confirmed';
    $success_message = "✅ Appointment #{$appt_id} has been Confirmed.";
} elseif ($action === 'reject') {
    $new_status = 'cancelled';
    $success_message = "❌ Appointment #{$appt_id} has been Cancelled by Admin.";
} else {
    $_SESSION['msg'] = "⚠️ Invalid action specified.";
    header("Location: admin.php?page=appointments");
    exit();
}

// --- 4. Update Database using Prepared Statement ---
$stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE appointment_id = ?");
$stmt->bind_param("si", $new_status, $appt_id);

if ($stmt->execute()) {
    // Success: Update successful
    $_SESSION['msg'] = $success_message;
} else {
    // Failure: Database error
    $_SESSION['msg'] = "❌ Database Error: Could not update appointment status.";
}

$stmt->close();

// --- 5. Redirect back to Admin Dashboard ---
header("Location: admin.php?page=appointments");
exit();

?>