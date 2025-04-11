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
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
</head>
<body>
  <meta charset="UTF-8">
  <title>Dashboard Form</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <!-- Navbar/Header -->
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
     <div class="container-fluid">
        <a class="navbar-brand" href="#">ALUMNI CONNECT.</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-right" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="alumni/directory.php">Alumni Directory</a></li>
            <li class="nav-item"><a class="nav-link" href="messages/inbox.php">Messages</a></li>
            <li class="nav-item"><a class="nav-link" href="events/list_events.php">Events</a></li>
            <li class="nav-item"><a class="nav-link" href="career/progress.php">Career Progress</a></li>
            <li class="nav-item"><a class="nav-link" href="resume/upload.php">Upload Resume</a></li>
            <li class="nav-item"><a class="nav-link" href="surveys/feedback.php">Feedback</a></li>
          </ul>
        </div>
      </div>
    </nav>

<div class="container">
    <form class="d-flex" method="GET">
      <input class="form-control me-2" name="query" type="search" placeholder="Search alumni..." aria-label="Search">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>

    <h1 class="text-center my-4">Welcome to Greenfield University Alumni Tracer</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <h2 class="mb-4">Hello, <?php echo $_SESSION['user_name']; ?>! 👋</h2>

        <?php else: ?>
      <?php  header("Location: ../index.php"); ?>
    <?php endif; ?>
</div>    
</body>
</html>
 
