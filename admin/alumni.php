<?php
require 'header.php';
require '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
    exit();
}
// Search and Filter
$search = $_GET['search'] ?? '';
$filter_course = $_GET['course'] ?? '';
$filter_year = $_GET['year'] ?? '';

// Pagination
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Delete Alumni
if (isset($_POST['delete_alumni_id'])) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_POST['delete_alumni_id']]);
    $_SESSION['msg'] = "Alumni deleted successfully!";
    header("Location: alumni.php");
    logAction($conn, null, 'Delete Alumi successfully', "id: [delete_alumni_id]");

    exit;
}

// Build Query with filters
$where = [];
$params = [];
if (!empty($search)) {
    $where[] = "(first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
    $params[] = "%$search%"; $params[] = "%$search%"; $params[] = "%$search%";
}
if (!empty($filter_course)) {
    $where[] = "course = ?";
    $params[] = $filter_course;
}
if (!empty($filter_year)) {
    $where[] = "graduation_year = ?";
    $params[] = $filter_year;
}

$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

// Total alumni count
$stmt = $conn->prepare("SELECT COUNT(*) FROM users $whereSQL");
$stmt->execute($params);
$total = $stmt->fetchColumn();

// Alumni List
$sql = "SELECT * FROM users $whereSQL ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$alumni = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch unique courses and years for filter dropdowns
$courses = $conn->query("SELECT DISTINCT course FROM users ORDER BY course")->fetchAll(PDO::FETCH_COLUMN);
$years = $conn->query("SELECT DISTINCT graduation_year FROM users ORDER BY graduation_year DESC")->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="container py-4 mb-5">
    <h3 class="text-success mb-4">Manage Alumni</h3>

    <?php if (!empty($_SESSION['msg'])): ?>
        <div class="alert alert-success"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
    <?php endif; ?>

    <!-- Filters -->
    <form method="get" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search name or email" class="form-control">
        </div>
        <div class="col-md-3">
            <select name="course" class="form-control">
                <option value="">All Courses</option>
                <?php foreach ($courses as $c): ?>
                    <option value="<?= htmlspecialchars($c) ?>" <?= $filter_course == $c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="year" class="form-control">
                <option value="">All Years</option>
                <?php foreach ($years as $y): ?>
                    <option value="<?= $y ?>" <?= $filter_year == $y ? 'selected' : '' ?>><?= $y ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Filter</button>
        </div>
    </form>

    <!-- Alumni Table -->
    <table class="table table-bordered table-responsive table-striped">
        <thead class="table-success">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Matric No</th>
                <th>Course</th>
                <th>Grad Year</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($alumni as $index => $a): ?>
            <tr>
                <td><?= $offset + $index + 1 ?></td>
                <td><?= htmlspecialchars($a['first_name'] . ' ' . $a['last_name']) ?></td>
                <td><?= htmlspecialchars($a['email']) ?></td>
                <td><?= htmlspecialchars($a['matric_no']) ?></td>
                <td><?= htmlspecialchars($a['course']) ?></td>
                <td><?= $a['graduation_year'] ?></td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $a['id'] ?>)">Delete</button>
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
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&course=<?= urlencode($filter_course) ?>&year=<?= urlencode($filter_year) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5>Confirm Delete</h5></div>
      <div class="modal-body">Are you sure you want to delete this alumni?</div>
      <div class="modal-footer">
        <form method="post">
            <input type="hidden" name="delete_alumni_id" id="deleteAlumniId">
            <button type="submit" class="btn btn-danger">Yes, Delete</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function confirmDelete(id) {
    document.getElementById('deleteAlumniId').value = id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();

}
</script>

<?php require '../includes/footer.php'; ?>
