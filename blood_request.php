<?php 
session_start();
include("connectionDB.php");
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'recipient'){
    header("Location: blood_login.php");
    exit();
}
$recipient_id = $_SESSION['user_id'];

if(isset($_POST['submit'])){
    $blood_group = $_POST['blood_group'];
    $units = $_POST['units'];
    $date = date('Y-m-d');

    $sql = "INSERT INTO requests (recipient_id, blood_group, units_requested, status, request_date) 
            VALUES ('$recipient_id','$blood_group','$units','pending','$date')";
    if(mysqli_query($conn,$sql)){
        echo "<script>alert('✅ Request submitted successfully!'); window.location='recipient.php';</script>";
    } else {
        echo "<script>alert('❌ Error: ".mysqli_error($conn)."');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Request Blood</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family:'Poppins',sans-serif; background:#f5f7fa; margin:0; display:flex; justify-content:center; align-items:center; height:100vh; }
        .form-box { background:#fff; padding:25px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); width:350px; }
        h2 { text-align:center; margin-bottom:20px; color:#d63031; }
        label { display:block; margin:10px 0 5px; }
        input, select { width:100%; padding:10px; border:1px solid #ccc; border-radius:5px; }
        button { margin-top:15px; width:100%; padding:10px; background:#d63031; color:#fff; border:none; border-radius:5px; cursor:pointer; }
        button:hover { background:#b71c1c; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Request Blood</h2>
        <form method="post">
            <label for="blood_group">Blood Group</label>
            <select name="blood_group" required>
                <option value="">--Select--</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
            </select>

            <label for="units">Units Required</label>
            <input type="number" name="units" min="1" required>

            <button type="submit" name="submit">Submit Request</button>
        </form>
    </div>
</body>
</html>