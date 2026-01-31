<?php
include("connectionDB.php");
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Login</title>
    <!-- Font Awesome for eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Ymxvb2R8ZW58MHx8MHx8fDA%3D&w=1000&q=80');
            background-size: cover;
            background-position: center;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.5s ease;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2d3436;
        }

        h3 {
            color: #636e72;
            text-align: center;
            margin-bottom: 25px;
            font-size: 18px;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #636e72;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #dfe6e9;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: #74b9ff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(116, 185, 255, 0.2);
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #0984e3;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            background: #0870c0;
            transform: translateY(-2px);
        }

        .success-message,
        .error-message {
            margin-top: 15px;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
            font-weight: 500;
            animation: fadeIn 0.5s ease;
        }

        .success-message {
            background: #dff9fb;
            color: #22a6b3;
            border: 1px solid #22a6b3;
        }

        .error-message {
            background: #ffe6e6;
            color: #d63031;
            border: 1px solid #d63031;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* 👁️ Password Eye Icon */
        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 40px;
        }

        .password-wrapper i {
            position: absolute;
            right: 15px;
            top: 47px;
            cursor: pointer;
            color: #636e72;
            font-size: 16px;
        }

        .password-wrapper i:hover {
            color: #0984e3;
        }

        h4 {
            text-align: center;
            margin-top: 15px;
            font-weight: 400;
            color: #2d3436;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <h3>Blood donation management system</h3>
        <form method="post" action="blood_login.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group password-wrapper">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <i class="fa-solid fa-eye" id="toggleEye" onclick="togglePassword()"></i>
            </div>
            <button type="submit" name="login" class="submit-btn">Login</button>

            <h4>Not ragister yet? <a href="ragisteration.php"> Ragister now!</a></h4>

        </form>
        <?php
        if (isset($_POST['login'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND is_active=1";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['role'] = $row['role'];

                echo "<div class='success-message'>✅ Login Successful! Redirecting...</div>";
                if ($row['role'] == 'admin') {
                    echo "<script>setTimeout(()=>{window.location.href='admin.php';},1500);</script>";
                } elseif ($row['role'] == 'donor') {
                    echo "<script>setTimeout(()=>{window.location.href='donor.php';},1500);</script>";
                } else {
                    echo "<script>setTimeout(()=>{window.location.href='recipient.php';},1500);</script>";
                }
            } else {
                echo "<div class='error-message'>❌ Invalid Email/Password OR Account Deactivated!</div>";
            }
        }
        ?>
    </div>

    <!-- Password Toggle Script -->
    <script>
        function togglePassword() {
            const passField = document.getElementById("password");
            const eyeIcon = document.getElementById("toggleEye");

            if (passField.type === "password") {
                passField.type = "text";
                eyeIcon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                passField.type = "password";
                eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>
</body>

</html>