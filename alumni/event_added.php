<?php
include '../includes/header.php';
require '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? 0;

// Handle delete event
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_event_id'])) {
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ? AND posted_by = ?");
    $stmt->execute([$_POST['delete_event_id'], $user_id]);
    echo "<script>alert('Event deleted successfully!'); window.location.href='event_added.php';</script>";
     logAction($conn, $_SESSION['user_id'] ?? null, 'Delete Event', "Job ID: {$event_id}");
}

// Handle cancel registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_app_id'])) {
    $stmt = $conn->prepare("DELETE FROM event_reg WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['cancel_app_id'], $user_id]);
    echo "<script>alert('Registration cancelled successfully!'); window.location.href='event_added.php';</script>";
logAction($conn, $_SESSION['user_id'] ?? null, 'Event Unregister', "Event ID: {$event_id}, Reason: {$reason}");

}


// Pagination Setup
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Total counts
$stmt = $conn->prepare("SELECT COUNT(*) FROM events WHERE posted_by = ?");
$stmt->execute([$user_id]);
$total_posted = $stmt->fetchColumn();

$stmt = $conn->prepare("SELECT COUNT(*) FROM event_reg WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_applied = $stmt->fetchColumn();

// Events posted by user
$stmt = $conn->prepare("SELECT * FROM events WHERE posted_by = ? ORDER BY event_date DESC LIMIT $limit OFFSET $offset");
$stmt->execute([$user_id]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Events user registered for
$stmt = $conn->prepare("
    SELECT a.*, er.id AS reg_id
    FROM event_reg er
    JOIN events a ON er.event_id = a.id
    WHERE er.user_id = ?
    ORDER BY a.event_date DESC
    LIMIT $limit
");
$stmt->execute([$user_id]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container p-4">
    <h3 class="mb-4">📌 Events You've Posted (<?= $total_posted ?>)</h3>
    <?php if ($events): ?>
        <?php foreach ($events as $event): ?>
            <div class="list-group mb-3">
                <div class="list-group-item flex-column justify-content-between">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="../uploads/<?= htmlspecialchars($event['photo']) ?>" class="img-fluid rounded-circle m-3" alt="Event Image" width="100" height="100">
                        </div>
                        <div class="col-md-6">
                            <h5><?= htmlspecialchars($event['title']) ?></h5>
                            <p><?= htmlspecialchars($event['description']) ?></p>
                            <p><small class="text-muted">Type: <?= htmlspecialchars($event['type']) ?> | Venue: <?= htmlspecialchars($event['location']) ?></small></p>
                            <small>Event Date: <?= htmlspecialchars($event['event_date']) ?></small>
                        </div>        
                        <div class="col-md-2 my-3">
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteEventModal<?= $event['id'] ?>">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Event Modal -->
            <div class="modal fade" id="deleteEventModal<?= $event['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header"><h5>Confirm Deletion</h5></div>
                        <div class="modal-body">Are you sure you want to delete this event?</div>
                        <div class="modal-footer">
                            <form method="post">
                                <input type="hidden" name="delete_event_id" value="<?= $event['id'] ?>">
                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div> 
        <?php endforeach; ?>
    <?php else: ?>
        <p>No events posted yet.</p>
    <?php endif; ?>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= ceil($total_posted / $limit); $i++): ?>
                <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

    <hr>
    <h3 class="mt-5 mb-4">📝 Events You've Registered For (<?= $total_applied ?>)</h3>
    <?php if ($applications): ?>
        <?php foreach ($applications as $app): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5><?= htmlspecialchars($app['title']) ?></h5>
                    <p><?= htmlspecialchars($app['description']) ?></p>
                    <p><?= htmlspecialchars($app['type']) ?></p>
                    <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal<?= $app['reg_id'] ?>">Cancel Registration</button>
                </div>
            </div>

            <!-- Cancel Registration Modal -->
            <div class="modal fade" id="cancelModal<?= $app['reg_id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header"><h5>Reason for Cancellation</h5></div>
                        <div class="modal-body">
                            <form method="post">
                                <input type="hidden" name="cancel_app_id" value="<?= $app['reg_id'] ?>">
                                <textarea name="cancel_reason" required class="form-control" placeholder="Why are you cancelling?"></textarea>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-warning">Cancel Registration</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No event registrations yet.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>