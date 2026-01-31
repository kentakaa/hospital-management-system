<?php
include('connectionDB.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Donation Camps | PC  Hospital</title>
    <link rel="stylesheet" href="home_style.css">
</head>
<body>
<nav class="navbar"><div class="navbar-container"><div class="logo">PC Hospital</div><ul class="nav-links"><li><a href="home_page.php">Home</a></li></ul></div></nav>
<div class="container-inner">
    <h1>All Donation Camps</h1>
    <?php
    $res = mysqli_query($conn, "SELECT * FROM camps ORDER BY date DESC");
    if ($res && mysqli_num_rows($res) > 0) {
        echo "<div class='camp-list all-camps'>";
        while ($r = mysqli_fetch_assoc($res)) {
            echo "<div class='camp-card'>";
            echo "<h3>".htmlspecialchars($r['camp_name'])."</h3>";
            echo "<p>".htmlspecialchars($r['location'])." • ".htmlspecialchars($r['date'])."</p>";
            echo "<a class='btn-primary' href='camp_detail.php?camp_id=".$r['camp_id']."'>View</a>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p class='muted'>No camps found.</p>";
    }
    ?>
</div>
</body>
</html>