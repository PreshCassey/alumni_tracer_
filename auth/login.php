<?php
session_start();
require_once __DIR__ . '../../admin/function.php'; 
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, first_name, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['first_name'];
        logAction($conn, $user['id'], 'User Login', 'Successful login');
        header('Location: ../alumni/alumni.php');
        exit();
    } else {
        $error = 'Invalid email or password';
        logAction($conn, null, 'Failed User Login', "Attempted Email: {$email}");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ALUMEX | Login</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
</head>
<body class="login-area1">

  <div class="container py-5 my-5">
    <div class="text-center mb-4">
      <a href="../index.php">
        <img src="../assets/images/nobglogo.png" alt="ALUMEX Logo" width="120">
      </a>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-4">
          <div class="card-body p-5">
            <h3 class="text-center mb-4 text-blue fw-bold">Welcome Back</h3>
            <p class="text-center text-muted mb-4">Login to continue your Alumni journey.</p>

            <?php if (!empty($error)) : ?>
              <div class="alert alert-danger text-center py-2"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" novalidate>
              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="you@example.com" required>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control form-control-lg" id="password" name="password" required>
              </div>

              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
              </div>

              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary py-2 fw-semibold rounded-pill">
                  Login
                </button>
              </div>

              <div class="text-center mt-3">
                <small class="text-muted">
                  Don’t have an account?
                  <a href="register.php" class="text-gold text-decoration-none fw-semibold">Register</a>
                </small>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
