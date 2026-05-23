<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
if (!isset($_GET['id'])) { header("Location: password_requests.php"); exit; }
$id = intval($_GET['id']);
$conn->query("DELETE FROM password_resets WHERE id=$id");
header("Location: password_requests.php");
exit;
?>
