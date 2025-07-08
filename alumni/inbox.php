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

// Select alumni (excluding current user)
$alumniStmt = $conn->prepare("SELECT id, first_name, last_name, email FROM users WHERE id != ?");
$alumniStmt->execute([$current_user]);
$alumni = $alumniStmt->fetchAll(PDO::FETCH_ASSOC);

// Get selected receiver_id
$receiver_id = isset($_GET['chat_with']) ? intval($_GET['chat_with']) : null;

// Handle sending message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $receiver_id && !empty($_POST['message'])) {
    $message = trim($_POST['message']);
    $file_path = ' ';
      if (!empty ($message)) {
        
        // $filename = time() . '_' . basename($_FILES['attachment']['name']);
        // $target = '../uploads' . $filename;
        // move_uploaded_file($_FILES['attachment']['tmp_name'], $target);
        // $file_path = $filename;

        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, attachment) VALUES (?, ?, ?,?)");
        $stmt->execute([$current_user, $receiver_id, $message, $file_path]);
    }
}


if (isset($_GET['delete'])) {
    $msg_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ? AND sender_id = ?");
    $stmt->execute([$msg_id, $current_user]);
}

// if (isset($_POST['edit_message'])) {
//     $msg_id = $_POST['message_id'];
//     $edited_msg = $_POST['edited_msg'];
//     $stmt = $conn->prepare("UPDATE messages SET message = ? WHERE id = ? AND sender_id = ?");
//     $stmt->execute([$edited_msg, $msg_id, $current_user]);
// }

// Fetch chat history
$chat = [];
$profile = null;
if ($receiver_id) {
    $chatStmt = $conn->prepare("
        SELECT m.*, u.first_name, u.last_name 
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
        ORDER BY m.created_at ASC
    ");
    $chatStmt->execute([$current_user, $receiver_id, $receiver_id, $current_user]);
    $chat = $chatStmt->fetchAll(PDO::FETCH_ASSOC);

    
    $pstmt = $conn->prepare("SELECT u.*, d.job_position, d.company, d.profile_image FROM users u JOIN user_details d ON u.id = d.user_id WHERE u.id = ?");
    $pstmt->execute([$receiver_id]);
    $profile = $pstmt->fetch();
}
?>


<div class="container mt-4">
    <h3>👥 Alumni Directory</h3>
    <div class="row">
        <div class="col-md-4">
   
            <ul class="list-group">
                <?php foreach ($alumni as $alumnus): ?>
                    <li class="list-group-item  <?= ($receiver_id == $alumnus['id']) ? 'active' : '' ?>">
                       <a href="?chat_with=<?= $alumnus['id'] ?>" class="text-decoration-none <?= ($receiver_id == $alumnus['id']) ? 'text-white' : '' ?>">
                            <?= htmlspecialchars($alumnus['first_name'] . ' ' . $alumnus['last_name']) ?>
                       </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="col-md-8">
            <?php if ($receiver_id && $profile): ?>
            <div class="card mb-3">
                <div class="card-body d-flex align-items-center">
                    <img src="../uploads/<?= $profile['profile_image'] ?: 'default.jpg' ?>" width="60" class="rounded-circle me-3" alt="Profile">
                    <div>
                    <h5 class="mb-0"><?= $profile['first_name'] . ' ' . $profile['last_name'] ?></h5>
                    <small><?= $profile['job_position'] ?> at <?= $profile['company'] ?> (Class of : <?= $profile['graduation_year'] ?>)</small>
                    </div>
                </div>
           </div>

            <div class="chat-box mb-3" style="max-height: 400px; overflow-y: auto;">
            <?php foreach ($chat as $msg): ?>
                <div class="mb-2">
                <strong><?= $msg['sender_id'] == $current_user ? 'You' : $msg['first_name'] . ' ' . $msg['last_name'] ?>:</strong>
                <p class="mb-1"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                <small class="text-muted"><?= date('M d, Y h:i A', strtotime($msg['created_at'])) ?></small>


                <?php if ($msg['sender_id'] == $current_user): ?>
                    <form method="post" class="d-inline">
                    <input type="hidden" name="message_id" value="<?= $msg['id'] ?>">
                    <!-- <input type="text" name="edited_msg" class="form-control d-inline w-50" value="<?= htmlspecialchars($msg['message']) ?>">
                    <button type="submit" name="edit_message" class="btn btn-sm btn-primary">Edit</button> -->
                    </form>
                    <a href="?receiver_id=<?= $receiver_id ?>&delete=<?= $msg['id'] ?>" class="text-danger">Delete</a>
                <?php endif; ?>

                </div>
            <?php endforeach; ?>
            </div>


                <form method="post" enctype="multipart/form-data" class="mb-5">
                    <div class="input-group">
                        <input type="text" name="message" class="form-control" required>
                        <!-- <input type="file" name="attachment" class="form-control"> -->
                        <button type="submit" name="send_message" class="btn btn-success">Send</button>
                    </div>
                </form>
                      
            <?php else: ?>
               <br> <p>Select an alumnus to start chatting.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
