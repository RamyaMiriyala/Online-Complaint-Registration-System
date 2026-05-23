<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
if (!isset($_GET['id'])) { header("Location: student_requests.php"); exit; }
$id = intval($_GET['id']);

$r = $conn->query("SELECT * FROM student_requests WHERE id=$id");
if ($r->num_rows === 0) { header("Location: student_requests.php"); exit; }
$row = $r->fetch_assoc();

$name    = $conn->real_escape_string($row['name']);
$roll    = $conn->real_escape_string($row['roll_number']);
$email   = $conn->real_escape_string($row['email']);
$pass    = $conn->real_escape_string($row['password']);
$secret  = $conn->real_escape_string($row['secret_answer']);
$stream  = $conn->real_escape_string($row['stream']);
$section = $conn->real_escape_string($row['section']);

$conn->query("INSERT INTO students (name, roll_number, email, password, secret_answer, stream, section) VALUES ('$name','$roll','$email','$pass','$secret','$stream','$section')");
$conn->query("DELETE FROM student_requests WHERE id=$id");

header("Location: student_requests.php");
exit;
?>
