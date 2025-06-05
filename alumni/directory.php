<?php
include '../includes/header.php';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filters
$search = $_GET['search'] ?? '';
$course = $_GET['course'] ?? '';
$grad_year = $_GET['graduation_year'] ?? '';

$where = "WHERE 1";
$params = [];

if (!empty($search)) {
    $where .= " AND (u.first_name LIKE ? OR u.last_name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($course)) {
    $where .= " AND u.course = ?";
    $params[] = $course;
}

if (!empty($grad_year)) {
    $where .= " AND u.graduation_year = ?";
    $params[] = $grad_year;
}

// Fetch total rows for pagination
$countStmt = $conn->prepare("SELECT COUNT(*) FROM users u JOIN user_details d ON u.id = d.user_id $where");
$countStmt->execute($params);
$totalRows = $countStmt->fetchColumn();
$totalPages = ceil($totalRows / $limit);

// Fetch alumni
$sql = "SELECT u.id, u.first_name, u.last_name, u.graduation_year, u.course, d.profile_image, d.job_position, d.company
        FROM users u
        JOIN user_details d ON u.id = d.user_id
        $where ORDER BY u.graduation_year DESC LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$alumni = $stmt->fetchAll();
?>

<div class="container py-5">
  <h2 class="mb-4">All Alumni</h2>
  <form method="get" class="row g-3 mb-4">
    <div class="col-md-4">
      <input type="text" name="search" class="form-control" placeholder="Search by name" value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-md-3">
      <input type="text" name="course" class="form-control" placeholder="Filter by course" value="<?= htmlspecialchars($course) ?>">
    </div>
    <div class="col-md-3">
      <input type="text" name="graduation_year" class="form-control" placeholder="Graduation Year" value="<?= htmlspecialchars($grad_year) ?>">
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-success w-100">Filter</button>
    </div>
  </form>

  <div class="row">
    <?php foreach ($alumni as $alum): ?>
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-body d-flex align-items-center">
            <img src="../uploads/<?= $alum['profile_image'] ?: 'default.png' ?>" alt="Profile" class="rounded-circle me-3" width="70" height="70">
            <div class="flex-grow-1">
              <h5 class="mb-1"><?= htmlspecialchars($alum['first_name'] . ' ' . $alum['last_name']) ?></h5>
              <p class="mb-0">Class of <?= htmlspecialchars($alum['graduation_year']) ?> | <?= htmlspecialchars($alum['course']) ?></p>
              <small class="text-muted"><?= htmlspecialchars($alum['job_position']) ?> at <?= htmlspecialchars($alum['company']) ?></small>
            </div>
            <a href="message.php?user_id=<?= $alum['id'] ?>" class="btn btn-outline-primary ms-3">Message</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <nav>
    <ul class="pagination justify-content-center">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&course=<?= urlencode($course) ?>&graduation_year=<?= urlencode($grad_year) ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
</div>

<?php include '../includes/footer.php'; ?>
