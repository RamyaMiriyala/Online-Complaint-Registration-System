<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
$student_pending  = $conn->query("SELECT id FROM complaints WHERE status='pending' AND student_email IN (SELECT email FROM students)")->num_rows;
$teacher_pending  = $conn->query("SELECT id FROM complaints WHERE status='pending' AND sender_email IN (SELECT email FROM teachers)")->num_rows;
$student_requests = $conn->query("SELECT id FROM student_requests")->num_rows;
$teacher_requests = $conn->query("SELECT id FROM teacher_requests")->num_rows;
$reset_requests   = $conn->query("SELECT id FROM password_resets")->num_rows;
$student_perm     = $conn->query("SELECT id FROM permissions WHERE role='student' AND status='pending'")->num_rows;
$teacher_perm     = $conn->query("SELECT id FROM permissions WHERE role='teacher' AND status='pending'")->num_rows;
$admin = $conn->query("SELECT name FROM admins WHERE email='".$conn->real_escape_string($_SESSION['admin'])."'")->fetch_assoc();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Principal Dashboard — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-dashboard"><div class="main-box">
<a href="admin_profile.php" class="profile-circle" title="Profile">👤</a>
<a href="admin_change_password.php" class="lock-circle" title="Change Password">🔒</a>
<h2>Principal Dashboard</h2>
<p style="text-align:center;color:var(--text-2);font-size:.88rem;margin-top:-18px;margin-bottom:24px">Welcome back, <strong style="color:var(--text)"><?= htmlspecialchars($admin['name'] ?? '') ?></strong></p>

<div class="section-box">
<h3>Students</h3>
<div class="quick-grid three-grid">
<a href="admin_complaints.php" class="btn ghost" style="position:relative">📩 Student Complaints<?php if($student_pending>0) echo "<span class='notify-dot'></span>";?></a>
<a href="admin_resolved.php" class="btn ghost">✅ Resolved</a>
<a href="student_requests.php" class="btn ghost" style="position:relative">📋 Registrations<?php if($student_requests>0) echo "<span class='notify-dot'></span>";?></a>
<a href="all_students.php" class="btn ghost">👥 All Students</a>
<a href="register_student.php" class="btn ghost">➕ Add Student</a>
<a href="password_requests.php" class="btn ghost" style="position:relative">🔑 Password Resets<?php if($reset_requests>0) echo "<span class='notify-dot'></span>";?></a>
<a href="admin_permission_students.php" class="btn ghost" style="position:relative">📄 Permissions<?php if($student_perm>0) echo "<span class='notify-dot'></span>";?></a>
</div></div>

<div class="section-box">
<h3>Teachers</h3>
<div class="quick-grid three-grid">
<a href="teacher_complaints.php" class="btn ghost" style="position:relative">📩 Teacher Complaints<?php if($teacher_pending>0) echo "<span class='notify-dot'></span>";?></a>
<a href="teacher_resolved.php" class="btn ghost">✅ Resolved</a>
<a href="teacher_requests.php" class="btn ghost" style="position:relative">📋 Registrations<?php if($teacher_requests>0) echo "<span class='notify-dot'></span>";?></a>
<a href="admin_all_teachers.php" class="btn ghost">👨‍🏫 All Teachers</a>
<a href="register_teacher.php" class="btn ghost">➕ Add Teacher</a>
<a href="admin_permission_teachers.php" class="btn ghost" style="position:relative">📄 Permissions<?php if($teacher_perm>0) echo "<span class='notify-dot'></span>";?></a>
</div></div>

<div class="logout-row"><a href="logout.php" class="btn red">Sign Out</a></div>
</div><script src="anim.js"></script></body></html>
