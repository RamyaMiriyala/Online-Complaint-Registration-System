<?php
session_start();
if (!isset($_SESSION['watchman'])) { header("Location: watchman_login.php"); exit; }
include 'db.php';

// Auto-expire old permissions
$conn->query("UPDATE permissions SET status='inactive' WHERE active_until < NOW() AND status='approved'");

$today = date('Y-m-d');
$todayFormatted = date('l, d F Y');

// Get today's approved permissions - both students and teachers
// A permission is "for today" if today falls between from_date and to_date AND it's approved
$query = " SELECT p.*, 
           CASE 
             WHEN p.role='student' THEN s.name
             WHEN p.role='teacher' THEN t.name
             ELSE p.user_email
           END AS person_name,
           CASE
             WHEN p.role='student' THEN s.roll_number
             ELSE t.department
           END AS extra_info,
           CASE
             WHEN p.role='student' THEN s.stream
             ELSE ''
           END AS stream_info,
           CASE
             WHEN p.role='student' THEN s.section
             ELSE ''
           END AS section_info
    FROM permissions p
    LEFT JOIN students s ON p.role='student' AND p.user_email = s.email
    LEFT JOIN teachers t ON p.role='teacher' AND p.user_email = t.email
    WHERE p.status = 'approved'
      AND p.from_date <= '$today'
      AND p.to_date   >= '$today'
    ORDER BY p.role, person_name
";

$perms = $conn->query($query);
$total = $perms->num_rows;

// Count by role
$students_count = $conn->query("SELECT COUNT(*) as c FROM permissions WHERE status='approved' AND role='student' AND from_date <= '$today' AND to_date >= '$today'")->fetch_assoc()['c'];
$teachers_count = $conn->query("SELECT COUNT(*) as c FROM permissions WHERE status='approved' AND role='teacher' AND from_date <= '$today' AND to_date >= '$today'")->fetch_assoc()['c'];
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Watchman Dashboard — EduPlex</title>
<link rel="stylesheet" href="style.css">
<meta http-equiv="refresh" content="120"><!-- Auto refresh every 2 minutes -->
</head>
<body class="theme ep-dashboard"><div class="main-box" style="max-width:1100px">
<a href="logout_watchman.php" class="back-btn">← Sign Out</a>

<h2>Gate Permissions</h2>

<!-- Today banner -->
<div class="today-banner">
  <div class="day-text">📅 <?= $todayFormatted ?></div>
  <div class="day-sub">Showing approved permissions active today · Auto-refreshes every 2 minutes</div>
</div>

<!-- Stats row -->
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:22px">
  <div class="card" style="text-align:center;padding:16px">
    <div style="font-size:1.8rem;font-weight:700;color:var(--blue)"><?= $total ?></div>
    <div style="font-size:.8rem;color:var(--text-2);margin-top:4px">Total Today</div>
  </div>
  <div class="card" style="text-align:center;padding:16px">
    <div style="font-size:1.8rem;font-weight:700;color:var(--blue-light)"><?= $students_count ?></div>
    <div style="font-size:.8rem;color:var(--text-2);margin-top:4px">Students</div>
  </div>
  <div class="card" style="text-align:center;padding:16px">
    <div style="font-size:1.8rem;font-weight:700;color:var(--green)"><?= $teachers_count ?></div>
    <div style="font-size:.8rem;color:var(--text-2);margin-top:4px">Teachers</div>
  </div>
</div>

<?php if ($total === 0): ?>
<div class="empty-state">
  <div class="empty-icon">✅</div>
  <p>No approved permissions for today.</p>
</div>
<?php else: ?>

<!-- Table view -->
<div class="table-wrap">
<table class="data-table">
<thead>
<tr>
  <th>Role</th>
  <th>Name</th>
  <th>Details</th>
  <th>Category</th>
  <th>Reason</th>
  <th>From</th>
  <th>To</th>
  <th>Active Until</th>
</tr>
</thead>
<tbody>
<?php while ($p = $perms->fetch_assoc()): ?>
<tr>
  <td><span class="role-tag <?= $p['role'] ?>"><?= ucfirst($p['role']) ?></span></td>
  <td><strong><?= htmlspecialchars($p['person_name'] ?: $p['user_email']) ?></strong></td>
  <td style="font-size:.82rem;color:var(--text-2)">
    <?php if ($p['role'] === 'student'): ?>
      Roll: <?= htmlspecialchars($p['extra_info']) ?>
      <?php if ($p['stream_info']): ?><br><?= htmlspecialchars($p['stream_info']) ?> – <?= htmlspecialchars($p['section_info']) ?><?php endif; ?>
    <?php else: ?>
      <?= htmlspecialchars($p['extra_info']) ?>
    <?php endif; ?>
  </td>
  <td><span class="badge approved"><?= htmlspecialchars($p['category']) ?></span></td>
  <td style="max-width:180px;font-size:.85rem;color:var(--text-2)"><?= nl2br(htmlspecialchars($p['reason'])) ?></td>
  <td style="font-family:'DM Mono',monospace;font-size:.82rem"><?= $p['from_date'] ?></td>
  <td style="font-family:'DM Mono',monospace;font-size:.82rem"><?= $p['to_date'] ?></td>
  <td style="font-family:'DM Mono',monospace;font-size:.82rem;color:var(--gold)"><?= $p['active_until'] ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

<?php endif; ?>

<div style="text-align:center;margin-top:20px">
  <a href="watchman_dashboard.php" class="btn ghost" style="display:inline-block;width:auto;padding:9px 22px">🔄 Refresh Now</a>
</div>

</div><script src="anim.js"></script></body></html>
