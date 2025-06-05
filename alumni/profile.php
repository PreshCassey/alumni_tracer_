<?php
session_start();
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$alumni = $stmt->fetch(PDO::FETCH_ASSOC);
?>
 <div class="container-fluid">
      <div class="card card-body shadow mx-4 overflow-hidden">
        <div class="row">
         <img src="" alt="">
          <div class="col-auto my-auto">
              <h5 class="mb-1">                           
                <h2>Welcome, <?php echo $alumni['first_name']; ?>!</h2>
                <p>Email: <?php echo $alumni['email']; ?></p>
                <p>Graduation Year: <?php echo $alumni['graduation_year']; ?></p>
              </h5>
          </div>
        </div>
      </div>
    </div>
  <?php include '../includes/footer.php' ?>
