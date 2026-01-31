<?php
session_start();
include("connectionDB.php"); // DB connection include

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donor') {
    header("Location: blood_login.php");
    exit();
}

// user ka naam fetch karo
$user_id = $_SESSION['user_id'];
$res = mysqli_query($conn, "SELECT name FROM users WHERE user_id = $user_id");
$user = mysqli_fetch_assoc($res);
$name = $user['name'];

// agar donation form submit hua hai
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['units'])) {
    $units = intval($_POST['units']);
    $date = $_POST['donation_date'];
    $camp_id = !empty($_POST['camp_id']) ? intval($_POST['camp_id']) : NULL;

    // donor_id aur blood_group fetch karo
    $res2 = mysqli_query($conn, "SELECT donor_id, blood_group FROM donors WHERE user_id=$user_id");
    $donor = mysqli_fetch_assoc($res2);

    if ($donor) {
        $did = $donor['donor_id'];
        $blood_group = $donor['blood_group'];

        // donations table me insert
     $sql = "INSERT INTO donations (donor_id, camp_id, units_donated, donation_date, blood_group)
        VALUES ('$did', " . ($camp_id ? $camp_id : "NULL") . ", $units, '$date', '$blood_group')";

        if (mysqli_query($conn, $sql)) {
            // stock update
            mysqli_query($conn, "UPDATE blood_stock 
                                 SET units_available = units_available + $units, 
                                     last_updated = NOW() 
                                 WHERE blood_group='$blood_group'");
            $msg = "✅ Donation recorded successfully!";
        } else {
            $msg = "❌ Error: " . mysqli_error($conn);
        }
    } else {
        $msg = "❌ Donor profile not found. Please complete your donor details first.";
    }
}
// donor details fetch karo
$donorDetailsRes = mysqli_query($conn, "SELECT donor_id, blood_group, dob, gender FROM donors WHERE user_id=$user_id");
$donorDetails = mysqli_fetch_assoc($donorDetailsRes);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['blood_group'])) {
    $blood_group = $_POST['blood_group'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];


    $check = mysqli_query($conn, "SELECT donor_id FROM donors WHERE user_id=$user_id");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE donors SET blood_group='$blood_group', dob='$dob', gender='$gender' WHERE user_id=$user_id");
        $msg = "✅ Donor details updated successfully!";
    } else {
        mysqli_query($conn, "INSERT INTO donors (user_id, blood_group, dob, gender) VALUES ($user_id, '$blood_group', '$dob', '$gender')");
        $msg = "✅ Donor details saved successfully!";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Donor Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* CSS Variables for Theme */
        :root {
            /* Colors */
            --primary: #2c3e50;
            --primary-light: #34495e;
            --accent: #e74c3c;
            --success: #27ae60;
            --success-light: #2ecc71;
            --warning: #f1c40f;
            --info: #3498db;
            --background: #f8fafc;
            --background-alt: #f1f5f9;
            --card: #ffffff;
            --text: #2c3e50;
            --text-light: #95a5a6;
            --text-muted: #64748b;
            --border: #e2e8f0;
            
            /* Shadows */
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.07);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
            --shadow-input: 0 1px 2px rgba(0,0,0,0.06);
            --shadow-success: 0 4px 12px rgba(39,174,96,0.2);
            
            /* Border Radius */
            --radius-sm: 6px;
            
            /* New Custom Colors */
            --detail-bg: #f8fafc;
            --detail-border: #e2e8f0;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-full: 9999px;

            /* Transitions */
            --transition-fast: 0.2s ease;
            --transition-normal: 0.3s ease;
            --transition-slow: 0.5s ease;
        }

        /* Base Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, var(--background) 0%, var(--background-alt) 100%);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, var(--success), var(--success-light));
            color: #fff;
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 45%, rgba(255,255,255,0.1) 50%, transparent 55%);
            animation: shine 3s infinite;
        }
        .header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            position: relative;
        }
        @keyframes shine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }
        .stat-card {
            background: var(--card);
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            text-align: center;
            transition: var(--transition-normal);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--success), var(--success-light));
            transform: scaleX(0.3);
            transform-origin: left;
            transition: transform var(--transition-normal);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .stat-card:hover::after {
            transform: scaleX(1);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 600;
            color: var(--success);
            line-height: 1;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
        }

        .stat-value::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 20%;
            background: var(--success-light);
            opacity: 0.1;
            bottom: 0;
            left: 0;
            transform: skewX(-15deg);
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            transition: var(--transition-normal);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--success), var(--success-light));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform var(--transition-normal);
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .card:hover::before {
            transform: scaleX(1);
        }

        .card h3 {
            color: var(--primary);
            margin-bottom: 1rem;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Links */
        a {
            color: var(--success);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        a:hover {
            color: #219653;
        }

        /* Status Messages */
        .success {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: var(--radius-md);
            font-weight: 500;
            animation: slideIn 0.3s ease;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: var(--radius-md);
            font-weight: 500;
            animation: slideIn 0.3s ease;
        }

        /* Forms */
        form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            background: var(--background-alt);
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            margin: 1rem 0;
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section-title {
            font-size: 1.1rem;
            color: var(--text);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--success-light);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-light);
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--transition-fast);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            color: var(--text);
            background: var(--card);
            box-shadow: var(--shadow-input);
            transition: var(--transition-normal);
        }

        .form-group:hover label {
            color: var(--success);
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--success);
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
            transform: translateY(-1px);
        }

        .form-group .input-icon {
            position: absolute;
            right: 1rem;
            top: 2.5rem;
            color: var(--text-muted);
            transition: var(--transition-fast);
        }

        .form-group input:focus + .input-icon,
        .form-group select:focus + .input-icon {
            color: var(--success);
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #00b894;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 184, 148, 0.4);
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--success), var(--success-light));
            color: #fff;
            padding: 0.875rem 1.75rem;
            border: none;
            border-radius: var(--radius-full);
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-normal);
            font-size: 0.875rem;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: auto;
            min-width: 150px;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(255,255,255,0.2), transparent);
            transform: translateY(-100%);
            transition: transform var(--transition-fast);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-success);
        }

        .btn-submit:hover::before {
            transform: translateY(0);
        }

        .btn-submit:active {
            transform: translateY(1px);
        }

        /* Saved Details Styles */
        .saved-details {
            background: var(--detail-bg);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .detail-row {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--detail-border);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            flex: 0 0 140px;
            font-weight: 500;
            color: var(--text-light);
        }

        .detail-value {
            color: var(--text);
            font-weight: 500;
        }

        .btn-edit {
            background: var(--background);
            color: var(--success);
            border: 2px solid var(--success);
            padding: 0.5rem 1rem;
            border-radius: var(--radius-full);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-normal);
            margin-top: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-edit:hover {
            background: var(--success);
            color: white;
            transform: translateY(-2px);
        }

        .btn-cancel {
            background: var(--background);
            color: var(--text-muted);
            border: 2px solid var(--border);
            padding: 0.875rem 1.75rem;
            border-radius: var(--radius-full);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-normal);
            margin-left: 1rem;
        }

        .btn-cancel:hover {
            background: var(--text-muted);
            color: white;
            transform: translateY(-2px);
        }
        
        /* Form Animation */
        #donorForm {
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    <script>
        function toggleForm() {
            const form = document.getElementById('donorForm');
            const savedDetails = document.querySelector('.saved-details');
            
            if (form.style.display === 'none') {
                form.style.display = 'block';
                savedDetails.style.display = 'none';
            } else {
                form.style.display = 'none';
                savedDetails.style.display = 'block';
            }
        }
    </script>
