<?php
require 'header.php';
require '../config/database.php';

// function logAction($conn, $user_id, $action, $details = null) {
//     $ip = $_SERVER['REMOTE_ADDR'] ?? null;
//     $ua = $_SERVER['HTTP_USER_AGENT'] ?? null;

//     $stmt = $conn->prepare("INSERT INTO security_logs (user_id, action, details, ip_address, user_agent) 
//         VALUES (?, ?, ?, ?, ?)
//     ");
//     $stmt->execute([$user_id, $action, $details, $ip, $ua]);
// }

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch latest logs
$stmt = $conn->query("
    SELECT l.*, u.email 
    FROM security_logs l
    LEFT JOIN users u ON l.user_id = u.id
    ORDER BY l.created_at DESC 
    LIMIT 200
");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container py-4 mb-5">

<h3>🔒 Security Audit Log</h3>
<div class="table-responsive">
<table class="table table-bordered table-striped">
    <thead class="table-success">
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Action</th>
            <th>Details</th>
            <th>IP Address</th>
            <th>User Agent</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logs as $log): ?>
        <tr>
            <td><?= (int) $log['id'] ?></td>
            <td><?= htmlspecialchars($log['email'] ?? 'System') ?></td>
            <td><?= htmlspecialchars($log['action']) ?></td>
            <td><?= htmlspecialchars($log['details']) ?></td>
            <td><?= htmlspecialchars($log['ip_address']) ?></td>
            <td><?= htmlspecialchars(substr($log['user_agent'], 0, 50)) ?>...</td>
            <td><?= htmlspecialchars($log['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
</div>

<?php require '../includes/footer.php'; ?>
