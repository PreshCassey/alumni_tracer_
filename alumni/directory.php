<?php
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
     echo "<div class='alert alert-danger'><a href='../auth/login.php'>Please log in.</a></div>";
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

<style>
    body {
    background: linear-gradient(180deg, #f9fbff 0%, #eef3ff 100%);
    color: #1a1a1a;
  }

  .dir-hero {
    text-align: center;
    padding: 60px 0 30px;
  }

  .dir-hero .brand {
    display: inline-flex;
    align-items: center;
    gap: 0.8rem;
    background: rgba(255, 255, 255, 0.6);
    padding: 0.8rem 1.2rem;
    border-radius: 50px;
    box-shadow: 0 4px 18px rgba(13, 71, 161, 0.1);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(255, 255, 255, 0.5);
  }

  .dir-hero h1 {
    margin-top: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    color: var(--alumex-blue);
  }

  .floating-filters {
    max-width: 1100px;
    margin: -36px auto 28px;
    padding: 16px;
    display: grid;
    grid-template-columns: 1fr 220px 180px 140px;
    gap: 12px;
    align-items: center;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 14px;
    border: 1px solid rgba(13, 71, 161, 0.1);
    box-shadow: 0 6px 20px rgba(13, 71, 161, 0.08);
    backdrop-filter: blur(8px);
  }

  .floating-filters .form-control, .floating-filters .btn {
    height: 46px;
    border-radius: 10px;
    border: 1px solid rgba(13, 71, 161, 0.15);
    background: #fff;
    color: #1a1a1a;
  }

  .floating-filters .btn {
    background: linear-gradient(90deg, var(--alumex-blue), #1e63d0);
    color: white;
    border: none;
    transition: all 0.2s ease;
  }

  .floating-filters .btn:hover {
    transform: translateY(-2px);
    background: linear-gradient(90deg, #1e63d0, var(--alumex-blue));
  }

  .grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin-top: 20px;
  }

  .glass-card {
    background: rgba(255, 255, 255, 0.8);
    border-radius: 16px;
    border: 1px solid rgba(13, 71, 161, 0.1);
    padding: 18px;
    box-shadow: 0 4px 18px rgba(13, 71, 161, 0.08);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    backdrop-filter: blur(6px);
  }

  .glass-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 30px rgba(13, 71, 161, 0.15);
  }

  .card-top {
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid rgba(13, 71, 161, 0.2);
  }

  .avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .alum-meta h5 {
    margin: 0;
    font-weight: 600;
    color: var(--alumex-blue);
  }

  .alum-meta p {
    margin: 4px 0 0;
    color: #444;
    font-size: 0.9rem;
  }

  .alum-job {
    margin-top: 10px;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }

  .badge-role {
    background: #f4f7ff;
    border: 1px solid rgba(13, 71, 161, 0.15);
    border-radius: 999px;
    padding: 6px 10px;
    font-size: 0.85rem;
    color: #333;
  }

  .pagination .page-item .page-link {
    background: #fff;
    border: 1px solid rgba(13, 71, 161, 0.15);
    color: var(--alumex-blue);
    border-radius: 8px;
    margin: 0 4px;
  }

  .pagination .page-item.active .page-link {
    background: var(--alumex-blue);
    color: #fff;
    border: none;
  }

  .modal-content {
    background: #fff;
    color: #1a1a1a;
    border-radius: 16px;
    border: 1px solid rgba(13, 71, 161, 0.15);
  }
</style>

<div class="dir-hero">
  <div class="container">
    <div class="brand">
      <img src="../assets/images/nobglogo.png" alt="ALUMEX" width="100" height="100">
      <div style="text-align:left;">
        <div style="font-size:.9rem; color:var(--alumex-silver);">ALUMEX</div>
        <h1 style="font-size:1.6rem; margin:0;">Meet the ALUMEX Community</h1>
        <div style="font-size:.9rem; color:rgba(10, 7, 138, 0.6);">Connect, message & celebrate alumni</div>
     <br>
      </div>
    </div>
  </div>
</div>

<!-- Floating filter bar -->
<form method="get" class="floating-filters" aria-label="Alumni filters">
  <input type="text" name="search" class="form-control" placeholder="Search by name, e.g. Praise Peter" value="<?= htmlspecialchars($search) ?>">
  <input type="text" name="course" class="form-control" placeholder="Program / Course" value="<?= htmlspecialchars($course) ?>">
  <input type="number" name="graduation_year" class="form-control" placeholder="Graduation Year" value="<?= htmlspecialchars($grad_year) ?>">
  <div style="display:flex; gap:8px;">
    <button type="submit" class="btn">Apply</button>
    <a href="directory.php" class="btn btn-outline-silver" style="display:inline-flex; align-items:center; justify-content:center;">Reset</a>
  </div>
</form>

<div class="container py-4">
  <div class="grid">
    <?php foreach ($alumni as $alum): ?>
      <div class="glass-card" role="article" aria-labelledby="alum-<?= $alum['id'] ?>">
        <div class="card-top">
          <div class="avatar" aria-hidden="true">
            <img src="../uploads/<?= htmlspecialchars($alum['profile_image'] ?: 'default.jpg') ?>" alt="Profile of <?= htmlspecialchars($alum['first_name'] . ' ' . $alum['last_name']) ?>">
          </div>

          <div class="alum-meta">
            <h5 id="alum-<?= $alum['id'] ?>"><?= htmlspecialchars($alum['first_name'] . ' ' . $alum['last_name']) ?></h5>
            <p>Class of <?= htmlspecialchars($alum['graduation_year']) ?> • <?= htmlspecialchars($alum['course']) ?></p>

            <div class="alum-job">
              <span class="badge-role"><?= htmlspecialchars($alum['job_position'] ?: '—') ?></span>
              <span class="badge-role"><?= htmlspecialchars($alum['company'] ?: 'Independent') ?></span>
            </div>
          </div>
        </div>

        <div class="card-actions">
          <a href="inbox.php?chat_with=<?= $alum['id'] ?>" class="btn-alumex" title="Message <?= htmlspecialchars($alum['first_name']) ?>">Message</a>
          <button class="btn-outline-silver" data-bs-toggle="modal" data-bs-target="#alumniModal<?= $alum['id'] ?>">View Profile</button>
        </div>
      </div>

      <!-- Modal (keeps your existing detail query) -->
      <div class="modal fade" id="alumniModal<?= $alum['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content" style="background: linear-gradient(180deg, #c0c0c0, #d4af37); color: #071033; border: 1px solid rgba(212,169,55,0.06);">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.03);">
              <h5 class="modal-title">Alumni Details</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <?php
                $detailStmt = $conn->prepare("SELECT * FROM users u LEFT JOIN user_details ud ON u.id = ud.user_id WHERE u.id = ?");
                $detailStmt->execute([$alum['id']]);
                $detail = $detailStmt->fetch();
              ?>
              <div class="row">
                <div class="col-md-4 text-center">
                  <img src="../uploads/<?= htmlspecialchars($detail['profile_image'] ?: 'default.jpg') ?>" class="rounded-circle mb-3" width="140" height="140" alt="Profile">
                  <h5 class="mt-2"><?= htmlspecialchars($detail['first_name'] . ' ' . $detail['last_name']) ?></h5>
                  <p class="text-muted">Matric No: <?= htmlspecialchars($detail['matric_no']) ?></p>
                  <p class="text-muted"><?= htmlspecialchars($detail['email']) ?></p>
                </div>
                <div class="col-md-8">
                  <ul class="list-group" style="background:transparent; border:none;">
                    <li class="list-group-item" style="background: rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.03);"><strong>Course:</strong> <?= htmlspecialchars($detail['course']) ?></li>
                    <li class="list-group-item" style="background: rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.03);"><strong>Graduation Year:</strong> <?= htmlspecialchars($detail['graduation_year']) ?></li>
                    <li class="list-group-item" style="background: rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.03);"><strong>Date of Birth:</strong> <?= htmlspecialchars($detail['dob']) ?></li>
                    <li class="list-group-item" style="background: rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.03);"><strong>Gender:</strong> <?= htmlspecialchars($detail['gender']) ?></li>
                    <li class="list-group-item" style="background: rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.03);"><strong>Phone:</strong> <?= htmlspecialchars($detail['contact_number']) ?></li>
                    <li class="list-group-item" style="background: rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.03);"><strong>Home Town:</strong> <?= htmlspecialchars($detail['hometown']) ?></li>
                    <li class="list-group-item" style="background: rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.03);"><strong>Current Location:</strong> <?= htmlspecialchars($detail['current_location']) ?></li>
                    <li class="list-group-item" style="background: rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.03);"><strong>Job Position:</strong> <?= htmlspecialchars($detail['job_position']) ?></li>
                    <li class="list-group-item" style="background: rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.03);"><strong>Company:</strong> <?= htmlspecialchars($detail['company']) ?></li>
                    <li class="list-group-item" style="background: rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.03);"><strong>Qualification:</strong> <?= htmlspecialchars($detail['qualification']) ?></li>
                    <li class="list-group-item" style="background: rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.03);"><strong>Resume:</strong>
                      <?php if ($detail['resume']): ?>
                        <a href="../uploads/<?= htmlspecialchars($detail['resume']) ?>" target="_blank">View Resume</a>
                      <?php else: ?>
                        Not uploaded
                      <?php endif; ?>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid rgba(255,255,255,0.02);">
              <a href="inbox.php?chat_with=<?= $alum['id'] ?>" class="btn btn-alumex">Message</a>
              <button type="button" class="btn btn-outline-silver" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

    <?php endforeach; ?>
  </div>

  <!-- Pagination -->
  <nav aria-label="Alumni pages" class="mt-4">
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
