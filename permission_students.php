<?php
session_start();
if (!isset($_SESSION['student'])) { header("Location: student_login.php"); exit; }
include 'db.php';
$email = $conn->real_escape_string($_SESSION['student']);
$msg = '';
if (isset($_POST['send'])) {
    $cat    = $conn->real_escape_string($_POST['category']);
    $reason = $conn->real_escape_string($_POST['reason']);
    $from   = $conn->real_escape_string($_POST['from']);
    $to     = $conn->real_escape_string($_POST['to']);
    $days   = intval($_POST['days']);
    $active_until = date("Y-m-d H:i:s", strtotime("+$days days"));
    $conn->query("INSERT INTO permissions (user_email, role, category, reason, from_date, to_date, active_until) VALUES ('$email','student','$cat','$reason','$from','$to','$active_until')");
    $msg = "Permission request submitted successfully.";
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Request Permission — EduPlex</title><link rel="stylesheet" href="style.css"></head>
<body class="theme ep-inner"><div class="main-box">
<a href="student_dashboard.php" class="back-btn">← Back</a>
<h2>Request Permission</h2>
<?php if($msg) echo "<div class='msg-success'>$msg</div>"; ?>
<form method="post">
<label>Category</label>
<select name="category" required>
  <option value="">Select type</option>
  <option>Leave</option><option>Medical</option><option>Workshop</option>
  <option>Seminar</option><option>Late Coming</option><option>Other</option>
</select>
<label>Reason</label>
<textarea name="reason" placeholder="Explain your reason..." required></textarea>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
  <div><label>From Date</label><input type="date" name="from" required></div>
  <div><label>To Date</label><input type="date" name="to" required></div>
</div>
<label>Active Days (how many days should permission stay active)</label>
<input type="number" name="days" min="1" placeholder="e.g. 3" required>
<button name="send" class="btn" style="margin-top:16px">Submit Request</button>
</form>
<div style="margin-top:14px;text-align:center">
  <a href="permission_history_student.php" class="small-btn" style="width:auto!important;display:inline-flex!important">View Permission History</a>
</div>
</div><script src="anim.js"></script></body></html>
