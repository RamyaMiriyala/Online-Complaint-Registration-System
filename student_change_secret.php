<?php
session_start();
if (!isset($_SESSION['student'])) { header("Location: student_login.php"); exit; }
include 'db.php';
$email=$conn->real_escape_string($_SESSION['student']); $msg='';
if (isset($_POST['save'])) { $new=$conn->real_escape_string($_POST['secret']); $conn->query("UPDATE students SET secret_answer='$new' WHERE email='$email'"); $msg="Secret answer updated."; }
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Change Secret</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box"><a href="student_dashboard.php" class="back-btn">← Back</a><h2>Change Secret Answer</h2>
<?php if($msg) echo "<div class='msg-success'>$msg</div>"; ?>
<form method="post"><input name="secret" placeholder="New secret answer" required><button name="save" class="btn" style="margin-top:14px">Save Secret</button></form>
</div><script src="anim.js"></script></body></html>
