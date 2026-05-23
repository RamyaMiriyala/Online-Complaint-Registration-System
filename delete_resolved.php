<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include 'db.php';
if (!isset($_GET['id'])) { header("Location: admin_dashboard.php"); exit; }
$id = intval($_GET['id']);
$conn->query("DELETE FROM complaints WHERE id=$id AND status='Resolved' LIMIT 1");
echo "<script>history.back();</script>";
exit;
?>
