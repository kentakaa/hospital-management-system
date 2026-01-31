<?php
session_start();
include("connectionDB.php");

/* ===== ADMIN ONLY ===== */


$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ===== SANITIZE INPUT ===== */
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $spec  = trim($_POST['specialization'] ?? '');
    $qual  = trim($_POST['qualification'] ?? '');
    $exp   = intval($_POST['experience'] ?? 0);

    /* ===== VALIDATION ===== */
    if ($name == '' || strlen($name) < 3) {
        $errors[] = "Name must be at least 3 characters.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = "Phone must be 10 digits.";
    }

    if (strlen($pass) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($spec == '') {
        $errors[] = "Specialization is required.";
    }

    if ($qual == '') {
        $errors[] = "Qualification is required.";
    }

    if ($exp < 0 || $exp > 60) {
        $errors[] = "Experience must be between 0 and 60 years.";
    }

    /* ===== CHECK DUPLICATE EMAIL ===== */
    if (empty($errors)) {
        $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors[] = "Email already registered.";
        }
        $check->close();
    }

    /* ===== INSERT IF NO ERRORS ===== */
    if (empty($errors)) {

        $conn->begin_transaction();

        try {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);

            /* Insert into users */
            $stmt1 = $conn->prepare(
                "INSERT INTO users (name, email, phone, password, role)
                 VALUES (?, ?, ?, ?, 'doctor')"
            );
            $stmt1->bind_param("ssss", $name, $email, $phone, $hashed);
            $stmt1->execute();
            $user_id = $stmt1->insert_id;
            $stmt1->close();

            /* Insert into doctors */
            $stmt2 = $conn->prepare(
                "INSERT INTO doctors (user_id, specialization, qualification, experience)
                 VALUES (?, ?, ?, ?)"
            );
            $stmt2->bind_param("issi", $user_id, $spec, $qual, $exp);
            $stmt2->execute();
            $stmt2->close();

            $conn->commit();
            $success = "✅ Doctor registered successfully.";

        } catch (Exception $e) {
            $conn->rollback();
            $errors[] = "Database error. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Doctor Registration</title>
<style>
body{font-family:Arial;background:#f4f7fb}
.box{width:420px;margin:40px auto;background:#fff;padding:25px;border-radius:8px}
input{width:100%;padding:10px;margin:6px 0}
button{padding:10px;width:100%;background:#0d9488;color:#fff;border:none}
.error{background:#fee2e2;color:#991b1b;padding:10px;margin-bottom:10px}
.success{background:#dcfce7;color:#166534;padding:10px;margin-bottom:10px}
</style>
</head>
<body>

<div class="box">
<h2>Register Doctor</h2>

<?php
if (!empty($errors)) {
    echo "<div class='error'><ul>";
    foreach ($errors as $e) echo "<li>$e</li>";
    echo "</ul></div>";
}
if ($success) {
    echo "<div class='success'>$success</div>";
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Doctor Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Phone (10 digits)" required>
    <input type="password" name="password" placeholder="Password" required>

    <input type="text" name="specialization" placeholder="Specialization" required>
    <input type="text" name="qualification" placeholder="Qualification" required>
    <input type="number" name="experience" placeholder="Experience (years)" min="0" max="60" required>

    <button type="submit">Register Doctor</button>
</form>
</div>

</body>
</html>
