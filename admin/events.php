<?php
require 'header.php';
require '../config/database.php';
if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
    exit();
}
// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Handle Delete
if (isset($_POST['delete_event_id'])) {
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$_POST['delete_event_id']]);
    $_SESSION['msg'] = "Event deleted successfully!";
    header("Location: events.php");
    logAction($conn, null, 'Delete Event successfully', "Event_id: [delete_event_id]");

    exit;
}

// Handle Approval Toggle
if (isset($_POST['toggle_status_id'])) {
    $stmt = $conn->prepare("UPDATE events SET status = CASE WHEN status = 'approved' THEN 'pending' ELSE 'approved' END WHERE id = ?");
    $stmt->execute([$_POST['toggle_status_id']]);
    $_SESSION['msg'] = "Event status updated!";
    header("Location: events.php");
    logAction($conn, null, 'Approve Event successfully', "Event_id: [toggle_status_id]");

    exit;
}

// Handle Add Event
if (isset($_POST['add_event'])) {
    $stmt = $conn->prepare("INSERT INTO events (title, location, description, event_date, type, status, posted_by) VALUES (?, ?, ?, ?, ?, 'pending', ?)");
    $stmt->execute([$_POST['title'], $_POST['location'], $_POST['description'], $_POST['event_date'], $_POST['type'], $_SESSION['admin_id']]);
    $_SESSION['msg'] = "Event added successfully!";
    header("Location: events.php");
    logAction($conn, null, 'Added Event successfully', "title: [title]");

    exit;
}

// Get events with pagination
$total = $conn->query("SELECT COUNT(*) FROM events")->fetchColumn();
$stmt = $conn->prepare("SELECT * FROM events ORDER BY event_date DESC LIMIT $limit OFFSET $offset");
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Search & Filter
$search = $_GET['search'] ?? '';
$filter_type = $_GET['type'] ?? '';
$filter_status = $_GET['status'] ?? '';
$filter_date = $_GET['event_date'] ?? '';

// Build query with filters
$where = [];
$params = [];

if (!empty($search)) {
    $where[] = "(title LIKE ? OR description LIKE ?)";
    $params[] = "%$search%"; 
    $params[] = "%$search%";
}

if (!empty($filter_type)) {
    $where[] = "type = ?";
    $params[] = $filter_type;
}

if (!empty($filter_status)) {
    $where[] = "status = ?";
    $params[] = $filter_status;
}

if (!empty($filter_date)) {
    $where[] = "DATE(event_date) = ?";
    $params[] = $filter_date;
}

$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

// Fetch Events
$sql = "SELECT * FROM events $whereSQL ORDER BY event_date DESC";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="container py-4 mb-5">
    <h3 class="text-success mb-4">Manage Events(<?= $total?> total)</h3>

    <?php if (!empty($_SESSION['msg'])): ?>
        <div class="alert alert-success"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
    <?php endif; ?>

    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addEventModal">+ Add New Event</button>

    
<!-- Filters -->
<form method="get" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Search event">
    </div>
    <div class="col-md-2">
        <select name="type" class="form-control">
            <option value="">All Types</option>
            <option value="seminar" <?= $filter_type=="seminar"?'selected':'' ?>>Seminar</option>
            <option value="workshop" <?= $filter_type=="workshop"?'selected':'' ?>>Workshop</option>
            <option value="conference" <?= $filter_type=="conference"?'selected':'' ?>>Conference</option>
        </select>
    </div>
    <div class="col-md-2">
        <select name="status" class="form-control">
            <option value="">All Status</option>
            <option value="pending" <?= $filter_status=="pending"?'selected':'' ?>>Pending</option>
            <option value="approved" <?= $filter_status=="approved"?'selected':'' ?>>Approved</option>
        </select>
    </div>
    <div class="col-md-3">
        <input type="date" name="date" value="<?= htmlspecialchars($filter_date) ?>" class="form-control">
    </div>
    <div class="col-md-2">
        <button class="btn btn-success w-100">Filter</button>
    </div>
</form>
    <table class="table table-bordered table-responsive table-striped">
        <thead class="table-success">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Location</th>
                <th>Date</th>
                <th>Type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($events as $index => $event): ?>
            <tr>
                <td><?= $offset + $index + 1 ?></td>
                <td><?= htmlspecialchars($event['title']) ?></td>
                <td><?= htmlspecialchars($event['location']) ?></td>
                <td><?= $event['event_date'] ?></td>
                <td><?= $event['type'] ?></td>
                <td><span class="badge bg-<?= $event['status'] == 'approved' ? 'success' : 'warning' ?>"><?= ucfirst($event['status']) ?></span></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="toggle_status_id" value="<?= $event['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-success"><?= $event['status'] == 'approved' ? 'Unapprove' : 'Approve' ?></button>
                    </form>
                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $event['id'] ?>)">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= ceil($total / $limit); $i++): ?>
                <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Confirm Delete</h5></div>
      <div class="modal-body">Are you sure you want to delete this event?</div>
      <div class="modal-footer">
        <form method="post">
            <input type="hidden" name="delete_event_id" id="deleteEventId">
            <button type="submit" class="btn btn-danger">Yes, Delete</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Add Event</h5></div>
      <div class="modal-body">
        <form method="post">
            <input type="hidden" name="add_event" value="1">
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Location</label>
                <input type="text" name="location" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label>Event Date</label>
                <input type="date" name="event_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Type</label>
                <input type="text" name="type" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Add Event</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function confirmDelete(id) {
    document.getElementById('deleteEventId').value = id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
    
}
</script>

<?php require '../includes/footer.php'; ?>