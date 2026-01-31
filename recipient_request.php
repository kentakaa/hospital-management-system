<?php
session_start();
include("connectionDB.php");
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'recipient') {
    header("Location: login.php");
    exit();
}
$recipient_user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Recipient Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Base */
        :root {
            --primary: #6c5ce7;
            --accent: #4e4cff;
            --muted: #9aa3bf;
            --bg1: #f3f6ff;
            --bg2: #f5f7fa
        }

        * {
            box-sizing: border-box
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, var(--bg1), var(--bg2));
            margin: 0;
            color: #2b3350;
        }

        /* Header */
        .header {
            background: linear-gradient(90deg, var(--primary), #7b66ff);
            color: #fff;
            padding: 18px 20px;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px
        }

        .header h2 {
            margin: 0;
            font-weight: 600;
            font-size: 20px;
            letter-spacing: 0.2px
        }

        .logout {
            background: rgba(255, 255, 255, 0.12);
            padding: 8px 12px;
            border-radius: 8px;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            transition: background .22s, transform .18s
        }

        .logout:hover {
            background: rgba(255, 255, 255, 0.18);
            transform: translateY(-2px)
        }

        /* Container */
        .container {
            padding: 28px;
            max-width: 1000px;
            margin: 28px auto;
            animation: fadeIn .45s ease both
        }

        /* Card */
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 6px 18px rgba(46, 61, 125, 0.08);
            margin-top: 18px;
            transition: transform .2s, box-shadow .2s
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 36px rgba(46, 61, 125, 0.09)
        }

        /* Form */
        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            align-items: end
        }

        form label {
            display: block;
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 6px
        }

        input,
        select {
            padding: 12px;
            border: 1px solid #e6e9f2;
            border-radius: 10px;
            width: 100%;
            outline: none;
            background: #fbfdff;
            transition: box-shadow .18s, border-color .18s, transform .12s
        }

        input:focus,
        select:focus {
            box-shadow: 0 6px 18px rgba(101, 82, 255, 0.12);
            border-color: var(--accent);
            transform: translateY(-1px)
        }

        .full-width {
            grid-column: 1/-1
        }

        /* Button */
        .btn {
            background: linear-gradient(180deg, var(--primary), var(--accent));
            color: #fff;
            border: none;
            padding: 12px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: transform .16s, box-shadow .16s
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 26px rgba(99, 84, 255, 0.16)
        }

        /* Messages */
        .msg {
            color: green;
            font-weight: 700;
            background: rgba(72, 187, 120, 0.08);
            padding: 10px;
            border-radius: 8px;
            display: inline-block
        }

        /* Table */
        .table-card {
            overflow: hidden
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: transparent
        }

        thead th {
            background: linear-gradient(90deg, var(--primary), #6b5ce3);
            color: #fff;
            padding: 12px;
            text-align: center;
            font-weight: 600;
            font-size: 14px
        }

        tbody td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #f1f3fb;
            color: #333
        }

        tbody tr {
            background: #fff;
            transition: background .18s, transform .18s
        }

        tbody tr:hover {
            background: #fbfaff
        }

        /* Responsive */
        @media (max-width:720px) {
            form {
                grid-template-columns: 1fr
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px
            }

            .container {
                padding: 16px
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Recipient Dashboard</h2>
        <a class="logout" href="logout.php">Logout</a>
    </div>
    <div class="container">
        <?php if (isset($_SESSION['msg'])) {
            echo "<p class='msg'>" . $_SESSION['msg'] . "</p>";
            unset($_SESSION['msg']);
        } ?>

        <h3>Make a new blood request</h3>
        <div class="card">
            <form method="post" action="recipient_request_submit.php">
                <label>Blood Group</label>
                <select name="blood_group" required>
                    <option value="">Select</option>
                    <option>A+</option>
                    <option>A-</option>
                    <option>B+</option>
                    <option>B-</option>
                    <option>AB+</option>
                    <option>AB-</option>
                    <option>O+</option>
                    <option>O-</option>
                </select>
                <label>Units requested</label>
                <input type="number" name="units_requested" min="1" required>
                    <button class="btn full-width" type="submit">Submit request</button>
            </form>
        </div>

        <h3 style="margin-top:30px;">My requests</h3>
        <div class="card table-card">
            <?php
            $res = mysqli_query($conn, "SELECT request_id, blood_group, units_requested, status, request_date 
                                                                FROM requests WHERE recipient_id = $recipient_user_id ORDER BY request_date DESC");
            echo "<table><thead><tr><th>ID</th><th>Blood Group</th><th>Units</th><th>Date</th><th>Status</th></tr></thead><tbody>";
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>
                                <td>{$row['request_id']}</td>
                                <td>{$row['blood_group']}</td>
                                <td>{$row['units_requested']}</td>
                                <td>{$row['request_date']}</td>
                                <td>{$row['status']}</td>
                            </tr>";
            }
            echo "</tbody></table>";
            ?>
        </div>
    </div>
</body>

</html>