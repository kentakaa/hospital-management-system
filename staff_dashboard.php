<?php


session_start();
include("connectionDB.php");

// 1. Staff Authentication Check
// (Check if user is logged in AND is a staff member)
if (
    !isset($_SESSION['user_id']) ||
    ($_SESSION['role'] != 'staff' && $_SESSION['role'] != 'admin')
) {
    header("Location: blood_login.php");
    exit();
}


$page = $_GET['page'] ?? 'patients'; // Default page set to 'patients' as it's their main job

// 2. Allowed Pages for Staff (Reduced list - Removed 'staff' and 'doctors' management)
$allowed_pages = ['donors', 'recipients', 'requests', 'stock', 'camps', 'patients', 'appointments'];
if (!in_array($page, $allowed_pages)) {
    $page = 'patients';
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Staff Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* CSS Variables (Same theme, maybe slightly different color for staff?) */
        :root {
            --primary: #0d9488;
            /* Teal color for Staff to differentiate from Admin */
            --primary-light: #14b8a6;
            --accent: #e74c3c;
            --success: #27ae60;
            --warning: #f1c40f;
            --info: #3498db;
            --background: #f0fdfa;
            /* Very light teal background */
            --card: #ffffff;
            --text: #2c3e50;
            --text-light: #95a5a6;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --radius-md: 8px;
        }

        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background);
            color: var(--text);
            line-height: 1.6;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: #fff;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-md);
        }

        .header h2 {
            font-size: 1.5rem;
            font-weight: 600;
        }
 .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            gap: 0.5rem;
        }

        .btn:hover {
            transform: translateY(-1px);
            filter: brightness(110%);
        }

        .deactivate {
            background: var(--accent);
            color: #fff;
        }

        .activate {
            background: var(--success);
            color: #fff;
        }
        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: var(--radius-md);
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* Navigation */
        .nav {
            background: var(--card);
            padding: 1rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
            box-shadow: var(--shadow-sm);
        }

        .nav a {
            text-decoration: none;
            padding: 0.75rem 1.25rem;
            color: var(--primary);
            background: var(--card);
            border-radius: var(--radius-md);
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid var(--primary);
        }

        .nav a:hover,
        .nav a.active {
            background: var(--primary);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Container & Cards */
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .card {
            background: var(--card);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-md);
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #edf2f7;
        }

        th {
            background: var(--primary);
            color: #fff;
            font-weight: 500;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        /* Buttons & Status */
        .btn {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 0.9rem;
        }

        .view {
            background: var(--info);
        }

        .update {
            background: var(--warning);
            color: #333;
            border: none;
            cursor: pointer;
        }

        .approve {
            background: var(--success);
        }

        .fulfill {
            background: var(--primary);
        }

        /* Status Badges */
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .active {
            background: #dcfce7;
            color: #166534;
        }

        .inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav {
                flex-direction: column;
            }

            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>PC Hospital Staff Dashboard</h2>
        <a href="logout.php" class="logout-btn">Logout (<?php echo $_SESSION['user_name'] ?? 'Staff'; ?>)</a>
    </div>

    <!-- 3. Simplified Navigation for Staff -->
    <div class="nav">
        <a href="?page=patients" class="<?php echo $page == 'patients' ? 'active' : ''; ?>">Patients</a>
        <a href="?page=appointments" class="<?php echo $page == 'appointments' ? 'active' : ''; ?>">Appointments</a>
        <a href="?page=requests" class="<?php echo $page == 'requests' ? 'active' : ''; ?>">Blood Requests</a>
        <a href="?page=stock" class="<?php echo $page == 'stock' ? 'active' : ''; ?>">Blood Stock</a>
        <a href="?page=donors" class="<?php echo $page == 'donors' ? 'active' : ''; ?>">Donors</a>
        <a href="?page=recipients" class="<?php echo $page == 'recipients' ? 'active' : ''; ?>">Recipients</a>
    </div>

    <div class="container">
        <!-- Alerts -->
        <?php if (isset($_SESSION['msg'])) {
            echo "<div style='padding:1rem; background:#dcfce7; color:#166534; border-radius:8px; margin-bottom:1rem;'>{$_SESSION['msg']}</div>";
            unset($_SESSION['msg']);
        } ?>

        <?php
        // --- PATIENTS (Staff's Primary Job) ---
        if ($page == 'patients') {
            echo "<div class='card'>";
            echo "<div style='display:flex; justify-content:space-between; align-items:center;'>
                    <h3>Patient Records</h3>
                    <a href='patient_reg.php' class='btn approve'>+ Register New Patient</a>
                  </div>";

            // Simple Query
            $sql = "SELECT u.user_id, u.name, u.phone, p.admission_date, p.bed_number 
                    FROM users u JOIN patients p ON u.user_id = p.user_id 
                    WHERE u.role='patient' ORDER BY p.admission_date DESC";
            $res = mysqli_query($conn, $sql);

            echo "<table><tr><th>ID</th><th>Name</th><th>Phone</th><th>Admitted</th><th>Bed</th><th>Action</th></tr>";
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>
                        <td>{$row['user_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['admission_date']}</td>
                        <td>{$row['bed_number']}</td>
                        <td><a href='view_patient_record.php?id={$row['user_id']}' class='btn view'>View Details</a></td>
                      </tr>";
            }
            echo "</table></div>";
        }

        // --- APPOINTMENTS (View Only) ---
        elseif ($page == 'appointments') {
            echo "<div class='card'><h3>Appointments Schedule</h3>";
            $sql = "SELECT a.appointment_id, a.appointment_date, a.status, 
                           u1.name AS patient_name, u2.name AS doctor_name 
                    FROM appointments a
                    JOIN users u1 ON a.patient_id = u1.user_id 
                    JOIN doctors d ON a.doctor_id = d.doctor_id
                    JOIN users u2 ON d.user_id = u2.user_id
                    ORDER BY a.appointment_date DESC";
            $res = mysqli_query($conn, $sql);

            echo "<table><tr><th>ID</th><th>Patient</th><th>Doctor</th><th>Date</th><th>Status</th></tr>";
            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                            <td>{$row['appointment_id']}</td>
                            <td>{$row['patient_name']}</td>
                            <td>{$row['doctor_name']}</td>
                            <td>{$row['appointment_date']}</td>
                            <td><strong>{$row['status']}</strong></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No appointments found.</td></tr>";
            }
            echo "</table></div>";
        }

        // --- BLOOD STOCK (Update Allowed) ---
        elseif ($page == 'stock') {
            echo "<div class='card'><h3>Blood Stock Inventory</h3>";
            $res = mysqli_query($conn, "SELECT * FROM blood_stock ORDER BY blood_group");
            echo "<table><tr><th>Blood Group</th><th>Units Available</th><th>Last Updated</th><th>Update Stock</th></tr>";
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>
                        <td style='font-weight:bold; font-size:1.2rem;'>{$row['blood_group']}</td>
                        <td>{$row['units_available']} Units</td>
                        <td>{$row['last_updated']}</td>
                        <td>
                            <form method='post' action='update_stock.php' style='display:flex; gap:10px;'>
                                <input type='hidden' name='id' value='{$row['stock_id']}'>
                                <input type='number' name='units' min='0' value='{$row['units_available']}' style='width:60px; padding:5px;'>
                                <button type='submit' class='btn update'>Update</button>
                            </form>
                        </td>
                      </tr>";
            }
            echo "</table></div>";
        }

        // --- BLOOD REQUESTS (View/Approve) ---
        elseif ($page == 'requests') {
            echo "<div class='card'><h3>Blood Requests</h3>";
            $res = mysqli_query($conn, "SELECT r.request_id, u.name, r.blood_group, r.units_requested, r.status 
                                       FROM requests r JOIN users u ON r.recipient_id = u.user_id 
                                       ORDER BY r.request_date DESC");
            echo "<table><tr><th>Recipient</th><th>Blood Group</th><th>Units</th><th>Status</th><th>Action</th></tr>";
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['blood_group']}</td>
                        <td>{$row['units_requested']}</td>
                        <td>{$row['status']}</td>
                        <td>";
                if ($row['status'] == 'pending') {
                    echo "<a href='update_request.php?id={$row['request_id']}&status=approved' class='btn approve'>Approve</a>";
                } elseif ($row['status'] == 'approved') {
                    echo "<a href='update_request.php?id={$row['request_id']}&status=fulfilled' class='btn fulfill'>Fulfill</a>";
                } else {
                    echo "<span>N/A</span>  ";
                }
                echo "</td></tr>";
            }
            echo "</table></div>";
        }

        //  DONORS
        elseif ($page == 'donors') {
            echo "<div class='card'><h3>Registered Donors</h3>";
            $res = mysqli_query($conn, "SELECT user_id, name, email, phone, is_active FROM users WHERE role='donor'");
            echo "<table><tr><th>User ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Action</th></tr>";
            while ($row = mysqli_fetch_assoc($res)) {
                $status = intval($row['is_active']) === 1;
                echo "<tr>
                <td>{$row['user_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>    
                            
                         <td>" . ($status ? "✅ Active" : "❌ Inactive") . "</td>
                        <td>";
                if ($status == 1) {
                    echo "<a href='toggle_user.php?id={$row['user_id']}&status=0&page=donors' class='btn deactivate'>Deactivate</a>";
                } else {
                    echo "<a href='toggle_user.php?id={$row['user_id']}&status=1&page=donors' class='btn activate'>Activate</a>";
                }
                echo    "</td></tr>";
            }
            echo "</table></div>";  
        }

        //  RECIPIENTS 
        elseif ($page == 'recipients') {
            echo "<div class='card'><h3>Registered Recipients</h3>";
            $res = mysqli_query($conn, "SELECT user_id, name, email, phone, is_active FROM users WHERE role='recipient'");
            echo "<table><tr><th>User ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Action</th></tr>";
            while ($row = mysqli_fetch_assoc($res)) {
                $status = intval($row['is_active']) === 1;
                echo "<tr>
                <td>{$row['user_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>    
                            
                         <td>" . ($status ? "✅ Active" : "❌ Inactive") . "</td>
                        <td>";
                if ($status == 1) {
                    echo "<a href='toggle_user.php?id={$row['user_id']}&status=0&page=donors' class='btn deactivate'>Deactivate</a>";
                } else {
                    echo "<a href='toggle_user.php?id={$row['user_id']}&status=1&page=donors' class='btn activate'>Activate</a>";
                }
                echo    "</td></tr>";
            }
            echo "</table></div>";
        }
        ?>
    </div>
</body>

</html>