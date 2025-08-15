<?php
session_start();
require_once __DIR__ . '../../admin/function.php'; 
require '../config/database.php';

if (isset($_GET['query'])) {
    $search = "%" . $_GET['query'] . "%";
    $stmt = $conn->prepare("SELECT * FROM users WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ?");
    $stmt->execute([$search, $search, $search]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $alumni) {
        echo "<p>" . $alumni['first_name'] . " " . $alumni['last_name'] . " - " . $alumni['email'] . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Alumni</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
    <script src="../assets/js/bootstrap.min.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>

<body class="bg-light">
  <!-- Navbar/Header -->

<nav class="navbar navbar-expand-lg navbar-light bg-success sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="alumni.php">
        <img src="../assets/images/Logo (2).png" width="70" height="70">
        <span class="text-white">ALUMNI CONNECT.</span></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse text-right" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link text-white" href="alumni.php">Home</a></li>

          <li class="nav-item"><a class="nav-link text-white" href="directory.php">Alumni</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="events.php">Events</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="view_advertisements.php">Jobs</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="inbox.php">Messages</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="profile.php">My Account</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="feedback.php">Feedback</a></li>

        </ul>
        <a class="btn btn-danger btn-sm" href="../auth/logout.php">Logout</a>
      </div>
    </div>
  </nav>
   
</body>
</html>
 
