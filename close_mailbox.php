<?php
session_start();
unset($_SESSION['mail_verified']);
unset($_SESSION['mail_email']);
header("Location: student_login.php");
exit;
?>
