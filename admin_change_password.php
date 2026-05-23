<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
$email = $conn->real_escape_string($_SESSION['admin']);
$msg = '';
if (isset($_POST['change'])) {
    $new = $conn->real_escape_string($_POST['new']);
    $conn->query("UPDATE admins SET password='$new' WHERE email='$email'");
    $msg = "Password updated successfully.";
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Change Password — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box">
<a href="admin_dashboard.php" class="back-btn">← Back</a>
<h2>Change Password</h2>
<?php if($msg) echo "<div class='msg-success'>$msg</div>"; ?>
<form method="post">
<input name="new" type="password" placeholder="New password" required>
<button name="change" class="btn" style="margin-top:14px">Update Password</button>
</form>
</div><script src="anim.js"></script></body></html>
