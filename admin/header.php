<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
      <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
      <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <script src="../assets/js/bootstrap.min.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      min-height: 100vh;
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
    .card h5 {
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
    }
    .icon-box {
      font-size: 2rem;
      margin-bottom: 10px;
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
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
      <div class="text-center mb-4">
        <img src="../assets/images/profile_picture.jpg" class="rounded-circle" alt="Admin" width="50" height="50">
        <h6 class="mt-2"><?php echo $admin; ?></h6>
        <p class="mb-0 small"></p>
      </div>
      <a href="dashboard.php">Dashboard</a>
      <a href="reports.php">Report</a>
      <a href="events.php">Events</a>
      <a href="alumni.php">Alumni List</a>
      <a href="jobs.php">Job Posts</a>
      <a class="btn btn-danger" href="../auth/logout.php">Logout</a>

    </div>

    <div class="col-md-10 p-4">
