<?php
$conn = new mysqli("localhost", "root", "", "complaint_system");
if ($conn->connect_error) {
    die("<div style='font-family:monospace;color:#ff4a6e;padding:20px'>⚠ Database connection failed: " . $conn->connect_error . "</div>");
}
$conn->set_charset("utf8mb4");
?>
