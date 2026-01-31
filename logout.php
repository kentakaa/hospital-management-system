<?php
session_start();
session_unset();
session_destroy();
header("Location: blood_login.php");
exit();
?>