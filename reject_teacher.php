<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
if (!isset($_GET['id'])) { header("Location: teacher_requests.php"); exit; }
$id = intval($_GET['id']);
$conn->query("DELETE FROM teacher_requests WHERE id=$id");
header("Location: teacher_requests.php");
exit;
?>
