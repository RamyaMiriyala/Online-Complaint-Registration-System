<?php
session_start();
if (!isset($_SESSION['teacher'])) { header("Location: teacher_login.php"); exit; }
include 'db.php';
$email = $conn->real_escape_string($_SESSION['teacher']);
$t = $conn->query("SELECT * FROM teachers WHERE email='$email'")->fetch_assoc();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Teacher Dashboard — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-dashboard"><div class="main-box">
<a href="teacher_profile.php" class="profile-circle">👤</a>
<a href="teacher_change_password.php" class="lock-circle">🔒</a>
<h2>Teacher Dashboard</h2>
<p style="text-align:center;color:var(--text-2);font-size:.88rem;margin-top:-18px;margin-bottom:24px">
  <strong style="color:var(--text)"><?= htmlspecialchars($t['name']??'') ?></strong>
  &nbsp;·&nbsp; <?= htmlspecialchars($t['department']??'') ?>
</p>
<div class="quick-grid">
  <div class="quick-card"><a href="teacher_complaint_box.php" class="btn">📩 Submit Complaint</a></div>
  <div class="quick-card"><a href="teacher_complaint_history.php" class="btn ghost">📜 Complaint History</a></div>
  <div class="quick-card"><a href="permission_teachers.php" class="btn ghost">📄 Request Permission</a></div>
  <div class="quick-card"><a href="permission_history_teacher.php" class="btn ghost">🕘 Permission History</a></div>
</div>
<div class="logout-row"><a href="logout.php" class="btn red">Sign Out</a></div>
</div><script src="anim.js"></script></body></html>
