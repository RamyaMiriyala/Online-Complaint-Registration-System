<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
if (!isset($_GET['id'])) { header("Location: admin_all_teachers.php"); exit; }
$id = intval($_GET['id']);
$conn->query("DELETE FROM teachers WHERE id=$id");
header("Location: admin_all_teachers.php");
exit;
?>
