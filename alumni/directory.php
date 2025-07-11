<?php
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'>Please log in.</div>";
    include '../includes/footer.php';
    exit();
}

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
              <p class="mb-1">Course: <?= htmlspecialchars($alum['course']) ?></p>
              <p class="mb-0">Class of <?= htmlspecialchars($alum['graduation_year']) ?> | <?= htmlspecialchars($alum['course']) ?></p>
              <small class="text-muted"><?= htmlspecialchars($alum['job_position']) ?> at <?= htmlspecialchars($alum['company']) ?></small>
            </div>
            <a href="inbox.php?chat_with=<?= $alum['id'] ?>" class="btn btn-outline-success ms-3">Message</a>
          </div>
          <button class="btn btn-outline-success mt-2" data-bs-toggle="modal" data-bs-target="#alumniModal<?= $alum['id'] ?>">View Profile</button>
        </div>
      </div>

            <!-- Modal -->
      <div class="modal fade" id="alumniModal<?= $alum['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Alumni Details</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <?php
                $detailStmt = $conn->prepare("SELECT * FROM users u LEFT JOIN user_details ud ON u.id = ud.user_id WHERE u.id = ?");
                $detailStmt->execute([$alum['id']]);
                $detail = $detailStmt->fetch();
              ?>
              <div class="row">
                <div class="col-md-4 text-center">
                  <img src="../uploads/<?= $detail['profile_image'] ?: 'default.png' ?>" class="rounded-circle mb-3" width="120" height="120" alt="Profile">
                  <h5><?= htmlspecialchars($detail['first_name'] . ' ' . $detail['last_name']) ?></h5>
                  <p class="text-muted">Matric No: <?= htmlspecialchars($detail['matric_no']) ?></p>
                  <p>Email: <?= htmlspecialchars($detail['email']) ?></p>
                </div>
                <div class="col-md-8">
                  <ul class="list-group">
                    <li class="list-group-item"><strong>Course:</strong> <?= htmlspecialchars($detail['course']) ?></li>
                    <li class="list-group-item"><strong>Graduation Year:</strong> <?= $detail['graduation_year'] ?></li>
                    <li class="list-group-item"><strong>Date of Birth:</strong> <?= $detail['dob'] ?></li>
                    <li class="list-group-item"><strong>Gender:</strong> <?= $detail['gender'] ?></li>
                    <li class="list-group-item"><strong>Phone:</strong> <?= $detail['contact_number'] ?></li>
                    <li class="list-group-item"><strong>Home Town:</strong> <?= $detail['hometown'] ?></li>
                    <li class="list-group-item"><strong>Current Location:</strong> <?= $detail['current_location'] ?></li>
                    <li class="list-group-item"><strong>Job Position:</strong> <?= $detail['job_position'] ?></li>
                    <li class="list-group-item"><strong>Company:</strong> <?= $detail['company'] ?></li>
                    <li class="list-group-item"><strong>Qualification:</strong> <?= $detail['qualification'] ?></li>
                    <li class="list-group-item"><strong>Resume:</strong> 
                      <?php if ($detail['resume']): ?>
                        <a href="../uploads/<?= $detail['resume'] ?>" target="_blank">View Resume</a>
                      <?php else: ?>
                        Not uploaded
                      <?php endif; ?>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
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


<?php



