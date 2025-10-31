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
  background-color: #f5f7fb;
  color: #333;
  font-family: "Poppins", sans-serif;
}

.sidebar {
  background: linear-gradient(180deg, #002b5b 0%, #001f3f 100%);
  color: white;
  padding-top: 1rem;
}

.sidebar a {
  color: #f8f9fa;
  text-decoration: none;
  display: block;
  padding: 10px 15px;
  border-radius: 8px;
  margin: 5px 0;
  transition: all 0.3s ease;
}

.sidebar a:hover {
  background-color: #0d47a1;
  color: #d4af37;
  transform: translateX(3px);
}

.card {
  border: none;
  border-radius: 15px;
  background: white;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s ease;
}
.card:hover {
  transform: translateY(-4px);
}

.btn-gold {
  background-color: #d4af37;
  border: none;
  color: #001f3f;
  font-weight: 500;
}
.btn-gold:hover {
  background-color: #001f3f;
  color: #d4af37;
}
.text-blue { color: #001f3f}
.bg-blue{background-color: #001f3f;}

.navbar-dark.bg-dark {
  background: #001f3f !important;
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
    body {
  animation: fadeIn 0.6s ease-in-out;
}
@keyframes fadeIn {
  from {opacity: 0;}
  to {opacity: 1;}
}
  </style>
</head>
<body>
<?php
session_start();
require_once __DIR__ . '/function.php'; 

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}
$admin = $_SESSION['admin_username'];
?>

<!-- Navbar for mobile toggle -->
<nav class="navbar navbar-dark bg-dark d-md-none">
  <div class="container-fluid">
    <button class="btn btn-gold" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
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
        <img src="../uploads/default.jpg" class="rounded-circle" alt="Admin" width="50" height="50">
        <h6 class="mt-2"><?php echo $admin; ?></h6>
      </div>
      <a href="index.php">Dashboard</a>
      <a href="alumni.php">Manage Alumni</a>
      <a href="events.php">Events</a>
      <a href="jobs.php">Job Posts</a>
      <a href="reports.php">Report</a>
      <a href="admin_audit_log.php">Security Audit</a>
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
