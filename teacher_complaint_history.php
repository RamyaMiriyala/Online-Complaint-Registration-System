<?php
session_start();
if (!isset($_SESSION['teacher'])) { header("Location: teacher_login.php"); exit; }
include 'db.php';
$email=$conn->real_escape_string($_SESSION['teacher']);
$q=$conn->query("SELECT * FROM complaints WHERE sender_email='$email' ORDER BY id DESC");
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Complaint History</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box"><a href="teacher_dashboard.php" class="back-btn">← Back</a><h2>Complaint History</h2>
<?php if ($q->num_rows===0): ?><div class="empty-state"><div class="empty-icon">📭</div><p>No complaints submitted yet.</p></div><?php endif; ?>
<?php while ($r=$q->fetch_assoc()): $cls=strtolower($r['status'])==='resolved'?'resolved':'pending'; ?>
<div class="card">
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
<strong><?= htmlspecialchars($r['category']) ?></strong><span class="badge <?= $cls ?>"><?= htmlspecialchars($r['status']) ?></span>
</div>
<div class="msg-box"><?= nl2br(htmlspecialchars($r['message'])) ?></div>
<small style="color:var(--text-3);display:block;margin-top:8px"><?= $r['created_at'] ?></small>
</div>
<?php endwhile; ?>
</div><script src="anim.js"></script></body></html>
