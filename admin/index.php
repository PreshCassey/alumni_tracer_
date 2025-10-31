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
$totalAlumni = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
?>


    <!-- Main Content -->
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-semibold text-primary">Admin Dashboard</h4>
    <a class="btn btn-danger btn-sm" href="../auth/logout.php">Log out</a>
    </div>

      <div class="row g-3">

        <div class="col-md-4">
          <div class="card p-3 text-center">
            <div class="icon-box text-blue">🧍</div>
            <h5>Total Alumni Registration</h5>
            <p class="fs-4 fw-bold"><?= $totalAlumni ?></p>
            <a href="alumni.php" class="btn btn-gold btn-sm">View Details</a>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card p-3 text-center">
            <div class="icon-box text-blue">📄</div>
            <h5>Jobs / Ads Management</h5>
            <p class="fs-4 fw-bold"><?= $totaljobs ?></p>
            <a href="jobs.php" class="btn btn-gold btn-sm">View Details</a>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="card p-3 text-center">
            <div class="icon-box text-blue">📅</div>
            <h5>Events Management</h5>
            <p class="fs-4 fw-bold"><?= $totalEvents ?></p>
            <a href="events.php" class="btn btn-gold btn-sm">View Details</a>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card p-3 text-center">
            <div class="icon-box text-blue">📄</div>
            <h5>Reports</h5>
            <a href="reports.php" class="btn btn-gold btn-sm">View Report</a>
          </div>
        </div>






  
      </div>
    </div>

<?php require 'footer.php'; ?>
