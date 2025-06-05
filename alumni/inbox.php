<?php
session_start();
include '../includes/header.php';
require '../config/database.php';


// Check if user is logged in
$current_user = $_SESSION['user_id'] ?? null;
if (!$current_user) {
    echo "<div class='alert alert-danger'>Please log in first.</div>";
    include '../includes/footer.php';
    exit;
}

// Create messages table if not exists
$conn->exec("
    CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sender_id INT,
        receiver_id INT,
        message TEXT,
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");

// Select alumni (excluding current user)
$alumniStmt = $conn->prepare("SELECT id, first_name, last_name, email FROM users WHERE id != ?");
$alumniStmt->execute([$current_user]);
$alumni = $alumniStmt->fetchAll(PDO::FETCH_ASSOC);

// Get selected receiver_id
$receiver_id = isset($_GET['chat_with']) ? intval($_GET['chat_with']) : null;

// Handle sending message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $receiver_id && !empty($_POST['message'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$current_user, $receiver_id, $message]);
    }
}

// Fetch chat history
$chat = [];
if ($receiver_id) {
    $chatStmt = $conn->prepare("
        SELECT m.*, u.first_name, u.last_name 
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
        ORDER BY m.timestamp ASC
    ");
    $chatStmt->execute([$current_user, $receiver_id, $receiver_id, $current_user]);
    $chat = $chatStmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container mt-4">
    <h3>👥 Alumni Directory</h3>
    <div class="row">
        <div class="col-md-4">
            <ul class="list-group">
                <?php foreach ($alumni as $alumnus): ?>
                    <li class="list-group-item <?= ($receiver_id == $alumnus['id']) ? 'active' : '' ?>">
                        <a href="?chat_with=<?= $alumnus['id'] ?>" class="text-decoration-none <?= ($receiver_id == $alumnus['id']) ? 'text-white' : '' ?>">
                            <?= htmlspecialchars($alumnus['first_name'] . ' ' . $alumnus['last_name']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="col-md-8">
            <?php if ($receiver_id): ?>
                <h5 class="mb-3">💬 Chat with <?= htmlspecialchars($_GET['name'] ?? '') ?></h5>
                <div class="border p-3 mb-3" style="height: 300px; overflow-y: scroll; background: #f9f9f9;">
                    <?php foreach ($chat as $msg): ?>
                        <div class="mb-2">
                            <strong><?= $msg['sender_id'] == $current_user ? 'You' : $msg['first_name'] . ' ' . $msg['last_name'] ?>:</strong>
                            <p class="mb-1"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                            <small class="text-muted"><?= date('M d, Y h:i A', strtotime($msg['timestamp'])) ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>

                <form method="POST">
                    <div class="form-group">
                        <textarea name="message" class="form-control" rows="3" placeholder="Type your message..." required></textarea>
                    </div>
                    <button class="btn btn-primary mt-2">Send</button>
                </form>
            <?php else: ?>
                <p>Select an alumnus to start chatting.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
