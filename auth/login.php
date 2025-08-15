<?php
session_start();
require_once __DIR__ . '../../admin/function.php'; 
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, first_name, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['first_name'];
        header('Location: ../alumni/alumni.php');
        logAction($conn, $user['id'], 'User Login', 'Successful login');

        exit();
    } else {
      // when login fails
        $error = 'Invalid email or password';
        logAction($conn, null, 'Failed User Login', "Username: {$email}");

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
<div class="container py-5 my-5">
          <a class="navbar-brand" href="../index.php">
          <img src="../assets/images/Logo (2).png" alt="" srcset="" width="50" height="50">
          <span class="text-success font-weight-bold">ALUMNI CONNECT</span>
        </a>
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow p-4">
          <div class="card-body p-5">
            <h3 class="text-center mb-4">Login to Alumni Connect</h3>
             <p class= "alert alert-danger"><?php echo $error ?? ''; ?></p>
            <form method="post">
              <div class="mb-3">
                <label for="email" class="form-label">Email Address<span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-success border-0 px-5 rounded-pill text-white">Login</button>
              </div>
              <div class="text-center mt-3">
                <small>Don't have an account? <a href="register.php">Register</a></small>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

   
</body>
</html>
