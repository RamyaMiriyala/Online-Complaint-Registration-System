<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
$search = $conn->real_escape_string($_GET['search'] ?? '');
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>All Students — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box">
<a href="admin_dashboard.php" class="back-btn">← Back</a>
<h2>All Students</h2>
<form method="get" style="margin-bottom:16px">
<input name="search" placeholder="Search by name, roll, email, stream, section..." value="<?= htmlspecialchars($search) ?>">
</form>
<?php
$q = $search !== '' 
  ? $conn->query("SELECT * FROM students WHERE name LIKE '%$search%' OR roll_number LIKE '%$search%' OR email LIKE '%$search%' OR stream LIKE '%$search%' OR section LIKE '%$search%'")
  : $conn->query("SELECT * FROM students ORDER BY name");
if ($q->num_rows === 0) echo "<div class='empty-state'><div class='empty-icon'>🔍</div><p>No students found.</p></div>";
while ($r = $q->fetch_assoc()):
?>
<div class="card student-row">
<div>
  <strong><?= htmlspecialchars($r['name']) ?></strong>
  <span class="badge approved" style="margin-left:8px;font-size:.72rem"><?= htmlspecialchars($r['stream']) ?> · <?= htmlspecialchars($r['section']) ?></span><br>
  <small style="color:var(--text-2)">Roll <?= htmlspecialchars($r['roll_number']) ?> &nbsp;·&nbsp; <?= htmlspecialchars($r['email']) ?></small>
</div>
<div class="icon-actions">
  <a href="edit_student.php?id=<?= $r['id'] ?>" class="icon-btn icon-edit" title="Edit">✏</a>
  <a href="delete_student.php?id=<?= $r['id'] ?>" class="icon-btn icon-delete" title="Delete" onclick="return confirm('Delete <?= htmlspecialchars($r['name']) ?>?')">🗑</a>
</div>
</div>
<?php endwhile; ?>
</div><script src="anim.js"></script></body></html>
