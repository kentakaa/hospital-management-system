<?php
session_start();
include("connectionDB.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}


$camp_id = isset($_GET['camp_id']) ? intval($_GET['camp_id']) : 0;
if($camp_id <= 0){
    echo "<h3>Invalid Camp ID</h3>";
    exit();
}


$sql = "SELECT u.name, d.blood_group, d.dob, d.gender, r.reg_date
        FROM camp_registrations r
        JOIN donors d ON r.donor_id = d.donor_id
        JOIN users u ON d.user_id = u.user_id
        WHERE r.camp_id = $camp_id
        ORDER BY r.reg_date DESC";
$res = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Camp Registrations</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family:'Poppins',sans-serif; background:#f5f7fa; margin:0; }
        .header { background:#6c5ce7; color:#fff; padding:15px; text-align:center; }
        .container { padding:20px; max-width:900px; margin:auto; }
        table { width:100%; border-collapse: collapse; margin-top:20px; }
        th, td { padding:10px; border:1px solid #dfe6e9; text-align:center; }
        th { background:#a29bfe; color:#fff; }
    </style>
</head>
<body>
    <div class="header"><h2>Camp Registrations</h2></div>
    <div class="container">
        <table>
            <tr>
                <th>Donor Name</th>
                <th>Blood Group</th>
                <th>DOB</th>
                <th>Gender</th>
                <th>Registration Date</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($res)){ ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['blood_group']; ?></td>
                <td><?php echo $row['dob']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['reg_date']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>