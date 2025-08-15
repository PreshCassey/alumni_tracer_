<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <script src="../assets/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      background-color: #063b1d; /* Dark green */
      color: white;
      padding-top: 1rem;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 10px 15px;
      border-radius: 5px;
      margin: 5px 0;
    }
    .sidebar a:hover {
      background-color: #065c2a; /* Lighter green hover */
    }
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .icon-box {
      font-size: 2rem;
      margin-bottom: 10px;
    }
    /* Hide sidebar on small screens (use offcanvas instead) */
    @media (max-width: 768px) {
      .sidebar-fixed {
        display: none;
      }
    }
  </style>
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}
$admin = $_SESSION['admin_username'];
?>

<!-- Navbar for mobile toggle -->
<nav class="navbar navbar-dark bg-dark d-md-none">
  <div class="container-fluid">
    <button class="btn btn-success" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
      ☰ Menu
    </button>
    <span class="navbar-text text-white"><?php echo $admin; ?></span>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <!-- Desktop Sidebar -->
    <div class="col-md-2 sidebar sidebar-fixed d-none d-md-block">
      <div class="text-center mb-4">
        <img src="../assets/images/profile_picture.jpg" class="rounded-circle" alt="Admin" width="50" height="50">
        <h6 class="mt-2"><?php echo $admin; ?></h6>
      </div>
      <a href="index.php">Dashboard</a>
      <a href="reports.php">Report</a>
      <a href="events.php">Events</a>
      <a href="alumni.php">Alumni List</a>
      <a href="jobs.php">Job Posts</a>
      <a class="btn btn-danger" href="../auth/logout.php">Logout</a>
    </div>

    <!-- Mobile Sidebar (Offcanvas) -->
    <div class="offcanvas offcanvas-start sidebar" tabindex="-1" id="mobileSidebar">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <div class="text-center mb-4">
          <img src="../assets/images/profile_picture.jpg" class="rounded-circle" alt="Admin" width="50" height="50">
          <h6 class="mt-2"><?php echo $admin; ?></h6>
        </div>
        <a href="index.php" data-bs-dismiss="offcanvas">Dashboard</a>
        <a href="reports.php" data-bs-dismiss="offcanvas">Report</a>
        <a href="events.php" data-bs-dismiss="offcanvas">Events</a>
        <a href="alumni.php" data-bs-dismiss="offcanvas">Alumni List</a>
        <a href="jobs.php" data-bs-dismiss="offcanvas">Job Posts</a>
        <a class="btn btn-danger" href="../auth/logout.php" data-bs-dismiss="offcanvas">Logout</a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 p-4">
