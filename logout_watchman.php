<?php
session_start();
unset($_SESSION['watchman']);
unset($_SESSION['watchman_name']);
header("Location: watchman_login.php");
exit;
?>
