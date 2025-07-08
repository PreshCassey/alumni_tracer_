<?php
session_start();
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <!-- Navbar/Header -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed mb-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="alumni.php">
        <img src="../assets/images/Logo (2).png" width="70" height="70">
        <span class="">ALUMNI CONNECT.</span></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse text-right" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link " href="directory.php">View Alumni</a></li>
          <li class="nav-item"><a class="nav-link " href="events.php">Events</a></li>
          <li class="nav-item"><a class="nav-link " href="view_advertisements.php">Job</a></li>
          <li class="nav-item"><a class="nav-link " href="feedback.php">Feedback</a></li>
          <li class="nav-item"><a class="nav-link " href="inbox.php">Messages</a></li>
          <li class="nav-item"><a class="nav-link " href="profile.php">Profile</a></li>
        </ul>
      </div>
    </div>
  </nav>
   
  <div class="container my-3">
      <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a class="breadcrumb-link text-secondary link-underline link-underline-opacity-0" href="alumni.php">Go to Home</a></li>
              <li class="breadcrumb-item breadcrumb-active" aria-current="page">Current</li>
          </ol>
      </nav>
  </div>
</body>
</html>
 
