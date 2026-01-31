<?php
include("connectionDB.php");
session_start();

$message = ''; // Variable to hold success/error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mail = trim($_POST['mail']);
    $pass = $_POST['pass'];

    // 1. Use Prepared Statements for Security (Crucial for login)
    $sql = "SELECT user_id, name, password, role FROM users WHERE email = ? AND role = 'staff' AND is_active = 1";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Bind the email parameter
    $stmt->bind_param("s", $mail);
    
    // Execute and get result
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // 2. Verify the Hashed Password
        if (password_verify($pass, $row['password'])) {
            // ✅ Login success
            $_SESSION['user_id'] = $row['user_id']; // Using a generic 'user_id' is better practice
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['role'] = $row['role']; 
            
            // Redirect to the staff dashboard/home page
            header("Location: staff_dashboard.php");
            exit();
        } else {
            $message = "<div class='message error'>❌ Invalid Email or Password.</div>";
        }
    } else {
        $message = "<div class='message error'>❌ Invalid Email or Password.</div>";
    }
    
    $stmt->close();
}

// NOTE: I changed the submit button type from 'button' to 'submit'
// and the session variable keys from 'staff_id'/'staff_name' to generic 'user_id'/'user_name' 
// for consistency across different user roles.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff | Login</title>
    <style>
        /* Base Reset and Body Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background-image: url(assests/image_log.png);
            background-repeat: no-repeat;
            background-size: cover;
            
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Login Card Container */
        .login-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 50, 100, 0.1);
            width: 100%;
            max-width: 350px; /* Slightly smaller for login */
            text-align: center;
        }

        /* Title Style */
        .login-card h2 {
            color: #1d4ed8;
            margin-bottom: 30px;
            font-size: 1.8rem;
            font-weight: 700;
        }
h3{
    color: #1d4ed8;
            margin-bottom: 25px;
            font-size: 1.3rem;
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
        
        /* Placeholder styling */
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

        .error {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Staff Login</h2>
        <h3>Pc Hospital</h3>
        
        <?php echo $message; ?>

        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <input type="email" name="mail" placeholder="Enter Email" class="field" required>
            <input type="password" name="pass" placeholder="Enter Password" class="field" required>
            <input type="submit" value="Login" class="submit-btn">
        </form>

        
    </div>
</body>
</html>