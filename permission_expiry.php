<?php
include 'db.php';
$conn->query("UPDATE permissions SET status='inactive' WHERE active_until < NOW() AND status='approved'");
echo "Expiry check done.";
?>
