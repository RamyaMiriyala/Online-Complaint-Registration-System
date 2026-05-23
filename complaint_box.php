<?php
session_start();
if (!isset($_SESSION['student'])) { header("Location: student_login.php"); exit; }
include 'db.php';
$email = $conn->real_escape_string($_SESSION['student']);
$s = $conn->query("SELECT name FROM students WHERE email='$email'")->fetch_assoc();
$name = $conn->real_escape_string($s['name'] ?? '');
if (isset($_POST['send'])) {
    $cat = $conn->real_escape_string($_POST['category']);
    $msg = $conn->real_escape_string($_POST['message']);
    $conn->query("INSERT INTO complaints (student_name, student_email, sender_email, category, message, status) VALUES ('$name','$email','$email','$cat','$msg','pending')");
    header("Location: complaint_history.php"); exit;
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Complaint Box — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box">
<a href="student_dashboard.php" class="back-btn">← Back</a>
<h2>Submit Complaint</h2>
<div class="card">
<form method="post">
<label>Category</label>
<select name="category" required>
<option value="">Select category</option>
<option>Infrastructure</option><option>Faculty</option><option>Fees</option>
<option>Transport</option><option>Library</option><option>Hostel</option><option>Other</option>
</select>
<label>Your Complaint</label>
<textarea name="message" placeholder="Describe your complaint in detail..." required></textarea>
<button name="send" class="btn" style="margin-top:14px">Submit Complaint</button>
</form>
</div>
</div><script src="anim.js"></script></body></html>
