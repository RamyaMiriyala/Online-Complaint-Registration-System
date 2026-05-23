<?php
session_start(); include 'db.php'; $_SESSION['mail_verified']=false; $err='';
if (isset($_POST['check'])) {
    $email=$conn->real_escape_string($_POST['email']); $ans=$conn->real_escape_string($_POST['answer']);
    $q=$conn->query("SELECT * FROM students WHERE email='$email' AND secret_answer='$ans'");
    if ($q->num_rows>0) { $_SESSION['mail_verified']=true; $_SESSION['mail_email']=$email; header("Location: student_mailbox.php"); exit; }
    else { $err="Wrong email or secret answer."; }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Mailbox Login</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box"><a href="student_login.php" class="back-btn">← Back</a><h2>Student Mailbox</h2>
<p style="text-align:center;color:var(--text-2);font-size:.88rem;margin:-18px 0 20px">Enter your email and secret answer to access your mailbox</p>
<?php if($err) echo "<div class='msg-error'>$err</div>"; ?>
<form method="post"><input name="email" type="email" placeholder="Email" required><input name="answer" placeholder="Secret Answer" required><button name="check" class="btn" style="margin-top:14px">Open Mailbox</button></form>
</div><script src="anim.js"></script></body></html>
