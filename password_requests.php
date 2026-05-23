<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Password Resets</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box">
<a href="admin_dashboard.php" class="back-btn">← Back</a><h2>Password Reset Requests</h2>
<?php
$q=$conn->query("SELECT * FROM password_resets ORDER BY id DESC");
if ($q->num_rows===0) echo "<div class='empty-state'><div class='empty-icon'>✅</div><p>No reset requests.</p></div>";
while ($r=$q->fetch_assoc()):
?>
<div class="card student-row">
  <div><strong><?= htmlspecialchars($r['email']) ?></strong><br><small style="color:var(--text-2)">Role: <?= $r['role'] ?> · <?= $r['created_at'] ?></small></div>
  <div class="icon-actions">
    <a href="reset_student_password.php?email=<?= urlencode($r['email']) ?>" class="icon-btn icon-edit" title="Reset">🔑</a>
    <a href="delete_password_request.php?id=<?= $r['id'] ?>" class="icon-btn icon-delete" title="Dismiss">🗑</a>
  </div>
</div>
<?php endwhile; ?>
</div><script src="anim.js"></script></body></html>
