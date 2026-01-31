<?php
include("connectionDB.php"); // Assuming $conn is the mysqli connection object

$message = ''; // Variable to hold success/error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name  = trim($_POST['name']);
    $phn   = trim($_POST['phone']);
    $mail  = trim($_POST['mail']);
    $pass1 = $_POST['pass'];
    $pass2 = $_POST['passC'];

    if ($pass1 != $pass2) {
        $message = "<div class='message error'>❌ Password Doesn't match.</div>";
    } else {
        // 1. Password Hashing (MUST DO!)
        $hashed_password = password_hash($pass1, PASSWORD_DEFAULT);
        $role = 'staff';
        $is_active = 1; // Default to active

        // 2. Use Prepared Statements for Security (Avoids SQL Injection)
        $sql = "INSERT INTO users (name, phone, email, password, role, is_active) VALUES (?, ?, ?, ?, ?, ?)";
        
        // Prepare the statement
        $stmt = $conn->prepare($sql);
        
        // Bind parameters (s: string, i: integer)
        // 'sssssi' => name, phone, email, hashed_password, role, is_active
        $stmt->bind_param("sssssi", $name, $phn, $mail, $hashed_password, $role, $is_active);

        try {
            if ($stmt->execute()) {
                $message = "<div class='message success'>✅ Registration Successful! Redirecting to login...</div>";
                
                // Redirect after success (using header is better, but script is safer for message display)
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'staff_login.php';
                        }, 1500);
                      </script>";
            } else {
                // Check for duplicate entry error (e.g., duplicate email)
                if ($conn->errno == 1062) {
                    $message = "<div class='message error'>❌ Error: This Email or Phone number is already registered.</div>";
                } else {
                    $message = "<div class='message error'>❌ Database Error: " . $stmt->error . "</div>";
                }
            }
        } catch (mysqli_sql_exception $e) {
             // Catch other execution exceptions
             $message = "<div class='message error'>❌ An unexpected error occurred.</div>";
        }
        
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff | Registration</title>
    <style>
        /* Base Reset and Body Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background-image: url('assests/image_reg.png');
            background-repeat: no-repeat;
            background-size: cover; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Registration Card Container */
        .registration-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 50, 100, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Title Style */
        .registration-card h1 {
            color: #1d4ed8;
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: 700;
        }
h3{
     color: #1d4ed8;
            margin-bottom: 30px;
            font-size: 20px;
            font-weight: 700;
}
        /* Form Styling */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* Input Fields */
        .field {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #c2d0e2;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .field:focus {
            border-color: #3b82f6; /* Focus blue */
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            outline: none;
        }
        
        /* Placeholder styling for better visibility */
        .field::placeholder {
            color: #94a3b8;
        }

        /* Submit Button */
        .submit-btn {
            background-color: #1d4ed8;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background-color: #1e40af;
        }
        .submit-btn:active {
            transform: scale(0.99);
        }

        /* Message Boxes */
        .message {
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
            font-weight: 500;
            text-align: left;
        }

        .success {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }

        .error {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }
    </style>
</head>
<body>
    <div class="registration-card">
        <h1>Staff Registration</h1>
        <h3>Pc hospital</h3>
        
        <?php echo $message; ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            
            <input type="text" placeholder="Enter Full Name" name="name" class="field" required>
            <input type="text" placeholder="Enter Phone Number" name="phone" class="field" required>
            <input type="email" placeholder="Enter Email (Login ID)" name="mail" class="field" required>
            <input type="password" name="pass" id="p1" placeholder="Create Password" class="field" required>
            <input type="password" name="passC" id="p2" placeholder="Confirm Password" class="field" required>
            
            <input type="submit" value="Register Staff" class="submit-btn">
        </form>

        <p style="margin-top: 20px; font-size: 0.9rem;">
            Already have an account? <a href="staff_login.php" style="color: #1d4ed8; text-decoration: none; font-weight: bold;">Login here</a>
        </p>
    </div>
</body>
</html>