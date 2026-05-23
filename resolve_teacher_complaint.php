<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
if (!isset($_GET['id'])) { header("Location: teacher_complaints.php"); exit; }
$id = intval($_GET['id']);
$conn->query("UPDATE complaints SET status='Resolved' WHERE id=$id");
header("Location: teacher_complaints.php");
exit;
?>
