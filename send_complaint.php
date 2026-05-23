<?php
// Redirect to complaint_box.php - the form is now handled there
session_start();
if (!isset($_SESSION['student'])) { header("Location: student_login.php"); exit; }
header("Location: complaint_box.php");
exit;
?>
