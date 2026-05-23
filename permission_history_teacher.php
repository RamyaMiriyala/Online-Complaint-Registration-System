<?php
session_start();
if (!isset($_SESSION['teacher'])) { header("Location: teacher_login.php"); exit; }
include 'db.php';
$email = $conn->real_escape_string($_SESSION['teacher']);
$q = $conn->query("SELECT * FROM permissions WHERE user_email='$email' AND role='teacher' ORDER BY id DESC");
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Permission History — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box">
<a href="teacher_dashboard.php" class="back-btn">← Back</a>
<h2>Permission History</h2>

<?php if ($q->num_rows === 0): ?>
<div class="empty-state"><div class="empty-icon">📄</div><p>You haven't submitted any permission requests yet.</p></div>
<?php else: ?>

<div class="table-wrap">
<table class="data-table">
<thead>
<tr>
  <th>Category</th>
  <th>Reason</th>
  <th>From</th>
  <th>To</th>
  <th>Active Until</th>
  <th>Status</th>
  <th>Submitted</th>
</tr>
</thead>
<tbody>
<?php while ($p = $q->fetch_assoc()): ?>
<tr>
  <td><strong><?= htmlspecialchars($p['category']) ?></strong></td>
  <td style="max-width:200px;color:var(--text-2);font-size:.85rem"><?= nl2br(htmlspecialchars($p['reason'])) ?></td>
  <td style="font-family:'DM Mono',monospace;font-size:.82rem"><?= $p['from_date'] ?></td>
  <td style="font-family:'DM Mono',monospace;font-size:.82rem"><?= $p['to_date'] ?></td>
  <td style="font-family:'DM Mono',monospace;font-size:.82rem"><?= $p['active_until'] ?></td>
  <td><span class="badge <?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
  <td style="font-size:.82rem;color:var(--text-2)"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

<?php endif; ?>
</div><script src="anim.js"></script></body></html>
