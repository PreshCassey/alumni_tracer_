<?php include '../includes/header.php' ?>
<?php
if (!isset($_SESSION['user_id'])) {
     echo "<div class='alert alert-danger'><a href='../auth/login.php'>Please log in.</a></div>";
    include '../includes/footer.php';
    exit();
}
?>

  <style>
    .hero {
      background: url('../assets/images/back2.jpg') center/cover no-repeat;
      color: white;
      padding: 100px 0;
      text-align: center;
    }
    .hero h1 {
      font-weight: bold;
      text-transform: uppercase;
    }
    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      transition: 0.3s;
    }
    .circle-icon {
      background-color: #e9f6ef;
      width: 80px;
      height: 80px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      font-size: 2rem;
    }
  </style>

<!-- Hero Section -->
<div class="hero">
  <div class="container">
    <h1>Congratulations,</h1>
    <p class="lead">and welcome to the GreenField Alumni Community!</p>
  </div>
</div>

<div class="container py-5">
  
    <?php if (isset($_SESSION['user_id'])): ?>
        <h2 class="mb-3 mt-4">Hello, <?php echo $_SESSION['user_name']; ?>! 👋</h2>

        <?php else: ?>
      <?php  header("Location: ../index.php"); ?>
    <?php endif; ?>


  <div class="row g-4">
    <!-- Alumni Directory -->
    <div class="col-md-4">
      <div class="card text-center p-4 card-hover">
        <div class="circle-icon text-success mb-3">
          🎓
        </div>
        <h5 class="fw-bold">ALUMNI DIRECTORY</h5>
        <p class="text-success fw-bold">Connect Across Our Community</p>
        <a href="directory.php" class="text-decoration-none text-success fw-bold">All Alumni →</a>
      </div>
    </div>

    <!-- message Calls -->
    <div class="col-md-4">
      <div class="card text-center p-4 card-hover">
        <div class="mb-3">
          📩
        </div>
        <h5 class="fw-bold">Messages</h5>
        <p class="text-success fw-bold">Check your inbox and connect with old classmates.</p>
        <a href="inbox.php" class="text-decoration-none text-success fw-bold">Go to Messages</a>
      </div>
    </div>

    <!-- message Calls -->
    <div class="col-md-4">
      <div class="card text-center p-4 card-hover">
        <div class="mb-3">
          📃
        </div>
        <h5 class="fw-bold">Jobs/ Advertisment</h5>
        <p class="text-success fw-bold">Check for job listing or advertisment</p>
        <a href="view_advertisement.php" class="text-decoration-none text-success fw-bold">Jobs -></a>
    </div>


  </div>


  <div class="row g-4">
    <!-- Upcoming Events -->
    <div class="col-md-4">
      <div class="card text-center p-4 card-hover">
        <div class="circle-icon text-success mb-3">
          📅
        </div>
        <h5 class="fw-bold">UPCOMING EVENTS</h5>
        <p class="text-muted">No event found!</p>
        <a href="events.php" class="text-decoration-none text-success fw-bold">All Events →</a>
      </div>
    </div>

    <!-- progile -->
    <div class="col-md-4">
      <div class="card text-center p-4 card-hover">
        <div class="circle-icon text-success mb-3">
          👥
        </div>
        <h5 class="fw-bold">View Profile</h5>
        <p class="text-muted">Highlight your latest professional achievements and relevant skills</p>
        <a href="profile.php" class="text-decoration-none text-success fw-bold">View profile →</a>
      </div>
    </div>

  </div>

       <!-- Placeholder cards -->
    <hr><br><hr>

    <div class="text-center mt-5">
      <a href="../auth/logout.php" class="btn btn-danger rounded-pill px-4">Logout</a>
    </div>



  <?php include '../includes/footer.php' ?>
