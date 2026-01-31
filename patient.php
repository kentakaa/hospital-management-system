    <?php
    session_start();
    include("connectionDB.php");

    // 1. Security Check
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'patient') {
        header("Location: patient_login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // 2. Fetch Patient Info (Correct Logic: Join Users & Patients)
    // हमें मरीज़ का नाम 'users' टेबल से और मरीज़ की ID 'patients' टेबल से चाहिए
    $patient_sql = "SELECT p.patient_id, u.name, u.phone, p.bed_number 
                    FROM patients p 
                    JOIN users u ON p.user_id = u.user_id 
                    WHERE p.user_id = '$user_id'";

    $patient_res = mysqli_query($conn, $patient_sql);

    if (mysqli_num_rows($patient_res) > 0) {
        $patient = mysqli_fetch_assoc($patient_res);
        $patient_id = $patient['patient_id']; 
    } else {
        die("Error: Patient record not found. Please contact administration.");
    }
    $appt_sql = "SELECT a.appointment_id,   
                        a.appointment_date, 
                        a.status, 
                        u_doc.name AS doctor_name, 
                        d.specialization    
                FROM appointments a 
                JOIN doctors d ON a.doctor_id = d.doctor_id 
                JOIN users u_doc ON d.user_id = u_doc.user_id 
                WHERE a.patient_id = '$patient_id' 
                ORDER BY a.appointment_date DESC";

    $appointments = mysqli_query($conn, $appt_sql);

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Patient Dashboard</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <style>
            /* CSS Variables (Theme) */
            :root {
                --primary: #de2020ff;
                /* Blue */
                --light-bg: #eff6ff;
                --white: #ffffff;
                --text: #1e293b;
                --gray: #64748b;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Poppins', sans-serif;
            }

            body {
                background-color: var(--light-bg);
                color: var(--text);
            }

            /* Navbar */
            .navbar {
                background: var(--primary);
                color: var(--white);
                padding: 1rem 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .navbar h2 {
                font-size: 1.5rem;
            }

            .nav-links a {
                color: var(--white);
                text-decoration: none;
                margin-left: 1.5rem;
                font-weight: 500;
                transition: 0.3s;
            }

            .nav-links a:hover {
                color: #bfdbfe;
            }

            /* Container */
            .container {
                max-width: 1200px;
                margin: 2rem auto;
                padding: 0 1rem;
            }

            /* Welcome Banner */
            .welcome-box {
                background: var(--white);
                padding: 1.5rem;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                margin-bottom: 2rem;
                border-left: 5px solid var(--primary);
            }

            .welcome-box h1 {
                font-size: 1.8rem;
                color: var(--primary);
            }

            .welcome-box p {
                color: var(--gray);
                margin: 10px auto;
            }

            /* Cards */
            .card {
                background: var(--white);
                padding: 1.5rem;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                margin-bottom: 2rem;
            }

            .card h2 {
                margin-bottom: 1rem;
                color: var(--text);
                border-bottom: 2px solid #e2e8f0;
                padding-bottom: 0.5rem;
                font-size: 1.4rem;
            }

            /* Tables */
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }

            th,
            td {
                padding: 12px;
                text-align: left;
                border-bottom: 1px solid #e2e8f0;
            }

            th {
                background-color: #f8fafc;
                color: var(--primary);
                font-weight: 600;
            }

            td {
                color: var(--gray);
            }

            /* Status Colors */
            .status-pending {
                color: #d97706;
                font-weight: 600;
            }

            .status-confirmed {
                color: #16a34a;
                font-weight: 600;
            }

            .status-cancelled {
                color: #dc2626;
                font-weight: 600;
            }

            /* Buttons */
            .btn-book {
                background: var(--primary);
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                font-weight: 600;
                display: inline-block;
            }

            .btn-book:hover {
                background: #1e40af;
            }
        </style>
    </head>

    <body>

        <nav class="navbar">
            <h2>PC Hospital</h2>
            <div class="nav-links">
                <a href="logout_patient.php">Logout</a>
            </div>
        </nav>

        <div class="container">
            <!-- Welcome Section -->
            <div class="welcome-box">
                <h1>Welcome, <?php echo htmlspecialchars($patient['name']); ?> </h1>
                <p><strong>Patient ID:</strong> <?php echo $patient['patient_id']; ?> | <strong>Contact:</strong> <?php echo $patient['phone']; ?> | <strong>Bed No:</strong> <?php echo $patient['bed_number'] ?? 'Not Admitted'; ?> | <strong>Ward:</strong></p>
            </div>

            <!-- Appointments Section -->
            <section id="appointments" class="card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h2>📅 My Appointments</h2>
                    <a href="appointment.php" class="btn-book">+ Book New</a>
                </div>

                <table>
                    <tr>
                        
                        <th>Doctor name</th>
                        <th>Department</th>
                        <th>Appointment Date & Time</th>
                        <th>Status</th>
                    </tr>
                    <?php
                    if ($appointments && mysqli_num_rows($appointments) > 0) {
                        while ($row = mysqli_fetch_assoc($appointments)) {
                            $statusClass = 'status-' . strtolower($row['status']);
                    ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['specialization']); ?></td>

                                <td>
                                    <?php echo date('Y-m-d', strtotime($row['appointment_date'])); ?>
                                    (<?php echo date('h:i A', strtotime($row['appointment_date'])); ?>)
                                </td>
                                <td class="<?php echo $statusClass; ?>"><?php echo ucfirst($row['status']); ?></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center;'>No appointments found.</td></tr>";
                    }
                    ?>
                </table>
            </section>

        </div>

    </body>

    </html>