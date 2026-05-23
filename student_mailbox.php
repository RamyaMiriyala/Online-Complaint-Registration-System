<?php
session_start(); include 'db.php';
if (!isset($_SESSION['mail_verified']) || $_SESSION['mail_verified']!==true) { header("Location: student_mailbox_login.php"); exit; }
$email=$conn->real_escape_string($_SESSION['mail_email']);
$q=$conn->query("SELECT * FROM mailbox WHERE receiver_email='$email' ORDER BY id DESC");
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>My Mailbox</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box"><a href="close_mailbox.php" class="back-btn">← Close</a><h2>My Mailbox</h2>
<?php if ($q->num_rows===0): ?><div class="empty-state"><div class="empty-icon">📭</div><p>Your mailbox is empty.</p></div><?php endif; ?>
<?php while ($r=$q->fetch_assoc()): ?>
<div class="card"><div class="msg-box"><?= nl2br(htmlspecialchars($r['message'])) ?></div>
<div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px">
<small style="color:var(--text-3)"><?= $r['created_at'] ?></small>
<a href="delete_mail.php?id=<?= $r['id'] ?>" class="btn red" style="display:inline-block;width:auto;padding:6px 14px;font-size:.82rem">Delete</a>
</div></div>
<?php endwhile; ?>
</div><script src="anim.js"></script></body></html>
