
<?php
require 'header.php';
require '../config/database.php';
if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
    exit();
}
// Pagination
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filters
$search = $_GET['search'] ?? '';
$filter_category = $_GET['category'] ?? '';
$filter_status = $_GET['status'] ?? '';
$filter_advertiser = $_GET['advertiser'] ?? '';


// Handle Status Change
if (isset($_POST['change_status_id'], $_POST['new_status'])) {
    $stmt = $conn->prepare("UPDATE advertisement SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['new_status'], $_POST['change_status_id']]);
    $_SESSION['msg'] = "Job status updated!";
    header("Location: jobs.php");
    exit;
}

// Build query conditions
$where = [];
$params = [];
if (!empty($search)) {
    $where[] = "(title LIKE ? OR description LIKE ?)";
    $params[] = "%$search%"; $params[] = "%$search%";
}
if (!empty($filter_category)) {
    $where[] = "category = ?";
    $params[] = $filter_category;
}
if (!empty($filter_status)) {
    $where[] = "status = ?";
    $params[] = $filter_status;
}
if (!empty($filter_advertiser)) {
    $where[] = "advertiser = ?";
    $params[] = $filter_advertiser;
}

$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";


// Dropdown data
$categories = $conn->query("SELECT DISTINCT category FROM advertisement")->fetchAll(PDO::FETCH_COLUMN);
$statuses = $conn->query("SELECT DISTINCT status FROM advertisement")->fetchAll(PDO::FETCH_COLUMN);
$advertisers = $conn->query("SELECT DISTINCT advertiser FROM advertisement")->fetchAll(PDO::FETCH_COLUMN);

// Fetch jobs
$stmt = $conn->prepare("SELECT * FROM advertisement $whereSQL ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total count
$totalStmt = $conn->prepare("SELECT COUNT(*) FROM advertisement $whereSQL");
$totalStmt->execute($params);
$totalJobs = $totalStmt->fetchColumn();
$totalPages = ceil($totalJobs / $limit);

// Handle Add Job
if (isset($_POST['add_job'])) {
    $photo = $_FILES['photo']['name'];
    $target = '../uploads/' . basename($photo);
    move_uploaded_file($_FILES['photo']['tmp_name'], $target);

    $stmt = $conn->prepare("INSERT INTO advertisement 
        (title, description, button_message, button_link, photo, category, status, advertiser, posted_by, appliable, date_to_hide, created_at)
        VALUES (?, ?, ?, ?, ?, ?, 'pending', ?, ?, ?, ?, NOW())");
    $stmt->execute([
        $_POST['title'], $_POST['description'], $_POST['button_message'], $_POST['button_link'],
        $photo, $_POST['category'], $_POST['advertiser'], $_SESSION['admin_id'],
        $_POST['appliable'], $_POST['date_to_hide']
    ]);
    header("Location: admin_manage_jobs.php");
    exit;
}

// Handle Delete
if (isset($_POST['delete_job_id'])) {
    $stmt = $conn->prepare("DELETE FROM advertisement WHERE id = ?");
    $stmt->execute([$_POST['delete_job_id']]);
    header("Location: admin_manage_jobs.php");
    exit;
}

// Handle Approve
if (isset($_POST['approve_job_id'])) {
    $stmt = $conn->prepare("UPDATE advertisement SET status = 'active' WHERE id = ?");
    $stmt->execute([$_POST['approve_job_id']]);
    header("Location: admin_manage_jobs.php");
    exit;
}
?>

    <?php if (!empty($_SESSION['msg'])): ?>
        <div class="alert alert-success"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
    <?php endif; ?>

<div class="container mt-4">
    <h3 class="mb-4">Manage Jobs (<?= $totalJobs ?> total)</h3>

    <!-- Filters -->
    <form method="get" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search jobs..." class="form-control">
        </div>
        <div class="col-md-2">
            <select name="category" class="form-control">
                <option value="">All Categories</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= htmlspecialchars($c) ?>" <?= $filter_category == $c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-control">
                <option value="">All Status</option>
                <?php foreach ($statuses as $s): ?>
                    <option value="<?= htmlspecialchars($s) ?>" <?= $filter_status == $s ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="advertiser" class="form-control">
                <option value="">All Advertisers</option>
                <?php foreach ($advertisers as $a): ?>
                    <option value="<?= htmlspecialchars($a) ?>" <?= $filter_advertiser == $a ? 'selected' : '' ?>><?= htmlspecialchars($a) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Filter</button>
        </div>
    </form>

    <!-- Jobs Table -->
    <table class="table table-bordered">
        <thead class="table-success">
            <tr>
                <th>Title</th>
                <th>Advertiser</th>
                <th>Category</th>
                <th>Status</th>
                <th>Appliable</th>
                <th>Date to Hide</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($jobs as $job): ?>
            <tr>
                <td><?= htmlspecialchars($job['title']) ?></td>
                <td><?= htmlspecialchars($job['advertiser']) ?></td>
                <td><?= htmlspecialchars($job['category']) ?></td>
                <td><?= htmlspecialchars($job['status']) ?></td>
                <td><?= $job['appliable'] ? 'Yes' : 'No' ?></td>
                <td><?= htmlspecialchars($job['date_to_hide']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="approveModal(<?= $job['id'] ?>)">Change Status</button>
                    <!-- <a href="edit_job.php?id=<?= $job['id'] ?>" class="btn btn-success btn-sm">Edit</a> -->
                    <button class="btn btn-danger btn-sm" onclick="deleteJob(<?= $job['id'] ?>)">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php for ($i=1; $i<=$totalPages; $i++): ?>
                <li class="page-item <?= $page==$i?'active':'' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&title=<?= $filterTitle ?>&category=<?= $filterCategory ?>&status=<?= $filterStatus ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<!-- Add Job Modal -->
<div class="modal fade" id="addJobModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Add Job</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="text" name="title" class="form-control mb-2" placeholder="Job Title" required>
                    <textarea name="description" class="form-control mb-2" placeholder="Description" required></textarea>
                    <input type="text" name="button_message" class="form-control mb-2" placeholder="Button Message" required>
                    <input type="url" name="button_link" class="form-control mb-2" placeholder="Button Link" required>
                    <input type="file" name="photo" class="form-control mb-2" accept="image/*" required>
                    <select name="category" class="form-select mb-2" required>
                        <option value="Job">Job</option>
                        <option value="Internship">Internship</option>
                        <option value="Scholarship">Scholarship</option>
                        <option value="Competition">Competition</option>
                    </select>
                    <input type="text" name="advertiser" class="form-control mb-2" placeholder="Advertiser" required>
                    <select name="appliable" class="form-select mb-2" required>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    <input type="date" name="date_to_hide" class="form-control mb-2" required>
                </div>
                <div class="modal-footer">
                    <button name="add_job" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white"><h5>Confirm Delete</h5></div>
            <div class="modal-body">Are you sure you want to delete this job?</div>
            <div class="modal-footer">
                <form method="post">
                    <input type="hidden" name="delete_job_id" id="deleteJobId">
                    <button class="btn btn-danger">Yes, Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Approve/Change Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5>Change Job Status</h5></div>
      <div class="modal-body">
        <form method="post">
            <input type="hidden" name="change_status_id" id="statusJobId">
            <select name="new_status" class="form-control" required>
                <option value="">Select Status</option>
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="rejected">Rejected</option>
            </select>
            <div class="mt-3 text-end">
                <button type="submit" class="btn btn-success">Update Status</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
function deleteJob(id) {
    document.getElementById('deleteJobId').value = id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
function approveModal(id) {
    document.getElementById('statusJobId').value = id;
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}
</script>

<?php require '../includes/footer.php'; ?>



