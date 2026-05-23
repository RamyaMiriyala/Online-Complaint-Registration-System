<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php'; $msg='';
if (isset($_POST['register'])) {
    $name=$conn->real_escape_string($_POST['name']); $roll=$conn->real_escape_string($_POST['roll']); $email=$conn->real_escape_string($_POST['email']);
    $pass=$conn->real_escape_string($_POST['password']); $secret=$conn->real_escape_string($_POST['secret']);
    $stream=$conn->real_escape_string($_POST['stream']); $section=$conn->real_escape_string($_POST['section']);
    $conn->query("INSERT INTO students (name,roll_number,email,password,secret_answer,stream,section) VALUES ('$name','$roll','$email','$pass','$secret','$stream','$section')");
    $msg="Student registered successfully!";
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Register Student</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box"><a href="admin_dashboard.php" class="back-btn">← Back</a><h2>Register Student</h2>
<?php if($msg) echo "<div class='msg-success'>$msg</div>"; ?>
<form method="post">
<input name="name" placeholder="Full name" required>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
<select name="stream" required><option value="">Stream</option><option>BSc</option><option>BCA</option><option>BCom</option><option>BA</option><option>MSc</option><option>BBA</option><option>MCA</option></select>
<select name="section" required><option value="">Section</option><option>A1</option><option>A2</option><option>B</option><option>B1</option><option>B2</option><option>C</option><option>D</option><option>G</option><option>E</option><option>K</option></select>
</div>
<input name="roll" placeholder="Roll number" required>
<input name="email" type="email" placeholder="Email" required>
<input name="password" type="password" placeholder="Password" required>
<input name="secret" placeholder="Secret answer (for mailbox)" required>
<button name="register" class="btn" style="margin-top:14px">Register Student</button>
</form>
</div><script src="anim.js"></script></body></html>
