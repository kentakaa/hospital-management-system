<?php 
session_start();
include("connectionDB.php");
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'recipient'){
    header("Location: login.php");
    exit();
}
$recipient_id = $_SESSION['user_id'];
$user_id = $_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT name FROM users WHERE user_id = $user_id");
$user = mysqli_fetch_assoc($res);
$name = $user['name'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Recipient Dashboard</title>
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
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.07);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
        }

        /* Base Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Poppins', sans-serif;
            background: var(--background);
            color: var(--text);
            line-height: 1.6;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, var(--accent), #c0392b);
            color: #fff;
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--shadow-md);
        }
        .header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        /* Container */
        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* Cards */
        .card {
            background: var(--card);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        .card h3 {
            color: var(--primary);
            margin-bottom: 1rem;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Buttons */
        a.btn {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, var(--accent), #c0392b);
            color: #fff;
            border-radius: var(--radius-md);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            gap: 0.5rem;
        }
        a.btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.2);
        }

        /* Table Styles */
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
        
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #edf2f7;
        }
        
        th {
            background: linear-gradient(135deg, var(--accent), #c0392b);
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

        /* Messages */
        .message {
            padding: 1rem;
            border-radius: var(--radius-md);
            margin-bottom: 1rem;
            font-weight: 500;
            animation: slideIn 0.3s ease;
        }
        .message.success { background: #d4edda; color: #155724; }
        .message.error { background: #f8d7da; color: #721c24; }

        /* Animations */
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
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
        <h2>Recipient Dashboard</h2>
    </div>
    <div class="container">
        <?php if(isset($_SESSION['msg'])){ 
            echo "<div class='message success'>" . $_SESSION['msg'] . "</div>"; 
            unset($_SESSION['msg']); 
        } ?>
        
        <div class="card">
            <h3>Welcome, <?php echo htmlspecialchars($name); ?>!</h3>
            <p>You can request blood and check your request status here.</p>
            <div style="margin-top: 1.5rem">
                <a href="blood_request.php" class="btn">➕ Request Blood</a>
            </div>
        </div>

        <div class="card">
            <h3>📋 My Requests</h3>
            <?php
            $res = mysqli_query($conn,"SELECT * FROM requests WHERE recipient_id='$recipient_id'");
            if(mysqli_num_rows($res) > 0){
                echo "<table><tr><th>ID</th><th>Blood Group</th><th>Units</th><th>Date</th><th>Status</th></tr>";
                while($row = mysqli_fetch_assoc($res)){
                    echo "<tr>
                            <td>{$row['request_id']}</td>
                            <td>{$row['blood_group']}</td>
                            <td>{$row['units_requested']}</td>
                            <td>{$row['request_date']}</td>
                            <td>{$row['status']}</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No requests found.</p>";
            }
            ?>
        </div>

        <div class="card" style="text-align: right;">
            <a href="logout.php" class="btn">🚪 Logout</a>
        </div>
    </div>
</body>
</html>