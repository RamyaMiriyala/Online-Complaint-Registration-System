<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
if (!isset($_GET['email'])) { header("Location: password_requests.php"); exit; }
$email=$conn->real_escape_string($_GET['email']); $msg='';
if (isset($_POST['save'])) {
    $new=$conn->real_escape_string($_POST['new']);
    $conn->query("UPDATE students SET password='$new' WHERE email='$email'");
    $msgText="Your password has been reset by the principal.\nNew Password: $new";
    $conn->query("INSERT INTO mailbox (receiver_email, message) VALUES ('$email','$msgText')");
    $conn->query("DELETE FROM password_resets WHERE email='$email'");
    $msg="Password updated and sent to student mailbox.";
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Reset Password</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box"><a href="password_requests.php" class="back-btn">← Back</a><h2>Reset Student Password</h2>
<div class="card" style="margin-bottom:16px"><small style="color:var(--text-2);font-size:.78rem;text-transform:uppercase">Student Email</small><div style="font-family:'DM Mono',monospace;margin-top:3px"><?= htmlspecialchars($email) ?></div></div>
<?php if($msg) echo "<div class='msg-success'>$msg</div>"; ?>
<form method="post"><input name="new" type="password" placeholder="New password" required><button name="save" class="btn" style="margin-top:14px">Save & Notify Student</button></form>
</div><script src="anim.js"></script></body></html>
