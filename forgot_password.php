<?php
include 'db.php'; $msg=''; $err='';
if (isset($_POST['send'])) {
    $email=$conn->real_escape_string($_POST['email']);
    $check=$conn->query("SELECT * FROM students WHERE email='$email'");
    if ($check->num_rows>0) { $conn->query("INSERT INTO password_resets (email,role) VALUES ('$email','student')"); $msg="Reset request sent. Check your mailbox after the principal processes it."; }
    else { $err="This email is not registered."; }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Forgot Password</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box"><a href="student_login.php" class="back-btn">← Back</a><h2>Forgot Password</h2>
<?php if($msg) echo "<div class='msg-success'>$msg</div>"; if($err) echo "<div class='msg-error'>$err</div>"; ?>
<form method="post"><input name="email" type="email" placeholder="Your registered email" required><button name="send" class="btn" style="margin-top:14px">Send Reset Request</button></form>
</div><script src="anim.js"></script></body></html>
