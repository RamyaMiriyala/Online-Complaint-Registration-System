<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php'; $msg='';
if (isset($_POST['add'])) {
    $name=$conn->real_escape_string($_POST['name']); $email=$conn->real_escape_string($_POST['email']);
    $pass=$conn->real_escape_string($_POST['password']); $dept=$conn->real_escape_string($_POST['dept']);
    $check=$conn->query("SELECT id FROM teachers WHERE email='$email'");
    if ($check->num_rows>0) { $msg="ERROR:A teacher with this email already exists."; }
    else { $conn->query("INSERT INTO teachers (name,email,password,department) VALUES ('$name','$email','$pass','$dept')"); $msg="Teacher registered successfully!"; }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Register Teacher</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box"><a href="admin_dashboard.php" class="back-btn">← Back</a><h2>Register Teacher</h2>
<?php if($msg) echo strpos($msg,'ERROR')!==false?"<div class='msg-error'>".substr($msg,6)."</div>":"<div class='msg-success'>$msg</div>"; ?>
<form method="post">
<input name="name" placeholder="Full name" required>
<input name="email" type="email" placeholder="Email" required>
<select name="dept" required><option value="">Select Department</option><option>Computer Science</option><option>Mathematics</option><option>Physics</option><option>Chemistry</option><option>Electronics</option><option>Mechanical</option><option>Civil</option><option>Commerce</option><option>Management</option></select>
<input name="password" type="password" placeholder="Password" required>
<button name="add" class="btn" style="margin-top:14px">Register Teacher</button>
</form>
</div><script src="anim.js"></script></body></html>
