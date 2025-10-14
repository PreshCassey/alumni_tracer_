<?php
include '../includes/header.php';
require '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
     echo "<div class='alert alert-danger text-center mt-4'><a href='../auth/login.php'>Please log in.</a></div>";
    include '../includes/footer.php';
    exit();
}

// Add Event
if (isset($_POST['add_event'])) {
    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];
    $type = $_POST['type'];
    $status = 'Pending';
    $photo = $_FILES['photo']['name'];

    $target = '../uploads/' . basename($photo);
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO events (title, location, description, event_date, type, photo, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $location, $description, $event_date, $type, $photo, $status]);
        echo "<div class='alert alert-success text-center mt-3'>Event added successfully! Awaiting admin approval.</div>";
        logAction($conn, $_SESSION['user_id'] ?? null, 'Post Event', "Title: {$title}");
    }
}

// Register Event
if (isset($_POST['register_event'])) {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id) {
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM event_reg WHERE event_id = ? AND user_id = ?");
        $checkStmt->execute([$event_id, $user_id]);
        if ($checkStmt->fetchColumn() == 0) {
            $stmt = $conn->prepare("INSERT INTO event_reg (event_id, user_id) VALUES (?, ?)");
            $stmt->execute([$event_id, $user_id]);
            echo "<div class='alert alert-success text-center mt-3'>Registered successfully!</div>";
            logAction($conn, $user_id, 'Register Event', "Event ID: {$event_id}");
        } else {
            echo "<div class='alert alert-danger text-center mt-3'>You are already registered for this event.</div>";
        }
    }
}

// Pagination & Filters
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$filterTitle = $_GET['title'] ?? '';
$filterLocation = $_GET['location'] ?? '';
$filterType = $_GET['type'] ?? '';

$where = "WHERE status = 'approved'";
$params = [];

if (!empty($filterTitle)) {
    $where .= " AND title LIKE ?";
    $params[] = "%$filterTitle%";
}
if (!empty($filterLocation)) {
    $where .= " AND location LIKE ?";
    $params[] = "%$filterLocation%";
}
if (!empty($filterType)) {
    $where .= " AND type = ?";
    $params[] = $filterType;
}

$stmt = $conn->prepare("SELECT * FROM events $where ORDER BY event_date DESC LIMIT $start, $limit");
$stmt->execute($params);
$events = $stmt->fetchAll();

$totalStmt = $conn->prepare("SELECT COUNT(*) FROM events $where");
$totalStmt->execute($params);
$total = $totalStmt->fetchColumn();
$pages = ceil($total / $limit);
?>

<div class="container py-5 animate__animated animate__fadeIn">
  <h2 class="mb-4 text-blue fw-bold">🎉 Upcoming Events</h2>

  <!-- Filter Toolbar -->
  <form method="get" class="row g-2 mb-4 shadow-sm p-3 rounded bg-white">
    <div class="col-md-3">
      <input type="text" name="title" class="form-control" placeholder="🔍 Title" value="<?= htmlspecialchars($filterTitle) ?>">
    </div>
    <div class="col-md-3">
      <input type="text" name="location" class="form-control" placeholder="📍 Location" value="<?= htmlspecialchars($filterLocation) ?>">
    </div>
    <div class="col-md-3">
      <select name="type" class="form-select">
        <option value="">All Types</option>
        <option value="Conference" <?= $filterType == 'Conference' ? 'selected' : '' ?>>Conference</option>
        <option value="Workshop" <?= $filterType == 'Workshop' ? 'selected' : '' ?>>Workshop</option>
        <option value="Reunion" <?= $filterType == 'Reunion' ? 'selected' : '' ?>>Reunion</option>
      </select>
    </div>
    <div class="col-md-3 text-md-end">
      <button type="submit" class="btn btn-outline-silver">Filter</button>
      <button type="button" class="btn btn-outline-silver ms-2" data-bs-toggle="modal" data-bs-target="#addEventModal">+ Event</button>
      <a href="event_added.php" class="btn btn-alumex ms-2">My Events</a>
    </div>
  </form>

  <!-- Events Section -->
  <?php if (count($events) > 0): ?>
    <?php foreach ($events as $event): ?>
      <div class="card mb-4 shadow-sm border-0 hover-shadow transition">
        <div class="row g-0 align-items-center">
          <div class="col-md-3 text-center p-3">
            <img src="../uploads/<?= htmlspecialchars($event['photo']) ?>" class="img-fluid rounded-circle shadow-sm" alt="Event Photo" width="120" height="120">
          </div>
          <div class="col-md-9">
            <div class="card-body">
              <h5 class="fw-bold mb-2"><?= htmlspecialchars($event['title']) ?></h5>
              <p class="mb-2 text-muted"><i class="bi bi-geo-alt-fill text-blue"></i> <?= htmlspecialchars($event['location']) ?></p>
              <p class="mb-2"><?= htmlspecialchars($event['description']) ?></p>
              <p class="small text-muted"><i class="bi bi-calendar-event text-blue"></i> <?= date('F j, Y', strtotime($event['event_date'])) ?> | <strong><?= htmlspecialchars($event['type']) ?></strong></p>
              <button type="button" class="btn btn-alumex btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal<?= $event['id'] ?>">Register</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Register Modal -->
      <div class="modal fade" id="registerModal<?= $event['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content border-0 shadow-lg rounded-4">
            <form method="post">
              <div class="modal-body text-center py-4">
                <h5 class="fw-bold mb-3">Register for <?= htmlspecialchars($event['title']) ?>?</h5>
                <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                <p class="text-muted">Confirm your participation and stay updated.</p>
              </div>
              <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="register_event" class="btn btn-outline-silver px-4">Register</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="text-center py-5 bg-white rounded shadow-sm">
      <h5 class="text-muted mb-2">🚫 No Events Found</h5>
      <p>Try adjusting your filters or check back later for new events.</p>
    </div>
  <?php endif; ?>

  <!-- Pagination -->
  <nav class="mt-4">
    <ul class="pagination justify-content-center">
      <?php for ($i = 1; $i <= $pages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>&title=<?= urlencode($filterTitle) ?>&location=<?= urlencode($filterLocation) ?>&type=<?= urlencode($filterType) ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <form method="post" enctype="multipart/form-data">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-bold text-blue">Create New Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Title</label>
              <input type="text" name="title" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Location</label>
              <input type="text" name="location" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Date</label>
              <input type="date" name="event_date" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Type</label>
              <select name="type" class="form-select" required>
                <option value="Conference">Conference</option>
                <option value="Workshop">Workshop</option>
                <option value="Reunion">Reunion</option>
              </select>
            </div>
            <div class="col-md-12">
              <label class="form-label">Photo</label>
              <input type="file" name="photo" class="form-control" accept="image/*" required>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="add_event" class="btn btn-outline-silver px-4">Save Event</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
