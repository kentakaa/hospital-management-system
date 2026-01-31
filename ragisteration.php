<?php
include("connectionDB.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Registration</title>
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
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-image: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Ymxvb2R8ZW58MHx8MHx8fDA%3D&w=1000&q=80');
            background-size: cover;
            background-position: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            color: #2d3436;
            text-align: center;
            margin-bottom: 20px;
            font-size: 26px;
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

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #dfe6e9;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        /* ✨ Focus Style */
        .form-group input:focus,
        .form-group select:focus {
            border-color: #0984e3;
            outline: none;
            box-shadow: 0 0 8px rgba(9, 132, 227, 0.4);
            background-color: #f0f8ff;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #0984e3;
            color: white;
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

        /* ✅ Success & Error Messages */
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
    </style>
</head>

<body>
    <div class="container">
        <h2>Registration</h2>
        <h3>Pc hospital Blood Bank</h3>
        <form method="post" action="ragisteration.php">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone">
            </div>
            <div class="form-group password-wrapper">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <i class="fa-solid fa-eye" id="toggleEye1" onclick="togglePassword('password','toggleEye1')"></i>
            </div>
            <div class="form-group password-wrapper">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <i class="fa-solid fa-eye" id="toggleEye2" onclick="togglePassword('confirm_password','toggleEye2')"></i>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="donor">Donor</option>
                    <option value="recipient">Recipient</option>
                </select>
            </div>
            <button type="submit" name="register" class="submit-btn">Register</button>
        </form>

        <?php
        if (isset($_POST['register'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $role = $_POST['role'];

            if ($password !== $confirm_password) {
                echo "<div class='error-message'>❌ Passwords do not match!</div>";
            } else {
                $sql = "INSERT INTO users (name, email, phone, password, role) 
                        VALUES ('$name','$email','$phone','$password','$role')";
                if (mysqli_query($conn, $sql)) {
                    echo "<div class='success-message'>✅ Registration Successful! Redirecting...</div>";
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'blood_login.php';
                            }, 1500);
                          </script>";
                } else {
                    echo "<div class='error-message'>❌ Error: " . mysqli_error($conn) . "</div>";
                }
            }
        }
        ?>
    </div>

    <!-- 👁️ Password Toggle Script -->
    <script>
        function togglePassword(fieldId, eyeId) {
            const passField = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(eyeId);

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