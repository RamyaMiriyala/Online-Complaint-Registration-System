<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
if (!isset($_GET['id'])) { header("Location: admin_all_teachers.php"); exit; }
$id=intval($_GET['id']);
if (isset($_POST['save'])) {
    $name=$conn->real_escape_string($_POST['name']); $email=$conn->real_escape_string($_POST['email']); $dept=$conn->real_escape_string($_POST['dept']);
    $conn->query("UPDATE teachers SET name='$name',email='$email',department='$dept' WHERE id=$id");
    header("Location: admin_all_teachers.php"); exit;
}
$t=$conn->query("SELECT * FROM teachers WHERE id=$id")->fetch_assoc();
if (!$t) { header("Location: admin_all_teachers.php"); exit; }
$depts=["Computer Science","Mathematics","Physics","Chemistry","Electronics","Mechanical","Civil","Commerce","Management"];
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Edit Teacher</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box"><a href="admin_all_teachers.php" class="back-btn">← Back</a><h2>Edit Teacher</h2>
<form method="post">
<input name="name" value="<?= htmlspecialchars($t['name']) ?>" placeholder="Full name" required>
<input name="email" type="email" value="<?= htmlspecialchars($t['email']) ?>" required>
<select name="dept" required><?php foreach($depts as $d) echo "<option".($t['department']==$d?' selected':'').">$d</option>"; ?></select>
<button name="save" class="btn" style="margin-top:14px">Save Changes</button>
</form>
</div><script src="anim.js"></script></body></html>
