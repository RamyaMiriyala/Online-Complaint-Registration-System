<?php
session_start();
if (!isset($_SESSION['student'])) { header("Location: student_login.php"); exit; }
include 'db.php';
$email = $conn->real_escape_string($_SESSION['student']);
$r = $conn->query("SELECT * FROM students WHERE email='$email'")->fetch_assoc();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Profile — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box">
<a href="student_dashboard.php" class="back-btn">← Back</a>
<h2>My Profile</h2>
<div class="card"><div style="display:grid;gap:16px">
<?php foreach (['NAME'=>'name','STREAM'=>'stream','SECTION'=>'section','ROLL NUMBER'=>'roll_number','EMAIL'=>'email'] as $lbl=>$key): ?>
<div><small style="color:var(--text-2);font-size:.75rem;text-transform:uppercase;letter-spacing:.06em"><?= $lbl ?></small>
<div style="font-size:.98rem;font-weight:500;margin-top:3px"><?= htmlspecialchars($r[$key]??'') ?></div></div>
<?php endforeach; ?>
</div></div>
</div><script src="anim.js"></script></body></html>
