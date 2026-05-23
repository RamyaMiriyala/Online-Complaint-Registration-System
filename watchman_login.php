<?php
session_start();
include 'db.php';
$err = '';
if (isset($_POST['login'])) {
    $u = $conn->real_escape_string($_POST['username']);
    $p = $conn->real_escape_string($_POST['password']);
    $q = $conn->query("SELECT * FROM watchmen WHERE username='$u' AND password='$p'");
    if ($q->num_rows > 0) {
        $w = $q->fetch_assoc();
        $_SESSION['watchman']      = $w['id'];
        $_SESSION['watchman_name'] = $w['name'];
        header("Location: watchman_dashboard.php"); exit;
    } else { $err = "Invalid credentials."; }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Watchman Login — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-login"><div class="main-box">
<a href="index.php" class="back-btn">← Back</a>
<h2>Watchman Login</h2>
<p style="text-align:center;color:var(--text-2);font-size:.88rem;margin-top:-18px;margin-bottom:22px">Gate & permission monitoring portal</p>
<?php if($err) echo "<div class='msg-error'>$err</div>"; ?>
<form method="post">
<input name="username" placeholder="Username" required autocomplete="off">
<input name="password" type="password" placeholder="Password" required>
<button name="login" class="btn" style="margin-top:14px">Sign In</button>
</form>
</div><script src="anim.js"></script></body></html>
