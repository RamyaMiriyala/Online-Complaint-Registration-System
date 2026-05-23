<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
$data = $conn->query("SELECT * FROM teachers ORDER BY name");
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>All Teachers — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box">
<a href="admin_dashboard.php" class="back-btn">← Back</a>
<h2>All Teachers</h2>
<?php if ($data->num_rows === 0): ?><div class="empty-state"><div class="empty-icon">👨‍🏫</div><p>No teachers registered.</p></div><?php endif; ?>
<?php while ($t = $data->fetch_assoc()): ?>
<div class="card student-row">
<div>
  <strong><?= htmlspecialchars($t['name']) ?></strong><br>
  <small style="color:var(--text-2)"><?= htmlspecialchars($t['email']) ?></small><br>
  <small style="color:var(--blue-light);font-size:.82rem"><?= htmlspecialchars($t['department']) ?></small>
</div>
<div class="icon-actions">
  <a href="edit_teacher.php?id=<?= $t['id'] ?>" class="icon-btn icon-edit">✏</a>
  <a href="delete_teacher.php?id=<?= $t['id'] ?>" class="icon-btn icon-delete" onclick="return confirm('Delete?')">🗑</a>
</div>
</div>
<?php endwhile; ?>
</div><script src="anim.js"></script></body></html>
