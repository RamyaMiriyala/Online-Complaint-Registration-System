<?php
session_start();
if (!isset($_SESSION['student'])) { header("Location: student_login.php"); exit; }
include 'db.php';
$email = $conn->real_escape_string($_SESSION['student']);
$s = $conn->query("SELECT * FROM students WHERE email='$email'")->fetch_assoc();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Student Dashboard — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-dashboard"><div class="main-box">
<a href="student_change_password.php" class="lock-circle">🔒</a>
<a href="student_profile.php" class="profile-circle">👤</a>
<h2>Student Dashboard</h2>
<p style="text-align:center;color:var(--text-2);font-size:.88rem;margin-top:-18px;margin-bottom:24px">
  <strong style="color:var(--text)"><?= htmlspecialchars($s['name']??'') ?></strong>
  &nbsp;·&nbsp; <?= htmlspecialchars($s['stream']??'') ?> — <?= htmlspecialchars($s['section']??'') ?>
  &nbsp;·&nbsp; Roll <?= htmlspecialchars($s['roll_number']??'') ?>
</p>
<div class="quick-grid">
  <div class="quick-card"><a href="complaint_box.php" class="btn">📩 Submit Complaint</a></div>
  <div class="quick-card"><a href="complaint_history.php" class="btn ghost">📜 Complaint History</a></div>
  <div class="quick-card"><a href="permission_students.php" class="btn ghost">📄 Request Permission</a></div>
  <div class="quick-card"><a href="permission_history_student.php" class="btn ghost">🕘 Permission History</a></div>
  <div class="quick-card"><a href="student_change_secret.php" class="btn ghost">🔐 Change Secret</a></div>
</div>
<div class="logout-row"><a href="logout.php" class="btn red">Sign Out</a></div>
</div><script src="anim.js"></script></body></html>
