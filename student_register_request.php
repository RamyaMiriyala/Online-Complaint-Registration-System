<?php
include 'db.php'; $msg='';
if (isset($_POST['send'])) {
    $name=$conn->real_escape_string($_POST['name']); $email=$conn->real_escape_string($_POST['email']);
    $roll=$conn->real_escape_string($_POST['roll']); $pass=$conn->real_escape_string($_POST['password']);
    $secret=$conn->real_escape_string($_POST['secret']); $stream=$conn->real_escape_string($_POST['stream']); $section=$conn->real_escape_string($_POST['section']);
    $conn->query("INSERT INTO student_requests (name,roll_number,email,password,secret_answer,stream,section) VALUES ('$name','$roll','$email','$pass','$secret','$stream','$section')");
    $msg="Registration request sent! Await principal approval.";
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Request Account</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box"><a href="student_login.php" class="back-btn">← Back</a><h2>Registration Request</h2>
<?php if($msg) echo "<div class='msg-success'>$msg</div>"; ?>
<form method="post">
<input name="name" placeholder="Full name" required>
<input name="email" type="email" placeholder="Email" required>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
<select name="stream" required><option value="">Stream</option><option>BSc</option><option>BCA</option><option>BCom</option><option>BA</option><option>MSc</option><option>BBA</option><option>MCA</option></select>
<select name="section" required><option value="">Section</option><option>A1</option><option>A2</option><option>B</option><option>B1</option><option>B2</option><option>C</option><option>D</option><option>G</option><option>E</option><option>K</option></select>
</div>
<input name="roll" placeholder="Roll number" required>
<input name="password" type="password" placeholder="Password" required>
<input name="secret" placeholder="Secret answer (used for mailbox access)" required>
<button name="send" class="btn" style="margin-top:14px">Send Request</button>
</form>
</div><script src="anim.js"></script></body></html>
