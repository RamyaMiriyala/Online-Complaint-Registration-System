<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
$data = $conn->query("SELECT * FROM student_requests ORDER BY id DESC");
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Student Requests — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box">
<a href="admin_dashboard.php" class="back-btn">← Back</a>
<h2>Student Registration Requests</h2>
<?php if ($data->num_rows === 0): ?><div class="empty-state"><div class="empty-icon">📋</div><p>No pending requests.</p></div><?php endif; ?>
<?php while ($row = $data->fetch_assoc()): ?>
<div class="card">
  <div style="display:flex;flex-wrap:wrap;gap:16px;font-size:.88rem;margin-bottom:14px">
    <span><span style="color:var(--text-2)">Name:</span> <strong><?= htmlspecialchars($row['name']) ?></strong></span>
    <span><span style="color:var(--text-2)">Roll:</span> <?= htmlspecialchars($row['roll_number']) ?></span>
    <span><span style="color:var(--text-2)">Email:</span> <?= htmlspecialchars($row['email']) ?></span>
    <span><span style="color:var(--text-2)">Stream:</span> <?= htmlspecialchars($row['stream']) ?></span>
    <span><span style="color:var(--text-2)">Section:</span> <?= htmlspecialchars($row['section']) ?></span>
  </div>
  <div style="display:flex;gap:10px">
    <a href="approve_student.php?id=<?= $row['id'] ?>" class="btn green" style="flex:1;text-align:center">✅ Approve</a>
    <a href="reject_student.php?id=<?= $row['id'] ?>" class="btn red" style="flex:1;text-align:center">❌ Reject</a>
  </div>
</div>
<?php endwhile; ?>
</div><script src="anim.js"></script></body></html>
