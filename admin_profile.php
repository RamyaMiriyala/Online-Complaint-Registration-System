<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
$email = $conn->real_escape_string($_SESSION['admin']);
$r = $conn->query("SELECT * FROM admins WHERE email='$email'")->fetch_assoc();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Profile — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box">
<a href="admin_dashboard.php" class="back-btn">← Back</a>
<h2>Admin Profile</h2>
<div class="card">
<div style="display:grid;gap:14px">
<div><small style="color:var(--text-2);font-size:.78rem;text-transform:uppercase;letter-spacing:.06em">Name</small><div style="font-size:1.15rem;font-weight:600;margin-top:3px"><?= htmlspecialchars($r['name']) ?></div></div>
<div><small style="color:var(--text-2);font-size:.78rem;text-transform:uppercase;letter-spacing:.06em">Email</small><div style="margin-top:3px;font-family:'DM Mono',monospace;font-size:.9rem"><?= htmlspecialchars($r['email']) ?></div></div>
<div><small style="color:var(--text-2);font-size:.78rem;text-transform:uppercase;letter-spacing:.06em">Role</small><div style="margin-top:3px"><span class="badge approved">Principal / Admin</span></div></div>
</div>
</div>
</div><script src="anim.js"></script></body></html>
