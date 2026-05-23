<?php
session_start();
include 'db.php';
$err = '';
if (isset($_POST['login'])) {
    $e = $conn->real_escape_string($_POST['email']);
    $p = $conn->real_escape_string($_POST['password']);
    $q = $conn->query("SELECT * FROM teachers WHERE email='$e' AND password='$p'");
    if ($q->num_rows > 0) {
        $_SESSION['teacher'] = $e;
        header("Location: teacher_dashboard.php"); exit;
    } else { $err = "Invalid email or password."; }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Teacher Login — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-login"><div class="main-box">
<a href="index.php" class="back-btn">← Back</a>
<h2>Teacher Login</h2>
<?php if($err) echo "<div class='msg-error'>$err</div>"; ?>
<form method="post">
<input name="email" type="email" placeholder="Teacher email" required autocomplete="off">
<input name="password" type="password" placeholder="Password" required>
<button name="login" class="btn" style="margin-top:14px">Sign In</button>
</form>
<a href="teacher_register_request.php" class="small-btn">Request account</a>
</div><script src="anim.js"></script></body></html>
