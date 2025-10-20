<?php
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
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");

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

// Handle delete message
if (isset($_GET['delete'])) {
    $msg_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ? AND sender_id = ?");
    $stmt->execute([$msg_id, $current_user]);
}

// Fetch chat history if chatting
$chat = [];
$profile = null;
if ($receiver_id) {
    $chatStmt = $conn->prepare("SELECT m.*, u.first_name, u.last_name, d.profile_image FROM messages m
JOIN users u ON m.sender_id = u.id
JOIN user_details d ON u.id = d.user_id
WHERE (m.sender_id = ? AND m.receiver_id = ?) 
OR (m.sender_id = ? AND m.receiver_id = ?)ORDER BY m.created_at ASC;");

    $chatStmt->execute([$current_user, $receiver_id, $receiver_id, $current_user]);
    $chat = $chatStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch profile info
    $pstmt = $conn->prepare("SELECT u.*, d.job_position, d.company, d.profile_image, u.graduation_year 
        FROM users u 
        JOIN user_details d ON u.id = d.user_id 
        WHERE u.id = ?
    ");
    $pstmt->execute([$receiver_id]);
    $profile = $pstmt->fetch();
}

// Fetch recent chats (if no current chat)
$recentChats = [];
if (!$receiver_id) {
    $recentStmt = $conn->prepare("SELECT DISTINCT u.id, u.first_name, u.last_name 
        FROM users u
        JOIN user_details d ON (u.id = d.user_id)
        JOIN messages m ON (u.id = m.sender_id OR u.id = m.receiver_id)
        WHERE (m.sender_id = :uid OR m.receiver_id = :uid)
        AND u.id != :uid
        ORDER BY m.created_at DESC
        LIMIT 10
    ");
    $recentStmt->execute(['uid' => $current_user]);
    $recentChats = $recentStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch alumni directory (only if no chat and no recent chats)
$alumni = [];
if (!$receiver_id && empty($recentChats)) {
$alumniStmt = $conn->prepare("SELECT u.id, u.first_name, u.last_name, u.email, 
        d.profile_image FROM users u JOIN user_details d ON u.id = d.user_id WHERE u.id != ?");
    $alumniStmt->execute([$current_user]);
    $alumni = $alumniStmt->fetchAll(PDO::FETCH_ASSOC);
}


$where = "WHERE id != ?";
$params = [$current_user];

if (!empty($_GET['name'])) {
    $where .= " AND (first_name LIKE ? OR last_name LIKE ?)";
    $params[] = "%" . $_GET['name'] . "%";
    $params[] = "%" . $_GET['name'] . "%";
}

if (!empty($_GET['grad_year'])) {
    $where .= " AND graduation_year = ?";
    $params[] = $_GET['grad_year'];
}

if (!empty($_GET['course'])) {
    $where .= " AND course LIKE ?";
    $params[] = "%" . $_GET['course'] . "%";
}

if (!empty($_GET['matric_no'])) {
    $where .= " AND matric_no LIKE ?";
    $params[] = "%" . $_GET['matric_no'] . "%";
}

$alumniStmt = $conn->prepare("SELECT id, first_name, last_name, email FROM users $where");
$alumniStmt->execute($params);
$alumni = $alumniStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container mt-4">
    <?php if ($receiver_id): ?>
      <!-- Chat Header -->
      <div class="card mb-3">
        <?php if ($profile): ?>
        <div class="card-body d-flex align-items-center">
          <img src="../uploads/<?= $profile['profile_image'] ?: 'default.jpg' ?>" width="60" class="rounded-circle me-3">
          <div>
            <h5 class="mb-0"><?= htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']) ?></h5>
            <small class="text-muted">
              <?= htmlspecialchars($profile['job_position']) ?> at <?= htmlspecialchars($profile['company']) ?>
              (Class of <?= htmlspecialchars($profile['graduation_year']) ?>)
            </small>
          </div>
        </div>
        <?php endif; ?>
      </div>

      <!-- Chat Messages -->
      <div class="chat-box mb-3" style="max-height:400px; overflow-y:auto;">
        <?php foreach ($chat as $msg): ?>
          <div class="message <?= $msg['sender_id'] == $current_user ? 'you' : 'them' ?>">
            <div class="bubble">
              <?= nl2br(htmlspecialchars($msg['message'])) ?>
            </div>
            <small class="text-muted mt-1">
              <?= $msg['sender_id'] == $current_user ? 'You' : htmlspecialchars($msg['first_name']) ?> • 
              <?= date('M d, h:i A', strtotime($msg['created_at'])) ?>
              <?php if ($msg['sender_id'] == $current_user): ?>
                <a href="?chat_with=<?= $receiver_id ?>&delete=<?= $msg['id'] ?>" class="text-danger ms-2">Delete</a>
              <?php endif; ?>
            </small>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Send Message -->
      <form method="post" class="mb-5">
        <div class="input-group">
          <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
          <button type="submit" name="send_message" class="btn btn-success px-4">Send</button>
        </div>
      </form>

  <?php elseif (!empty($recentChats)): ?>
      <h4 class="mb-3">💬 Recently Chatted</h4>
      <ul class="list-group">
        <?php foreach ($recentChats as $chatUser): ?>
          <li class="list-group-item d-flex align-items-center">
            <img src="../uploads/<?= $chatUser['profile_image'] ?? 'default.jpg' ?>" class="alumni-avatar">
            <a href="?chat_with=<?= $chatUser['id'] ?>" class="text-decoration-none text-dark flex-grow-1">
              <?= htmlspecialchars($chatUser['first_name'] . ' ' . $chatUser['last_name']) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul><br><br><br>

  <?php else: ?>
      <h3 class="mb-3">👥 Alumni Directory</h3>
      <ul class="list-group">
        <?php foreach ($alumni as $alumnus): ?>
          <li class="list-group-item d-flex align-items-center">
            <img src="../uploads/<?= $alumnus['profile_image'] ?? 'default.jpg' ?>" class="alumni-avatar">
            <a href="?chat_with=<?= $alumnus['id'] ?>" class="text-decoration-none text-dark flex-grow-1">
              <?= htmlspecialchars($alumnus['first_name'] . ' ' . $alumnus['last_name']) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>