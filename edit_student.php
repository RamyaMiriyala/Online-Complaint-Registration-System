<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
if (!isset($_GET['id'])) { header("Location: all_students.php"); exit; }
$id=intval($_GET['id']);
if (isset($_POST['update'])) {
    $name=$conn->real_escape_string($_POST['name']); $roll=$conn->real_escape_string($_POST['roll']); $email=$conn->real_escape_string($_POST['email']);
    $pass=$conn->real_escape_string($_POST['password']); $secret=$conn->real_escape_string($_POST['secret']); $stream=$conn->real_escape_string($_POST['stream']); $section=$conn->real_escape_string($_POST['section']);
    if ($pass!='') $conn->query("UPDATE students SET name='$name',roll_number='$roll',email='$email',password='$pass',secret_answer='$secret',stream='$stream',section='$section' WHERE id=$id");
    else $conn->query("UPDATE students SET name='$name',roll_number='$roll',email='$email',secret_answer='$secret',stream='$stream',section='$section' WHERE id=$id");
    header("Location: all_students.php"); exit;
}
$r=$conn->query("SELECT * FROM students WHERE id=$id")->fetch_assoc();
if (!$r) { header("Location: all_students.php"); exit; }
$streams=["BSc","BCA","BCom","BA","MSc","BBA","MCA"]; $secs=["A1","A2","B","B1","B2","C","D","G","E","K"];
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Edit Student</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box"><a href="all_students.php" class="back-btn">← Back</a><h2>Edit Student</h2>
<form method="post">
<input name="name" value="<?= htmlspecialchars($r['name']) ?>" placeholder="Full name" required>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
<select name="stream" required><?php foreach($streams as $s) echo "<option".($r['stream']==$s?' selected':'').">$s</option>"; ?></select>
<select name="section" required><?php foreach($secs as $s) echo "<option".($r['section']==$s?' selected':'').">$s</option>"; ?></select>
</div>
<input name="roll" value="<?= htmlspecialchars($r['roll_number']) ?>" placeholder="Roll number" required>
<input name="email" type="email" value="<?= htmlspecialchars($r['email']) ?>" required>
<input name="password" type="password" placeholder="New password (leave blank to keep)">
<input name="secret" value="<?= htmlspecialchars($r['secret_answer']) ?>" placeholder="Secret answer" required>
<button name="update" class="btn" style="margin-top:14px">Save Changes</button>
</form>
</div><script src="anim.js"></script></body></html>
