<?php
session_start();
include("connectionDB.php"); 

$message = '';

// --- 1. Security Check and Patient ID Fetch ---
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'patient') {
    header("Location: patient_login.php");
    exit();
}

$current_user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);

// Get the patient_id (required for appointments table)
$patient_data_query = "SELECT patient_id FROM patients WHERE user_id = '$current_user_id'";
$patient_result = mysqli_query($conn, $patient_data_query);

if (mysqli_num_rows($patient_result) === 0) {
    die("Error: Your patient record is incomplete. Please contact staff.");
}
$patient_data = mysqli_fetch_assoc($patient_result);
$current_patient_id = $patient_data['patient_id'];


// --- 2. Fetch Doctors for Dropdown ---
$doctors_options = '';
$sql_doctors = "SELECT d.doctor_id, u.name, d.specialization 
                FROM doctors d 
                JOIN users u ON d.user_id = u.user_id 
                WHERE u.role = 'doctor' AND u.is_active = 1";
$result_doctors = mysqli_query($conn, $sql_doctors);

if ($result_doctors && mysqli_num_rows($result_doctors) > 0) {
    while ($row = mysqli_fetch_assoc($result_doctors)) {
        $doctors_options .= "<option value='" . htmlspecialchars($row['doctor_id']) . "'>" 
                          . htmlspecialchars($row['name']) . " (" . htmlspecialchars($row['specialization']) . ")" 
                          . "</option>";
    }
} else {
    $doctors_options = "<option value=''> Doctors Not Available </option>";
}


// --- 3. Handle Form Submission (INSERT Logic) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_appt'])) {
    
    $doctor_id = trim($_POST['doctor_id']);
    $date = trim($_POST['appointment_date']);
    $time = trim($_POST['appointment_time']);
    $reason = trim($_POST['reason']);
    
    // Check if fields are empty (basic server-side validation)
    if (empty($doctor_id) || empty($date) || empty($time)) {
        $message = "<div class='message error'>❌ Please fill all required fields.</div>";
    } else {
        // Combined DATETIME format
        $appt_datetime = $date . " " . $time . ":00";
        $status = 'pending'; // Default status
        
        // Use Prepared Statement for safe insertion
        $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, status, reason) 
                                VALUES (?, ?, ?, ?, ?)");
        // 'iiss' means: integer, integer, datetime string, status string, reason string
        $stmt->bind_param("iisss", $current_patient_id, $doctor_id, $appt_datetime, $status, $reason);

        if ($stmt->execute()) {
            $_SESSION['msg'] = "Appointment successfully requested! Status: Pending.";
            // Redirect back to dashboard to see the new appointment
            

            header('Location: patient.php'); 
            exit();
            
        } else {
            $message = "<div class='message error'>❌ Appointment failed: " . htmlspecialchars($stmt->error) . "</div>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book | Appointment</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* CSS Styling - Same theme as other dashboards */
        :root { --primary: #1d4ed8; --light-bg: #eef2f6; --white: #ffffff; --text: #1e293b; --error: #dc2626; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--light-bg); display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .appt-card { background: var(--white); padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); width: 100%; max-width: 500px; }
        .appt-card h2 { color: var(--primary); margin-bottom: 30px; text-align: center; font-size: 1.8rem; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 600; color: var(--text); margin-bottom: 8px; }
        input[type="date"], input[type="time"], select, textarea {
            width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem;
            transition: all 0.3s; background-color: #f8fafc;
        }
        input:focus, select:focus, textarea:focus { border-color: #3b82f6; background-color: var(--white); outline: none; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        textarea { resize: vertical; min-height: 100px; }
        .submit-btn { 
            background-color: var(--primary); color: white; padding: 12px; border: none; 
            border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; 
            transition: background-color 0.3s; width: 100%; margin-top: 10px;
        }
        .submit-btn:hover { background-color: #1e40af; }
        .message { padding: 10px; border-radius: 6px; margin-bottom: 20px; font-weight: 500; }
        .error { background-color: #fee2e2; color: var(--error); border: 1px solid #fecaca; }
        .back-link { display: block; margin-top: 20px; text-align: center; color: var(--primary); text-decoration: none; }
    </style>
</head>

<body>
    <div class="appt-card">
        <h2>Book Your Appointment</h2>
        
        <?php echo $message; ?>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            
            <div class="form-group">
                <label for="doctor_id">Select Doctor *</label>
                <select id="doctor_id" name="doctor_id" required>
                    <?php echo $doctors_options; ?> 
                </select>
            </div>

            <div class="form-group">
                <label for="appointment_date">Date *</label>
                <input type="date" id="appointment_date" name="appointment_date" min="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="form-group">
                <label for="appointment_time">Time *</label>
                <input type="time" id="appointment_time" name="appointment_time" required>
            </div>
            
            <div class="form-group">
                <label for="reason">Reason for Visit (Optional)</label>
                <textarea id="reason" name="reason" placeholder="Briefly describe your symptoms or reason for the visit."></textarea>
            </div>
            
            <button type="submit" name="book_appt" class="submit-btn">Request Appointment</button>
        </form>
        <a href="patient.php" class="back-link">← Back to Dashboard</a>'
    
    </div>
</body>
</html>