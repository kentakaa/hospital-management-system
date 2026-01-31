<?php
include("connectionDB.php");
$message = ""; // To display alerts

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name   = trim($_POST['name']);
    $mail   = trim($_POST['mail']);
    $phone  = trim($_POST['phone']);
    $pass   = $_POST['pass'];
    $conpas = $_POST['con_pas'];

    if($conpas !== $pass){
        $message = "<div class='message error'>❌ Passwords do not match. Please try again.</div>";
    } else {
        // 1. Secure Password Hashing
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        $role = 'patient';
        $is_active = 1; 

        // 2. Check if Email already exists (Optional but recommended)
        $check = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $check->bind_param("s", $mail);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "<div class='message error'>❌ This email is already registered.</div>";
        } else {
            // 3. Insert into Users Table using Prepared Statement
            $sql = "INSERT INTO users (name, email, phone, password, role, is_active) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $name, $mail, $phone, $hashed_password, $role, $is_active);

            if ($stmt->execute()) {
                // Optional: If you have a 'patients' table, you would insert user_id into it here.
                $new_user_id = $stmt->insert_id;
                $conn->query("INSERT INTO patients (user_id, admission_date) VALUES ($new_user_id, NOW())");

                $message = "<div class='message success'>✅ Registration Successful! Redirecting...</div>";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'patient_login.php';
                        }, 1000);
                      </script>";
            } else {
                $message = "<div class='message error'>❌ Error: " . $stmt->error . "</div>";
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-image: url(assests/image_reg.png);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* Card Container */
        .reg-container {
            background-color: #ffffff;
            width: 100%;
            max-width: 450px;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
        }

        .reg-container h2 {
            color: #1d4ed8; /* Brand Blue */
            margin-bottom: 20px;
            font-size: 1.8rem;
            font-weight: 600;
        }

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            text-align: left;
        }

        .fields {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.95rem;
            background-color: #f8fafc;
            transition: all 0.3s ease;
        }

        .fields:focus {
            border-color: #3b82f6;
            background-color: #fff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Button Style */
        .sub-btn {
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

        .sub-btn:hover {
            background-color: #1e40af;
        }

        /* Messages */
        .message {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            text-align: left;
        }
        .success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Login Link */
        .login-link {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #64748b;
        }
        .login-link a {
            color: #1d4ed8;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="reg-container">
        <h2>Patient Registration</h2>
        
        <?php echo $message; ?>

        <form method="post" action="">
            <input type="text" name="name" placeholder="Full Name" class="fields" required>
            <input type="email" name="mail" placeholder="Email Address" class="fields" required>
            <input type="text" name="phone" placeholder="Phone Number" class="fields" required>
            <input type="password" name="pass" placeholder="Create Password" class="fields" required>
            <input type="password" name="con_pas" placeholder="Confirm Password" class="fields" required>
            
            <input type="submit" value="Register" class="sub-btn">
        </form>

        <div class="login-link">
            Already have an account? <a href="patient_login.php">Login here</a>
        </div>
    </div>

</body>
</html>