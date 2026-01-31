<?php
session_start();
include("connectionDB.php");

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 1. Prepare Statement to prevent SQL Injection
    // We check for role = 'patient' specifically
    $stmt = $conn->prepare("SELECT user_id, name, password, role FROM users WHERE email = ? AND role = 'patient' AND is_active = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // 2. Verify Password               
        if (password_verify($password, $row['password'])|| $password === $row['password']) {
            // Login Success
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_role'] = $row['role'];
            
            
            header("Location: patient.php");
            exit();
        } else {
            $message = "<div class='message error'>❌ Incorrect password.</div>";
        }
    } else {
        $message = "<div class='message error'>❌ No patient account found with this email.</div>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Base Reset & Typography */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-image: url('assests/patient_login.png');
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Login Card */
        .login-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-card h2 {
            color: #1d4ed8; /* Primary Blue */
            margin-bottom: 10px;
            font-size: 2rem;
            font-weight: 600;
        }
        
        .subtitle {
            color: #64748b;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        /* Form Elements */
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            text-align: left;
        }

        label {
            font-weight: 500;
            color: #334155;
            margin-bottom: 5px;
            display: block;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #3b82f6;
            background-color: #fff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        input[type="submit"] {
            background-color: #1d4ed8;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #1e40af;
        }

        /* Links */
        .register-link {
            margin-top: 25px;
            font-size: 0.9rem;
            color: #64748b;
        }

        .register-link a {
            color: #1d4ed8;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Error Message */
        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            text-align: left;
        }
        .error {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <h2>Welcome Back</h2>
        <p class="subtitle">Please login to access your patient portal</p>
        
        <?php echo $message; ?>

        <form action="patient_login.php" method="POST">
            <div>
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <input type="submit" value="Login">
        </form>
        
        <div class="register-link">
            <p>New Patient? <a href="patient_reg.php">Register Now</a></p>
        </div>
    </div>
</body>
</html>