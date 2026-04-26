<?php
session_start();
include("connectionDB.php");
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: blood_login.php");
    exit();
}



$page = $_GET['page'] ?? 'donors';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* CSS Variables for Theme */
        :root {
            --primary: #2c3e50;
            --primary-light: #34495e;
            --accent: #e74c3c;
            --success: #27ae60;
            --warning: #f1c40f;
            --info: #3498db;
            --background: #f8fafc;
            --card: #ffffff;
            --text: #2c3e50;
            --text-light: #95a5a6;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
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

        /* Header & Navigation */
        .header {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: #fff;
            padding: 1.5rem;
            position: relative;
            box-shadow: var(--shadow-md);
        }

        .header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            text-align: center;
        }

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
            border-radius: var(--radius-sm);
            font-weight: 500;
            transition: all 0.3s ease;
            border: 2px solid var(--primary);
        }

        .nav a:hover {
            background: var(--primary);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .logout-btn {
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            background: var(--accent);
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        /* Container & Cards */
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .card {
            background: var(--card);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 1rem 0;
            background: var(--card);
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
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
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr {
            transition: background-color 0.2s ease;
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Buttons */
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

        .approve {
            background: var(--success);
            color: #fff;
        }

        .fulfill {
            background: var(--info);
            color: #fff;
        }

        .update {
            background: var(--warning);
            color: var(--text);
        }

        .view {
            background: var(--info);
            color: #fff;
        }

        /* Messages */
        .message {
            padding: 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1rem;
            font-weight: 500;
            animation: slideIn 0.3s ease;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav {
                flex-direction: column;
                align-items: stretch;
            }

            .nav a {
                text-align: center;
            }

            .container {
                padding: 1rem;
            }

            .card {
                padding: 1rem;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Admin Dashboard</h2>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
    <div class="nav">
        <a href="?page=donors">Manage Donors</a>
        <a href="?page=recipients">Manage Recipients</a>
        <a href="?page=requests">Blood Requests</a>
        <a href="?page=stock">Blood Stock</a>
        <a href="?page=camps">Camps</a>
        <a href="?page=staff">Staff</a>
        <a href="?page=appointments">Appointments</a>
    </div>

    <div class="container">
        <?php if (isset($_SESSION['msg'])) {
            echo "<div class='message success'>" . $_SESSION['msg'] . "</div>";
            unset($_SESSION['msg']);
        } ?>

        <?php
        // Donors
        if ($page == 'donors') {
            echo "<div class='card'>";
            echo "<h3>Donors List</h3>";
            $res = mysqli_query($conn, "SELECT user_id, name, email, phone, is_active FROM users WHERE role='donor'");
            echo "<table><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Action</th></tr>";
            while ($row = mysqli_fetch_assoc($res)) {
                $active = intval($row['is_active']) === 1;
                echo "<tr>
                        <td>{$row['user_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>
                        <td>" . ($active ? "✅ Active" : "❌ Inactive") . "</td>
                        <td>";
                if ($active) {
                    echo "<a href='toggle_user.php?id={$row['user_id']}&status=0&page=donors' class='btn deactivate'>Deactivate</a>";
                } else {
                    echo "<a href='toggle_user.php?id={$row['user_id']}&status=1&page=donors' class='btn activate'>Activate</a>";
                }
                echo    "</td></tr>";
            }
            echo "</table></div>";
        }
        // Recipients
        elseif ($page == 'recipients') {
            echo "<div class='card'><h3>Recipients List</h3>";
            $res = mysqli_query($conn, "SELECT user_id, name, email, phone, is_active FROM users WHERE role='recipient'");
            echo "<table><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Action</th></tr>";
            while ($row = mysqli_fetch_assoc($res)) {
                $active = intval($row['is_active']) === 1;
                echo "<tr>
                        <td>{$row['user_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>
                        <td>" . ($active ? "✅ Active" : "❌ Inactive") . "</td>
                        <td>";
                if ($active) {
                    echo "<a href='toggle_user.php?id={$row['user_id']}&status=0&page=recipients' class='btn deactivate'>Deactivate</a>";
                } else {
                    echo "<a href='toggle_user.php?id={$row['user_id']}&status=1&page=recipients' class='btn activate'>Activate</a>";
                }
                echo    "</td></tr>";
            }
            echo "</table></div>";
        }
        // Requests
        elseif ($page == 'requests') {
            echo "<div class='card'><h3>Blood Requests</h3>";
            $res = mysqli_query($conn, "SELECT r.request_id, u.name, u.email, r.blood_group, r.units_requested, r.status, r.request_date
                                       FROM requests r JOIN users u ON r.recipient_id = u.user_id");
            echo "<table><tr><th>ID</th><th>Recipient</th><th>Email</th><th>Blood Group</th><th>Units</th><th>Date</th><th>Status</th><th>Action</th></tr>";
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>
                        <td>{$row['request_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['blood_group']}</td>
                        <td>{$row['units_requested']}</td>
                        <td>{$row['request_date']}</td>
                        <td>{$row['status']}</td>
                        <td>
                            <a href='update_request.php?id={$row['request_id']}&status=approved' class='btn approve'>Approve</a>
                            <a href='update_request.php?id={$row['request_id']}&status=fulfilled' class='btn fulfill'>Fulfill</a>
                        </td>
                      </tr>";
            }
            echo "</table></div>";
        }
        // Blood Stock
        elseif ($page == 'stock') {
            echo "<div class='card'><h3>Blood Stock</h3>";
            $res = mysqli_query($conn, "SELECT * FROM blood_stock");
            echo "<table><tr><th>Blood Group</th><th>Units Available</th><th>Last Updated</th><th>Action</th></tr>";
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>
                        <td>{$row['blood_group']}</td>
                        <td>{$row['units_available']}</td>
                        <td>{$row['last_updated']}</td>
                        <td>
                            <form method='post' action='update_stock.php' style='display:inline;'>
                                <input type='hidden' name='id' value='{$row['stock_id']}'>
                                <input type='number' name='units' min='0' value='{$row['units_available']}' required>
                                <button type='submit' class='btn update'>Update</button>
                            </form>
                        </td>
                      </tr>";
            }
            echo "</table></div>";
        }
        // Camps
        elseif ($page == 'camps') {
            echo "<div class='card'><h3>Blood Donation Camps</h3>";
            $res = mysqli_query($conn, "SELECT * FROM camps ORDER BY date DESC");
            echo "<table><tr><th>ID</th><th>Camp Name</th><th>Location</th><th>Date</th><th>Organizer</th><th>Registrations</th></tr>";
            while ($row = mysqli_fetch_assoc($res)) {
                $camp_id = $row['camp_id'];
                $count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM camp_registrations WHERE camp_id=$camp_id"))['total'];
                echo "<tr>
                        <td>{$row['camp_id']}</td>
                        <td>{$row['camp_name']}</td>
                        <td>{$row['location']}</td>
                        <td>{$row['date']}</td>
                        <td>{$row['organizer']}</td>
                        <td><a class='btn view' href='camp_reg_details.php?camp_id={$row['camp_id']}'>View ($count)</a></td>
                      </tr>";
            }
            echo "</table>";
        }
        // Camp registrations summary
        elseif ($page == 'camp_regs') {
            echo "<h3>Camp Registrations Summary</h3>";
            $sql = "SELECT c.camp_id, c.camp_name, c.date, COUNT(r.reg_id) AS total_regs
                    FROM camps c LEFT JOIN camp_registrations r ON c.camp_id = r.camp_id
                    GROUP BY c.camp_id, c.camp_name, c.date
                    ORDER BY c.date DESC";
            $res = mysqli_query($conn, $sql);
            echo "<table><tr><th>Camp Name</th><th>Date</th><th>Total Registrations</th><th>Action</th></tr>";
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>
                        <td>{$row['camp_name']}</td>
                        <td>{$row['date']}</td>
                        <td>{$row['total_regs']}</td>
                        <td><a class='btn view' href='camp_reg_details.php?camp_id={$row['camp_id']}'>View Details</a></td>
                      </tr>";
            }
            echo "</table>";
        }
        // ... camp_regs वाले elseif के बाद जोड़ें ...

        elseif ($page == 'staff') {
            echo "<div class='card'>";
            // स्टाफ रजिस्टर करने के लिए बटन के साथ हेडिंग
            echo "<h3>Manage Staff <a href='staff_reg.php' class='btn approve' style='float:right; font-size:0.8rem;'>+ Add New Staff</a></h3>";

            // Query: सिर्फ role='staff' वालों को चुनें
            $res = mysqli_query($conn, "SELECT user_id, name, email, phone, is_active FROM users WHERE role='staff'");

            // टेबल शुरू
            echo "<table><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Action</th></tr>";

            while ($row = mysqli_fetch_assoc($res)) {
                $active = intval($row['is_active']) === 1;
                echo "<tr>
                <td>" . htmlspecialchars($row['user_id']) . "</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['phone']) . "</td>
                <td>" . ($active ? "✅ Active" : "❌ Inactive") . "</td>
                <td>";

                // Action Buttons Logic
                if ($active) {
                    // ध्यान दें: page=staff पास किया गया है
                    echo "<a href='toggle_user.php?id={$row['user_id']}&status=0&page=staff' class='btn deactivate'>Deactivate</a>";
                } else {
                    echo "<a href='toggle_user.php?id={$row['user_id']}&status=1&page=staff' class='btn activate'>Activate</a>";
                }
                echo    "</td></tr>";
            }
            echo "</table></div>";
        } elseif ($page == 'appointments') {
            echo "<div class='card'><h3>Manage Appointments</h3>";
            
            // Query to fetch all appointments, joining patient and doctor names
            $sql = "SELECT a.appointment_id, a.appointment_date, a.status, 
                           u1.name AS patient_name, 
                           u2.name AS doctor_name, 
                           d.specialization
                    FROM appointments a
                    JOIN patients p ON a.patient_id = p.patient_id /* Assuming patient_id foreign key */
                    JOIN users u1 ON p.user_id = u1.user_id /* Patient's name */
                    JOIN doctors d ON a.doctor_id = d.doctor_id
                    JOIN users u2 ON d.user_id = u2.user_id /* Doctor's name */
                    ORDER BY a.appointment_date DESC";
            
            $res = mysqli_query($conn, $sql);

            echo "<table><tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Action</th> 
                  </tr>";

            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $status_class = 'status-' . strtolower($row['status']);
                    
                    // Display fix: Formatting datetime
                    $display_datetime = date('Y-m-d h:i A', strtotime($row['appointment_date']));

                    echo "<tr>
                            <td>".htmlspecialchars($row['appointment_id'])."</td>
                            <td>".htmlspecialchars($row['patient_name'])."</td>
                            <td>".htmlspecialchars($row['doctor_name'])."</td>
                            <td>".htmlspecialchars($row['specialization'])."</td>
                            <td>".$display_datetime."</td>
                            <td><span class='status-".strtolower($row['status'])."'>".htmlspecialchars(ucfirst($row['status']))."</span></td>
                            <td>";
                    
                    
                    if (strtolower($row['status']) === 'pending') {
                        echo "<a href='appointment_approval.php?id={$row['appointment_id']}&action=approve' class='btn approve'>Approve</a>";
            
                        echo "<a href='appointment_approval.php?id={$row['appointment_id']}&action=reject' class='btn deactivate'>Reject</a>"; 
                    } else {
                        echo "—"; 
                    }
                    
                    echo      "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='7' style='text-align:center; padding: 20px;'>No appointments found.</td></tr>";
            }
            echo "</table></div>";
        }
        // ... (Remaining logic blocks) ...
        ?>
    </div>
</body>

</html>