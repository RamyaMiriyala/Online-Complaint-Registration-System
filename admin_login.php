<?php
session_start();
include 'db.php';
$err = '';
if (isset($_POST['login'])) {
    $e = $conn->real_escape_string($_POST['email']);
    $p = $conn->real_escape_string($_POST['password']);
    $q = $conn->query("SELECT * FROM admins WHERE email='$e' AND password='$p'");
    if ($q->num_rows > 0) {
        $_SESSION['admin'] = $e;
        header("Location: admin_dashboard.php"); exit;
    } else { $err = "Invalid email or password."; }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Principal Login — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-login"><div class="main-box">
<a href="index.php" class="back-btn">← Back</a>
<h2>Principal Login</h2>
<?php if($err) echo "<div class='msg-error'>$err</div>"; ?>
<form method="post">
<input name="email" type="email" placeholder="Email address" required autocomplete="off">
<input name="password" type="password" placeholder="Password" required>
<button name="login" class="btn" style="margin-top:14px">Sign In</button>
</form>
</div><script src="anim.js"></script></body></html>
