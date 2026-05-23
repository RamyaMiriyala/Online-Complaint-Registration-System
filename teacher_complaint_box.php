<?php
session_start();
if (!isset($_SESSION['teacher'])) { header("Location: teacher_login.php"); exit; }
include 'db.php';
$email=$conn->real_escape_string($_SESSION['teacher']);
$t=$conn->query("SELECT name FROM teachers WHERE email='$email'")->fetch_assoc();
$name=$conn->real_escape_string($t['name']??'');
if (isset($_POST['send'])) {
    $cat=$conn->real_escape_string($_POST['category']); $msg=$conn->real_escape_string($_POST['message']);
    $conn->query("INSERT INTO complaints (student_name, sender_email, category, message, status) VALUES ('$name','$email','$cat','$msg','pending')");
    header("Location: teacher_complaint_history.php"); exit;
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Submit Complaint</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box"><a href="teacher_dashboard.php" class="back-btn">← Back</a><h2>Submit Complaint</h2>
<div class="card"><form method="post">
<label>Category</label>
<select name="category" required><option value="">Select category</option><option>Infrastructure</option><option>Administration</option><option>Resources</option><option>Students</option><option>Salary</option><option>Other</option></select>
<label>Complaint</label>
<textarea name="message" placeholder="Describe your complaint..." required></textarea>
<button name="send" class="btn" style="margin-top:14px">Submit Complaint</button>
</form></div>
</div><script src="anim.js"></script></body></html>
