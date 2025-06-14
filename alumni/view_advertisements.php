<?php
include '../includes/header.php';
require '../config/database.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Add Job/Advertisement
if (isset($_POST['add_job'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $button_message = $_POST['button_message'];
    $button_link = $_POST['button_link'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $appliable = isset($_POST['appliable']) ? 1 : 0;
    $date_to_hide = $_POST['date_to_hide'];
    $advertiser = $_SESSION['user_id']; // or replace with a form input
    $photo = $_FILES['photo']['name'];

    $target = '../uploads/' . basename($photo);
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO advertisement (title, description, date_added, button_message, button_link, photo, category, status, advertiser, appliable, date_to_hide) VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $button_message, $button_link, $photo, $category, $status, $advertiser, $appliable, $date_to_hide]);
    }
}

// Apply to a job
if (isset($_POST['apply_job'])) {
    $job_id = $_POST['job_id'];
    $user_id = $_SESSION['user_id'];

    $check = $conn->prepare("SELECT COUNT(*) FROM job_applications WHERE job_id = ? AND user_id = ?");
    $check->execute([$job_id, $user_id]);

    if ($check->fetchColumn() == 0) {
        $stmt = $conn->prepare("INSERT INTO job_applications (job_id, user_id, applied_at) VALUES (?, ?, NOW())");
        $stmt->execute([$job_id, $user_id]);
    }
}

// Filters
$category = $_GET['category'] ?? '';
$status = $_GET['status'] ?? '';

$where = "WHERE 1=1 AND (date_to_hide IS NULL OR date_to_hide >= CURDATE())";
$params = [];

if (!empty($category)) {
    $where .= " AND category = ?";
    $params[] = $category;
}
if (!empty($status)) {
    $where .= " AND status = ?";
    $params[] = $status;
}

// Fetch Jobs
$stmt = $conn->prepare("SELECT * FROM advertisement $where ORDER BY date_added DESC");
$stmt->execute($params);
$jobs = $stmt->fetchAll();
?>

<div class="container py-5">
  <h2 class="mb-4">Job & Advertisement Listings</h2>

  <form method="get" class="row g-2 mb-4">
    <div class="col-md-4">
      <input type="text" name="category" class="form-control" placeholder="Category" value="<?= htmlspecialchars($category) ?>">
    </div>
    <div class="col-md-4">
      <select name="status" class="form-select">
        <option value="">All Status</option>
        <option value="Active" <?= $status == 'Active' ? 'selected' : '' ?>>Active</option>
        <option value="Closed" <?= $status == 'Closed' ? 'selected' : '' ?>>Closed</option>
      </select>
    </div>
    <div class="col-md-4">
      <button class="btn btn-primary">Filter</button>
      <button class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#addJobModal" type="button">Add Job</button>
    </div>
  </form>

  <?php foreach ($jobs as $job): ?>
    <div class="card mb-3">
      <div class="row g-0">
        <div class="col-md-4">
          <img src="../uploads/<?= htmlspecialchars($job['photo']) ?>" class="img-fluid rounded-start" alt="Ad Image">
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($job['title']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($job['description']) ?></p>
            <p class="card-text"><small class="text-muted">Category: <?= htmlspecialchars($job['category']) ?> | Status: <?= htmlspecialchars($job['status']) ?></small></p>
            <a href="<?= htmlspecialchars($job['button_link']) ?>" class="btn btn-outline-primary"><?= htmlspecialchars($job['button_message']) ?></a>
            <?php if ($job['appliable']): ?>
              <button class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#applyModal<?= $job['id'] ?>">Apply</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Apply Modal -->
    <div class="modal fade" id="applyModal<?= $job['id'] ?>" tabindex="-1" aria-labelledby="applyModalLabel<?= $job['id'] ?>" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="post">
            <div class="modal-body">
              Are you sure you want to apply for <strong><?= htmlspecialchars($job['title']) ?></strong>?
              <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
              <button type="submit" name="apply_job" class="btn btn-success">Yes, Apply</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Add Job Modal -->
<div class="modal fade" id="addJobModal" tabindex="-1" aria-labelledby="addJobModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="addJobModalLabel">Add Job / Advertisement</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" required>
          </div>
          <div class="col-md-12">
            <label class="form-label">Description</label>
            <textarea name="description" rows="4" class="form-control" required></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Button Message</label>
            <input type="text" name="button_message" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Button Link</label>
            <input type="url" name="button_link" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
              <option value="Active">Active</option>
              <option value="Closed">Closed</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Hide After</label>
            <input type="date" name="date_to_hide" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Photo</label>
            <input type="file" name="photo" class="form-control" accept="image/*" required>
          </div>
          <div class="col-md-6 d-flex align-items-end">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="appliable" value="1" id="appliableCheck">
              <label class="form-check-label" for="appliableCheck">
                Can users apply?
              </label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_job" class="btn btn-primary">Post Advertisement</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
