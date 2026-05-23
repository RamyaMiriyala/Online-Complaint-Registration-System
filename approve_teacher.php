<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
if (!isset($_GET['id'])) { header("Location: teacher_requests.php"); exit; }
$id = intval($_GET['id']);
$r  = $conn->query("SELECT * FROM teacher_requests WHERE id=$id");
if ($r->num_rows === 0) { header("Location: teacher_requests.php"); exit; }
$t = $r->fetch_assoc();
$name  = $conn->real_escape_string($t['name']);
$email = $conn->real_escape_string($t['email']);
$pass  = $conn->real_escape_string($t['password']);
$dept  = $conn->real_escape_string($t['department']);
$conn->query("INSERT INTO teachers (name, email, password, department) VALUES ('$name','$email','$pass','$dept')");
$conn->query("DELETE FROM teacher_requests WHERE id=$id");
header("Location: teacher_requests.php");
exit;
?>