</head>

<body>
    <div class="header">
        <h2>Donor Dashboard</h2>
    </div>
    <div class="container">
        <?php 
    if(isset($_SESSION['msg'])){
        echo "<div class='message success'>".$_SESSION['msg']."</div>";

        if(isset($_SESSION['dob'])){
            echo "<p>Your Date of Birth: ".$_SESSION['dob']."</p>";
        }
        if(isset($_SESSION['gender'])){
            echo "<p>Your Gender: ".$_SESSION['gender']."</p>";
        }
        if(isset($_SESSION['blood_group'])){
            echo "<p>Your Blood Group: ".$_SESSION['blood_group']."</p>";
        }

        unset($_SESSION['msg']); 
    }
    ?>

       <?php


$user_id = $_SESSION['user_id'];

$res = mysqli_query($conn, "SELECT dob, gender, blood_group FROM donors WHERE user_id=$user_id");
$donor = mysqli_fetch_assoc($res);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $blood_group = $_POST['blood_group'];

    $sql = "UPDATE donors 
            SET dob='$dob', gender='$gender', blood_group='$blood_group' 
            WHERE user_id=$user_id";

  
}
?>

        <?php
        if (isset($_SESSION['msg'])) {
            echo "<p style='color:green; font-weight:600;'>" . $_SESSION['msg'] . "</p>";
            unset($_SESSION['msg']); // एक बार दिखाने के बाद clear कर दो
        }
        ?>
        <div class="card">
            <h3>Welcome, <?php echo htmlspecialchars($name); ?>!</h3>
            <p>You can record your donations, update your donor details, and view history here.</p>
            <?php if (isset($msg)) echo "<p class='" . (strpos($msg, '✅') !== false ? 'success' : 'error') . "'>$msg</p>"; ?>
        </div>

        <!-- Donor Details Section -->
        <div class="card">
            <h3>🧾 Donor Details</h3>
            <?php if(!empty($donorDetails) && !empty($donorDetails['dob']) && !empty($donorDetails['gender']) && !empty($donorDetails['blood_group'])) { ?>
                <!-- Saved Details Show -->
                <div class="saved-details">
                    <div class="detail-row">
                        <span class="detail-label">🩸 Blood Group:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($donorDetails['blood_group']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">🎂 Date of Birth:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($donorDetails['dob']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">⚧ Gender:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($donorDetails['gender']); ?></span>
                    </div>
                    <button onclick="toggleForm()" class="btn-edit">✏️ Edit Details</button>
                </div>
                <!-- Hidden Form -->
                <form method="post" id="donorForm" style="display: none;">
            <?php } else { ?>
                <!-- Show Form for New Details -->
                <form method="post" id="donorForm">
            <?php } ?>
                <div class="form-group">
                    <label>🩸 Blood Group</label>
                    <select name="blood_group" required>
                        <option value="">-- Select --</option>
                        <?php
                        $blood_groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                        foreach($blood_groups as $bg) {
                            $selected = ($donorDetails && $donorDetails['blood_group'] == $bg) ? 'selected' : '';
                            echo "<option value='$bg' $selected>$bg</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>🎂 Date of Birth</label>
                    <input type="date" name="dob" required value="<?php echo $donorDetails ? $donorDetails['dob'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>⚧ Gender</label>
                    <select name="gender" required>
                        <option value="">-- Select --</option>
                        <?php
                        $genders = ['Male', 'Female', 'Other'];
                        foreach($genders as $g) {
                            $selected = ($donorDetails && $donorDetails['gender'] == $g) ? 'selected' : '';
                            echo "<option value='$g' $selected>$g</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Save Details</button>
                <?php if(!empty($donorDetails)) { ?>
                    <button type="button" onclick="toggleForm()" class="btn-cancel">Cancel</button>
                <?php } ?>
            </form>

        </div>

        <!-- Donation Form -->
        <div class="card">
            <h3>🩸 Record a Donation</h3>
            <form method="post">
                <div class="form-group">
                    <label>🧪 Units Donated</label>
                    <input type="number" name="units" min="1" required>
                </div>
                <div class="form-group">
                    <label>📅 Donation Date</label>
                    <input type="date" name="donation_date" required>
                </div>
            
                <div class="form-group">
                    <label>🏥 Camp (optional)</label>
                    <input type="number" name="camp_id" placeholder="Enter Camp ID">
                </div>
                <button type="submit" class="btn-submit">Submit Donation</button>
            </form>
        </div>
        <div class="card">
            <h3>🏥 Register for a Camp</h3>
            <form method="post" action="register_camp.php">
                <label>Select Camp:</label>
                <select name="camp_id"  required>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM camps ORDER BY date DESC");
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<option value='{$row['camp_id']}'>{$row['camp_name']} ({$row['date']})</option>";
                    }
                    ?>
                </select>
                <button type="submit" class="btn-submit">Register</button>
            </form>
        </div>
        <!-- Links -->
        <div class="card">
            <a href="donation_history.php">📜 My Donation History</a><br><br>
            <a href="logout.php">🚪 Logout</a>
        </div>
    </div>
</body>

</html>