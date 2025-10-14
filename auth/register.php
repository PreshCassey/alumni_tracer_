<?php
require '../config/database.php';

$matric_no_error = $first_name_error = $last_name_error = $password_error = $email_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = trim($_POST['first_name']);
    $lname = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $plain_password = $_POST['password'];
    $grad_year = $_POST['graduation_year'];
    $matric_no = strtoupper(trim($_POST['matric_no']));
    $course = trim($_POST['course']);

    // Validate Matric number format
    if (!preg_match('/^GFU\/\d{2}\/[A-Z]{3}\/\d{3}$/', $matric_no)) {
        $matric_no_error = "Matric number must follow the format: GFU/21/IFT/002";
    }

    // Check if matric exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE matric_no = ?");
    $stmt->execute([$matric_no]);
    if ($stmt->fetchColumn() > 0) {
        $matric_no_error = "Matric number already exists.";
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        $email_error = "Email already exists.";
    }

    // Validate First name
    if (!preg_match('/^[a-zA-Z]+$/', $fname)) {
        $first_name_error = "First name must contain only letters.";
    }

    // Validate Last name
    if (!preg_match('/^[a-zA-Z]+$/', $lname)) {
        $last_name_error = "Last name must contain only letters.";
    }

    // Validate Password (before hashing)
    if (strlen($plain_password) < 8) {
        $password_error = "Password must be at least 8 characters long.";
    }

    // Proceed if all fields are valid
    if (
        empty($matric_no_error) && 
        empty($first_name_error) && 
        empty($last_name_error) && 
        empty($password_error) && 
        empty($email_error)
    ) {
        $hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, graduation_year, matric_no, course) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$fname, $lname, $email, $hashed_password, $grad_year, $matric_no, $course])) {
            echo "<script>alert('Registration successful! You can now log in.'); location.href='login.php';</script>";
        } else {
            echo "<script>alert('Failed to register. Please try again later.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ALUMEX | Register</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
</head>
<body class="login-area1">

<div class="container py-5 my-4">
  <div class="text-center mb-4">
    <a href="../index.php">
      <img src="../assets/images/nobglogo.png" alt="ALUMEX Logo" width="120">
    </a>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
          <h3 class="text-center mb-4 text-blue fw-bold">Join ALUMEX</h3>
          <p class="text-center text-muted mb-4">Create your alumni profile to stay connected.</p>

          <form method="POST" novalidate>
            <div class="mb-3">
              <label class="form-label">First Name <span class="text-danger">*</span></label>
              <input type="text" name="first_name" class="form-control form-control-lg" required>
              <small class="text-danger"><?php echo $first_name_error; ?></small>
            </div>

            <div class="mb-3">
              <label class="form-label">Last Name <span class="text-danger">*</span></label>
              <input type="text" name="last_name" class="form-control form-control-lg" required>
              <small class="text-danger"><?php echo $last_name_error; ?></small>
            </div>

            <div class="mb-3">
              <label class="form-label">Course <span class="text-danger">*</span></label>
              <input type="text" name="course" class="form-control form-control-lg" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Graduation Year <span class="text-danger">*</span></label>
              <input type="number" name="graduation_year" class="form-control form-control-lg" min="2000" max="2099" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Matric Number <span class="text-danger">*</span></label>
              <input type="text" name="matric_no" class="form-control form-control-lg" placeholder="GFU/21/IFT/002" required>
              <small class="text-danger"><?php echo $matric_no_error; ?></small>
            </div>

            <div class="mb-3">
              <label class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control form-control-lg" required>
              <small class="text-danger"><?php echo $email_error; ?></small>
            </div>

            <div class="mb-3">
              <label class="form-label">Password <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control form-control-lg" required>
              <small class="text-danger"><?php echo $password_error; ?></small>
            </div>

            <div class="d-grid mt-4">
              <button type="submit" class="btn btn-primary py-2 fw-semibold rounded-pill">Register</button>
            </div>

            <div class="text-center mt-3">
              <small class="text-muted">
                Already have an account?
                <a href="login.php" class="text-gold text-decoration-none fw-semibold">Login</a>
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
