<?php
include 'db.php'; $msg='';
if (isset($_POST['send'])) {
    $name=$conn->real_escape_string($_POST['name']); $email=$conn->real_escape_string($_POST['email']);
    $pass=$conn->real_escape_string($_POST['password']); $dept=$conn->real_escape_string($_POST['department']);
    $conn->query("INSERT INTO teacher_requests (name,email,password,department) VALUES ('$name','$email','$pass','$dept')");
    $msg="Request sent! Await principal approval.";
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Request Account</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box"><a href="teacher_login.php" class="back-btn">← Back</a><h2>Teacher Account Request</h2>
<?php if($msg) echo "<div class='msg-success'>$msg</div>"; ?>
<form method="post">
<input name="name" placeholder="Full name" required>
<input name="email" type="email" placeholder="Email" required>
<select name="department" required><option value="">Select Department</option><option>Computer Science</option><option>Mathematics</option><option>Physics</option><option>Chemistry</option><option>Electronics</option><option>Mechanical</option><option>Civil</option><option>Commerce</option><option>Management</option></select>
<input name="password" type="password" placeholder="Password" required>
<button name="send" class="btn" style="margin-top:14px">Send Request</button>
</form>
</div><script src="anim.js"></script></body></html>
