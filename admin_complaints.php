<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
$data = $conn->query("SELECT c.id, c.message, c.category, c.created_at, s.name, s.roll_number, s.section, s.email FROM complaints c JOIN students s ON c.student_email = s.email WHERE c.status = 'pending' ORDER BY c.id DESC");
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Student Complaints — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box">
<a href="admin_dashboard.php" class="back-btn">← Back</a>
<h2>Pending Student Complaints</h2>
<?php if ($data->num_rows === 0): ?>
<div class="empty-state"><div class="empty-icon">✅</div><p>No pending complaints.</p></div>
<?php endif; ?>
<?php while ($row = $data->fetch_assoc()): ?>
<div class="card">
  <div style="display:flex;flex-wrap:wrap;gap:16px;font-size:.85rem;margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid var(--border)">
    <span><span style="color:var(--text-2)">Name:</span> <strong><?= htmlspecialchars($row['name']) ?></strong></span>
    <span><span style="color:var(--text-2)">Roll:</span> <?= htmlspecialchars($row['roll_number']) ?></span>
    <span><span style="color:var(--text-2)">Section:</span> <?= htmlspecialchars($row['section']) ?></span>
    <span><span style="color:var(--text-2)">Category:</span> <span style="color:var(--blue-light)"><?= htmlspecialchars($row['category']) ?></span></span>
  </div>
  <div class="msg-box"><?= nl2br(htmlspecialchars($row['message'])) ?></div>
  <div style="display:flex;justify-content:space-between;align-items:center;margin-top:14px">
    <small style="color:var(--text-3)"><?= $row['created_at'] ?></small>
    <a href="resolve_complaint.php?id=<?= $row['id'] ?>" class="btn green" style="display:inline-block;width:auto;padding:8px 20px">Mark Resolved</a>
  </div>
</div>
<?php endwhile; ?>
</div><script src="anim.js"></script></body></html>
