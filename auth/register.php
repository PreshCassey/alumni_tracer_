<?php
require '../config/database.php';
$matric_no_error = $first_name_error = $last_name_error = $password_error = $email_error ='';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $grad_year = $_POST['graduation_year'];
    $matric_no = $_POST['matric_no'];
    $course = $_POST['course'];

     // Validate Matric number
        if (!preg_match('/^GFU\/\d{2}\/[A-Z]{3}\/\d{3}$/i', $matric_no)) {
            $matric_no_error = "Matric number must start with 'GFU/' followed by two digits, a forward slash, three alphabets, another forward slash, and three digits (e.g., GFU/21/IFT/002)";
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

         // Validate password
        if (strlen($password) < 8) {
            $password_error = "Password must be at least 8 characters long.";
        }



    if (empty($matric_no_error) && empty($first_name_error) && empty($last_name_error) && empty($password_error)) {            

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, graduation_year, matric_no, course) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$fname, $lname, $email, $password, $grad_year, $matric_no, $course])) {
            echo "<script>alert('Registration successful'); location.href='login.php';</script>";
    } else {
            echo "<script>alert('Failed to register. Try again.');</script>";
    }
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
</head>
<body>
<div class="container py-5">
            <a class="navbar-brand" href="../index.php">
          <img src="../assets/images/Logo (2).png" alt="" srcset="" width="50" height="50">
          <span class="text-success font-weight-bold">ALUMNI CONNECT</span>
        </a>

    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow">
          <div class="card-body">
                <h3 class="text-center mb-4">Create an Alumni Account</h3>
                <form method="POST">
                    <div class="mb-4">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="first_name" placeholder="First Name" required>
                        <span class="text-danger"><?php echo $first_name_error; ?></span>

                    </div>
                    <div class="mb-4">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="last_name" placeholder="Last Name" required>
                        <span class="text-danger"><?php echo $last_name_error; ?></span>

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
                       <span class="text-danger"><?php echo $matric_no_error; ?></span>

                    </div>
            
                    <div class="mb-4">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input class="form-control" type="email" name="email" placeholder="Email" required>    
                         <span class="text-danger"><?php echo $email_error; ?></span>

                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input class="form-control" type="password" name="password" placeholder="Password" required>
                        <span class="text-danger"><?php echo $password_error; ?></span>

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