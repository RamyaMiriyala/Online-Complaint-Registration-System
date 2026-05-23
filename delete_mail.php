<?php
session_start();
if (!isset($_SESSION['mail_verified']) || $_SESSION['mail_verified'] !== true) {
    header("Location: student_mailbox_login.php");
    exit;
}
include 'db.php';
if (!isset($_GET['id'])) { header("Location: student_mailbox.php"); exit; }
$id    = intval($_GET['id']);
$email = $conn->real_escape_string($_SESSION['mail_email']);
$conn->query("DELETE FROM mailbox WHERE id=$id AND receiver_email='$email'");
header("Location: student_mailbox.php");
exit;
?>
