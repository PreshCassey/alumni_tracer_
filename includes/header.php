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

<?php
// session_start();
require_once __DIR__ . '../../admin/function.php'; 
require '../config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumex Portal</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">

    <!-- JS -->
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">

<!-- ==================== NAVBAR ==================== -->
<nav class="navbar navbar-expand-lg sticky-top" style="background: linear-gradient(90deg, royalblue, #e4b93d, silver);">
  <div class="container-fluid px-4">
    
    <!-- Brand -->
    <a class="navbar-brand d-flex align-items-center" href="alumni.php">
      <img src="../assets/images/nobglogo.png" alt="Alumex Logo" width="60" height="60" class="me-2">
      <span class="fw-bold text-white fs-5">ALUMEX</span>
    </a>

    <!-- Toggler -->
    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link text-white fw-semibold" href="alumni.php">Home</a></li>
        <li class="nav-item"><a class="nav-link text-white fw-semibold" href="directory.php">Alumni</a></li>
        <li class="nav-item"><a class="nav-link text-white fw-semibold" href="events.php">Events</a></li>
        <li class="nav-item"><a class="nav-link text-white fw-semibold" href="view_advertisements.php">Jobs</a></li>
        <li class="nav-item"><a class="nav-link text-white fw-semibold" href="inbox.php">Messages</a></li>
        <li class="nav-item"><a class="nav-link text-white fw-semibold" href="profile.php">My Account</a></li>
        <li class="nav-item"><a class="nav-link text-white fw-semibold" href="feedback.php">Feedback</a></li>
      </ul>
      <a href="../auth/logout.php" class="btn btn-danger btn-sm ms-lg-3 px-3 fw-semibold rounded-pill">Logout</a>
    </div>
  </div>
</nav>
<!-- ==================== /NAVBAR ==================== -->


   
</body>
</html>
 
