<?php
session_start();
include("connectionDB.php");

// केवल donor ही access कर सके
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donor') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// donor_id निकालो donors table से
$res = mysqli_query($conn, "SELECT donor_id FROM donors WHERE user_id = $user_id");
$donor = mysqli_fetch_assoc($res);

if (!$donor) {
    echo "<p>⚠️ Donor profile not found. Please complete donor details first.</p>";
    exit();
}

$donor_id = intval($donor['donor_id']);

$donations = mysqli_query($conn, "SELECT donation_id, donation_date, units_donated,  camp_id
                                  FROM donations
                                  WHERE donor_id=$donor_id
                                  ORDER BY donation_date DESC");
?>
<!DOCTYPE html>
<html>

<head>
    <title>My Donation History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #e74c3c;
            color: #fff;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        a {
            text-decoration: none;
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>📜 My Donation History</h2>
        <?php
        if (mysqli_num_rows($donations) > 0) {
            echo "<table>
                    <tr>
                        <th>Donation ID</th>
                        <th>Date</th>
                        <th>Units</th>
                        <th>Camp ID</th>
                    </tr>";
            while ($d = mysqli_fetch_assoc($donations)) {
                echo "<tr>
            <td>{$d['donation_id']}</td>
            <td>{$d['donation_date']}</td>
            <td>{$d['units_donated']}</td>
            <td>{$d['camp_id']}</td>
          </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No donations recorded yet.</p>";
        }
        ?>
        <br>
        <a href="donor.php">⬅ Back to Dashboard</a>
    </div>
</body>

</html>