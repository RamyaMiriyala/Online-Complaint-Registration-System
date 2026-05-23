<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
if (isset($_GET['del'])) { $id=intval($_GET['del']); $conn->query("DELETE FROM complaints WHERE id=$id AND status='Resolved'"); header("Location: teacher_resolved.php"); exit; }
$data = $conn->query("SELECT c.id, c.message, c.category, c.created_at, t.name, t.department FROM complaints c JOIN teachers t ON c.sender_email = t.email WHERE c.status = 'Resolved' ORDER BY c.id DESC");
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Teacher Resolved — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box">
<a href="admin_dashboard.php" class="back-btn">← Back</a>
<h2>Resolved Teacher Complaints</h2>
<?php if ($data->num_rows === 0): ?><div class="empty-state"><div class="empty-icon">📭</div><p>No resolved complaints yet.</p></div>
<?php else: ?>
<div class="table-wrap"><table class="data-table">
<thead><tr><th>#</th><th>Teacher</th><th>Category</th><th>Complaint</th><th>Date</th><th></th></tr></thead>
<tbody>
<?php while ($row = $data->fetch_assoc()): ?>
<tr>
  <td style="font-family:'DM Mono',monospace;color:var(--text-3)"><?= $row['id'] ?></td>
  <td><strong><?= htmlspecialchars($row['name']) ?></strong><br><small style="color:var(--text-2)"><?= htmlspecialchars($row['department']) ?></small></td>
  <td><span class="badge approved" style="font-size:.72rem"><?= htmlspecialchars($row['category']) ?></span></td>
  <td style="max-width:200px"><div class="msg-box" style="font-size:.83rem"><?= nl2br(htmlspecialchars($row['message'])) ?></div></td>
  <td style="font-size:.82rem;color:var(--text-2)"><?= $row['created_at'] ?></td>
  <td><a href="teacher_resolved.php?del=<?= $row['id'] ?>" class="btn red" style="display:inline-block;width:auto;padding:5px 12px;font-size:.8rem" onclick="return confirm('Delete?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</tbody></table></div>
<?php endif; ?>
</div><script src="anim.js"></script></body></html>
