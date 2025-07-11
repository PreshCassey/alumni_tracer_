<?php
include '../includes/header.php';
require '../config/database.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'>Please log in.</div>";
    include '../includes/footer.php';
    exit();
}


// Add Event logic
if (isset($_POST['add_event'])) {
    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];
    $type = $_POST['type'];
    $photo = $_FILES['photo']['name'];

    $target = '../uploads/' . basename($photo);
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO events (title, location, description, event_date, type, photo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $location, $description, $event_date, $type, $photo]);

        echo "<div class='alert alert-success text-center'>Added successfully!</div>";
    }
}

// Register Event logic
if (isset($_POST['register_event'])) {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id) {
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM event_reg WHERE event_id = ? AND user_id = ?");
        $checkStmt->execute([$event_id, $user_id]);
        if ($checkStmt->fetchColumn() == 0) {
            $stmt = $conn->prepare("INSERT INTO event_reg (event_id, user_id) VALUES (?, ?)");
            $stmt->execute([$event_id, $user_id]);

            echo "<div class='alert alert-success text-center'>Registered successfully!</div>";
        }
         else{
           echo "<div class='alert alert-danger text-center'>Registered already!</div>";

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

$where = "WHERE 1=1";
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

<div class="container py-5">
  <h2 class="mb-4">Upcoming Events</h2>
  <form method="get" class="row g-2 mb-4">
    <div class="col-md-3">
      <input type="text" name="title" class="form-control" placeholder="Title" value="<?= htmlspecialchars($filterTitle) ?>">
    </div>
    <div class="col-md-3">
      <input type="text" name="location" class="form-control" placeholder="Location" value="<?= htmlspecialchars($filterLocation) ?>">
    </div>
    <div class="col-md-3">
      <select name="type" class="form-select form-control">
        <option value="">All Types</option>
        <option value="Conference" <?= $filterType == 'Conference' ? 'selected' : '' ?>>Conference</option>
        <option value="Workshop" <?= $filterType == 'Workshop' ? 'selected' : '' ?>>Workshop</option>
        <option value="Reunion" <?= $filterType == 'Reunion' ? 'selected' : '' ?>>Reunion</option>
      </select>
    </div>
    <div class="col-md-3">
      <button type="submit" class="btn btn-success">Filter</button>
      <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#addEventModal">+ Event</button>
      <a role="button" href="event_added.php" class="btn btn-success ms-2">Events</a>
    </div>
  </form>

  <?php foreach ($events as $event): ?>
    <div class="card mb-3">
      <div class="row g-0">
        <div class="col-md-3">
          <img src="../uploads/<?= htmlspecialchars($event['photo']) ?>" class="img-fluid rounded-circle my-5 ml-5" alt="Event Photo" width="200" height="200">
        </div>
        <div class="col-md-9">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($event['title']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($event['description']) ?></p>
            <p class="card-text"><small class="text-muted">Location: <?= htmlspecialchars($event['location']) ?> | Date: <?= $event['event_date'] ?></small></p>
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#registerModal<?= $event['id'] ?>">Register</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Register Event Modal for each event -->
    <div class="modal fade" id="registerModal<?= $event['id'] ?>" tabindex="-1" aria-labelledby="registerModalLabel<?= $event['id'] ?>" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="post">
            <div class="modal-body">
              Are you sure you want to register for this event?
              <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
              <button type="submit" name="register_event" class="btn btn-success">Yes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <nav>
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
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="addEventModalLabel">Add Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="title" class="form-label">Event Title</label>
            <input type="text" class="form-control" name="title" required>
          </div>
          <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" name="location" required>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="4" required></textarea>
          </div>
          <div class="mb-3">
            <label for="event_date" class="form-label">Event Date</label>
            <input type="date" class="form-control" name="event_date" required>
          </div>
          <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" class="form-select" required>
              <option value="Conference">Conference</option>
              <option value="Workshop">Workshop</option>
              <option value="Reunion">Reunion</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input type="file" class="form-control" name="photo" accept="image/*" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="add_event" class="btn btn-success">Save Event</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
