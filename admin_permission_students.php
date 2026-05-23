<?php
session_start();
if (!isset($_SESSION['admin'])) { 
    header("Location: admin_login.php"); 
    exit; 
}

include 'db.php';

if (isset($_GET['approve'])) { 
    $id = intval($_GET['approve']); 
    $conn->query("UPDATE permissions SET status='approved' WHERE id=$id"); 
    header("Location: admin_permission_students.php"); 
    exit; 
}

if (isset($_GET['reject'])) {  
    $id = intval($_GET['reject']);  
    $conn->query("UPDATE permissions SET status='rejected' WHERE id=$id");  
    header("Location: admin_permission_students.php"); 
    exit; 
}

$data = $conn->query("
    SELECT p.*, s.name, s.roll_number, s.stream, s.section 
    FROM permissions p 
    LEFT JOIN students s ON p.user_email=s.email 
    WHERE p.role='student' 
    ORDER BY p.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Student Permissions — EduPlex</title>
<link rel="stylesheet" href="style.css">

<style>
.clickable-row {
    cursor: pointer;
    transition: 0.2s;
}
.clickable-row:hover {
    background-color: #489aa0;
}
.preview-row {
    animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
    from {opacity: 0;}
    to {opacity: 1;}
}
.preview-content {
    background: #489aa0;
    padding: 15px;
    border-left: 4px solid #4a90e2;
}
</style>

</head>

<body class="theme ep-inner">
<div class="main-box">

<a href="admin_dashboard.php" class="back-btn">← Back</a>

<h2>Student Permission Requests</h2>

<?php if ($data->num_rows === 0): ?>
<div class="empty-state">
    <div class="empty-icon">📄</div>
    <p>No permission requests.</p>
</div>
<?php else: ?>

<div class="table-wrap">
<table class="data-table">
<thead>
<tr>
    <th>Student</th>
    <th>Category</th>
    <th>Reason</th>
    <th>From</th>
    <th>To</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php while ($p = $data->fetch_assoc()): ?>

<tr class="clickable-row" onclick="toggleRow(<?= $p['id'] ?>)">
    <td>
        <strong><?= htmlspecialchars($p['name'] ?: $p['user_email']) ?></strong><br>
        <small style="color:var(--text-2)">
            <?= htmlspecialchars($p['roll_number'] ?? '') ?> · 
            <?= htmlspecialchars($p['stream'] ?? '') ?> 
            <?= htmlspecialchars($p['section'] ?? '') ?>
        </small>
    </td>

    <td><?= htmlspecialchars($p['category']) ?></td>

    <td style="font-size:.83rem;color:var(--text-2)">
        Click to preview
    </td>

    <td style="font-family:'DM Mono',monospace;font-size:.82rem">
        <?= $p['from_date'] ?>
    </td>

    <td style="font-family:'DM Mono',monospace;font-size:.82rem">
        <?= $p['to_date'] ?>
    </td>

    <td>
        <span class="badge <?= $p['status'] ?>">
            <?= ucfirst($p['status']) ?>
        </span>
    </td>

    <td style="white-space:nowrap">
        <?php if ($p['status'] === 'pending'): ?>
            <a href="?approve=<?= $p['id'] ?>" 
               class="btn green" 
               style="display:inline-block;width:auto;padding:5px 12px;font-size:.8rem;margin:2px"
               onclick="event.stopPropagation();">
               Approve
            </a>

            <a href="?reject=<?= $p['id'] ?>"  
               class="btn red"   
               style="display:inline-block;width:auto;padding:5px 12px;font-size:.8rem;margin:2px"
               onclick="event.stopPropagation();">
               Reject
            </a>
        <?php else: ?>
            <span style="color:var(--text-3);font-size:.82rem">—</span>
        <?php endif; ?>
    </td>
</tr>

<!-- Hidden Preview Row -->
<tr id="preview-<?= $p['id'] ?>" class="preview-row" style="display:none;">
    <td colspan="7">
        <div class="preview-content">
            <strong>Full Reason:</strong><br><br>
            <?= nl2br(htmlspecialchars($p['reason'])) ?>
        </div>
    </td>
</tr>

<?php endwhile; ?>

</tbody>
</table>
</div>

<?php endif; ?>

</div>

<script>
function toggleRow(id) {
    var row = document.getElementById("preview-" + id);
    if (row.style.display === "none") {
        row.style.display = "table-row";
    } else {
        row.style.display = "none";
    }
}
</script>

<script src="anim.js"></script></body>
</html>