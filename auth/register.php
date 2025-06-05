<?php
require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $course = $_POST['course'];
    $grad_year = $_POST['graduation_year'];
    $matric_no = $_POST['matric_no'];

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, course, graduation_year, matric_no) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$fname, $lname, $email, $password, $course, $grad_year, $matric_no])) {
        echo "Registration successful!";
    } else {
        echo "Error registering user.";
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
<div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow">
          <div class="card-body">
                <h3 class="text-center mb-4">Create an Alumni Account</h3>
                <form method="POST">
                    <div class="mb-4">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="first_name" placeholder="First Name" required>
                    </div>
                    <div class="mb-4">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="last_name" placeholder="Last Name" required>
                    </div>
                    <div class="mb-4">
                        <label for="course" class="form-label">Course <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="course" placeholder="Course">
                    </div>
                    <div class="mb-4">
                        <label for="graduation_year" class="form-label">Graduation year <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" name="graduation_year" placeholder="Graduation Year">
                    </div>
                    <div class="mb-4">
                        <label for="matric_no" class="form-label">Matric Number:<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="matric_no" placeholder="Matric Number">
                    </div>
            
                    <div class="mb-4">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input class="form-control" type="email" name="email" placeholder="Email" required>    
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input class="form-control" type="password" name="password" placeholder="Password" required>
                    </div>

                    <button class="px-5 py-3 btn btn-success border-0 rounded-pill text-white" id="form_submit" type="submit" >Register</button>
                
                    <div class="form-footer text-center my-3">
                        <p class="text-muted">Already have an account? <a href="login.php" class="text-success">Sign in</a></p>
                    </div>
                </form>              
          </div>
       </div>
      </div>
    </div>
  
</div>


</body>
</html>