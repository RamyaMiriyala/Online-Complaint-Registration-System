<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
$data = $conn->query("SELECT c.id, c.message, c.category, c.created_at, t.name, t.email, t.department FROM complaints c JOIN teachers t ON c.sender_email = t.email WHERE c.status = 'pending' ORDER BY c.id DESC");
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Teacher Complaints — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box">
<a href="admin_dashboard.php" class="back-btn">← Back</a>
<h2>Pending Teacher Complaints</h2>
<?php if ($data->num_rows === 0): ?>
<div class="empty-state"><div class="empty-icon">✅</div><p>No pending teacher complaints.</p></div>
<?php endif; ?>
<?php while ($row = $data->fetch_assoc()): ?>
<div class="card">
  <div style="display:flex;flex-wrap:wrap;gap:16px;font-size:.85rem;margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid var(--border)">
    <span><span style="color:var(--text-2)">Teacher:</span> <strong><?= htmlspecialchars($row['name']) ?></strong></span>
    <span><span style="color:var(--text-2)">Dept:</span> <?= htmlspecialchars($row['department']) ?></span>
    <span><span style="color:var(--text-2)">Category:</span> <span style="color:var(--blue-light)"><?= htmlspecialchars($row['category']) ?></span></span>
  </div>
  <div class="msg-box"><?= nl2br(htmlspecialchars($row['message'])) ?></div>
  <div style="display:flex;justify-content:space-between;align-items:center;margin-top:14px">
    <small style="color:var(--text-3)"><?= $row['created_at'] ?></small>
    <a href="resolve_teacher_complaint.php?id=<?= $row['id'] ?>" class="btn green" style="display:inline-block;width:auto;padding:8px 20px">Mark Resolved</a>
  </div>
</div>
<?php endwhile; ?>
</div><script src="anim.js"></script></body></html>
