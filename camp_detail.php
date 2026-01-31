<?php
include('connectionDB.php');
$camp_id = isset($_GET['camp_id']) ? intval($_GET['camp_id']) : 0;
$camp = null;
if ($camp_id) {
    $res = mysqli_query($conn, "SELECT * FROM camps WHERE camp_id=$camp_id LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $camp = mysqli_fetch_assoc($res);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Camp Details | PC Hospital</title>
    <link rel="stylesheet" href="home_style.css">
</head>
<body>
<nav class="navbar"><div class="navbar-container"><div class="logo">PC Hospital</div><ul class="nav-links"><li><a href="home_page.php">Home</a></li><li><a href="camps_list.php">All Camps</a></li></ul></div></nav>
<div class="container-inner">
    <?php if (!$camp) { ?>
        <h1>Camp not found</h1>
        <p class="muted">The camp you're looking for doesn't exist.</p>
    <?php } else { ?>
        <h1><?php echo htmlspecialchars($camp['camp_name']); ?></h1>
        <p><?php echo htmlspecialchars($camp['location']); ?> • <?php echo htmlspecialchars($camp['date']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($camp['description'] ?? 'No additional information.')); ?></p>
        <a class="btn-primary" href="donor_ragister_camp.php?camp_id=<?php echo $camp['camp_id']; ?>">Register for this camp</a>
    <?php } ?>
</div>
</body>
</html>