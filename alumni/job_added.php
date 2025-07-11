<?php
include '../includes/header.php';
require '../config/database.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$user_id = $_SESSION['user_id'] ?? 0;

// Pagination Setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Total jobs posted
$total_posted = $conn->query("SELECT COUNT(*) FROM advertisement WHERE posted_by = $user_id")->fetchColumn();
$total_applied =$conn->query("SELECT COUNT(*) FROM job_applications j JOIN advertisement a ON j.job_id = a.id WHERE j.user_id = $user_id")->fetchColumn();

// Jobs applied for
$stmt = $conn->prepare("SELECT j.*, a.title, a.description, a.category, a.advertiser FROM job_applications j JOIN advertisement a ON j.job_id = a.id WHERE j.user_id; = ? LIMIT 10");
$stmt->execute([$user_id]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Posted jobs with pagination
$stmt = $conn->prepare("SELECT * FROM advertisement WHERE posted_by = ? ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
if($stmt->execute([$user_id])){
$jobs = $stmt->fetchAll();
} 
else{
    echo "NO JOBS ADDED!!";
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_job_id'])) {
  $stmt = $conn->prepare("DELETE FROM advertisement WHERE id = ? AND posted_by = ?");
  $stmt->execute([$_POST['delete_job_id'], $user_id]);
   echo "<script>alert('deleted successfully!');</script>";
}

// Handle cancel application
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_app_id'])) {
  $stmt = $conn->prepare("DELETE FROM job_applications WHERE id = ? AND user_id = ?");
  $stmt->execute([$_POST['cancel_app_id'], $user_id]);
  // Optionally log reason in a `cancelled_applications` table
     echo "<script>alert('deleted successfully!');</script>";

}


?>

<div class="container p-4">
  <h3 class="mb-4">📌 Jobs You've Posted (<?= $total_posted ?>)</h3>
     <?php foreach ($jobs as $job): ?>
         <div class="list-group mb-3">
      <div class="list-group-item flex-column justify-content-between">
      <div class="row g-0">
         <div class="col-md-4">
          <img src="../uploads/<?= htmlspecialchars($job['photo']) ?>" class="img-fluid rounded-circle thumbnail m-3" alt="Ad Image" width="100" height="100">
        </div>
        <div class="col-md-6">
          <h5 class=""><?= htmlspecialchars($job['title']) ?></h5>
          <p class=""><?= htmlspecialchars($job['description']) ?></p>
          <p><small class="text-muted">Category: <?= htmlspecialchars($job['category']) ?> | Status: <?= htmlspecialchars($job['status']) ?></small></p>
          <small>Posted on <?= $job['created_at'] ?></small><br>

          <a href="<?= htmlspecialchars($job['button_link']) ?>" class="btn btn-outline-success"><?= htmlspecialchars($job['button_message']) ?></a>
        </div>        
        <div class="col-md-2 my-3">
          <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteJobModal<?= $job['id'] ?>">Delete</button>
        </div>

        <!-- Delete Job Modal -->
        <div class="modal fade" id="deleteJobModal<?= $job['id'] ?>" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header"><h5>Confirm Deletion</h5></div>
              <div class="modal-body">Are you sure you want to delete this job?</div>
              <div class="modal-footer">
                <form method="post">
                  <input type="hidden" name="delete_job_id">
                  <button type="submit" class="btn btn-danger">Yes, Delete</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
              </div>
            </div>
          </div>
        </div> 

      </div>
    </div>
    </div>

 <?php endforeach; ?>
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
  <h3 class="mt-5 mb-4">📝 Jobs You've Applied For (<?= $total_applied ?>)</h3>
  <?php foreach($applications as $index => $app): ?>
    <div class="card mb-3">
      <div class="card-body">
        <h5><?= htmlspecialchars($app['title']) ?></h5>
        <p><?= htmlspecialchars($app['description']) ?></p>
        <p><?= htmlspecialchars($app['category']) ?> | <?= htmlspecialchars($app['advertiser']) ?></p>
        <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal<?= $app['id'] ?>">Cancel Application</button>
      </div>
    </div>
    <div class="modal fade" id="cancelModal<?= $app['id'] ?>" tabindex="-1">
      <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header"><h5>Reason for Cancellation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form method="post">
              <input type="hidden" name="cancel_app_id">
              <textarea name="cancel_reason" required class="form-control" placeholder="Why are you cancelling?"></textarea>

              <div class="modal-footer">
                <button type="submit" class="btn btn-warning">Cancel Application</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>


  

<?php include '../includes/footer.php' ?>