<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
if (!isset($_GET['id'])) { header("Location: all_students.php"); exit; }
$id = intval($_GET['id']);
$conn->query("DELETE FROM students WHERE id=$id");
header("Location: all_students.php");
exit;
?>
