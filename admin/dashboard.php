<?php
require '../config/database.php';
require 'header.php';

if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
    exit();
}
// Fetch counts dynamically
$totalEvents = $conn->query("SELECT COUNT(*) FROM events")->fetchColumn();
$totaljobs = $conn->query("SELECT COUNT(*) FROM advertisement")->fetchColumn();
$newJobPosts = $conn->query("SELECT COUNT(*) FROM advertisement WHERE status='pending'")->fetchColumn();
$approvedJobPosts = $conn->query("SELECT COUNT(*) FROM advertisement WHERE status='active'")->fetchColumn();
$cancelledJobPosts = $conn->query("SELECT COUNT(*) FROM advertisement WHERE status IN ('cancelled','rejected')")->fetchColumn();
$totalJobRequests = $conn->query("SELECT COUNT(*) FROM job_applications")->fetchColumn();
$totalAlumni = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
?>


    <!-- Main Content -->

      <h3 class="mb-4">Dashboard</h3>
      <div class="row g-3">
        <div class="col-md-4">
          <div class="card p-3 text-center">
            <div class="icon-box text-success">📅</div>
            <h5>Total Events</h5>
            <p class="fs-4 fw-bold"><?= $totalEvents ?></p>
            <a href="events.php" class="btn btn-success btn-sm">View Details</a>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card p-3 text-center">
            <div class="icon-box text-success">📄</div>
            <h5>Total jobs/ Ads</h5>
            <p class="fs-4 fw-bold"><?= $totaljobs ?></p>
            <a href="jobs.php" class="btn btn-success btn-sm">View Details</a>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card p-3 text-center">
            <div class="icon-box text-success">✅</div>
            <h5>Approved Job Post</h5>
            <p class="fs-4 fw-bold"><?= $approvedJobPosts ?></p>
            <a href="jobs.php" class="btn btn-success btn-sm">View Details</a>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card p-3 text-center">
            <div class="icon-box text-success">❌</div>
            <h5>Cancelled / Rejected Job Post</h5>
            <p class="fs-4 fw-bold"><?= $cancelledJobPosts ?></p>
            <a href="jobs.php" class="btn btn-success btn-sm">View Details</a>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card p-3 text-center">
            <div class="icon-box text-success">📋</div>
            <h5>Total Job Request</h5>
            <p class="fs-4 fw-bold"><?= $totalJobRequests ?></p>
            <a href="jobs.php" class="btn btn-success btn-sm">View Details</a>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card p-3 text-center">
            <div class="icon-box text-success">🧍</div>
            <h5>Total Alumni Reg</h5>
            <p class="fs-4 fw-bold"><?= $totalAlumni ?></p>
            <a href="alumni.php" class="btn btn-success btn-sm">View Details</a>
          </div>
        </div>

  
      </div>
    </div>
<?php
require '../includes/footer.php';
?>