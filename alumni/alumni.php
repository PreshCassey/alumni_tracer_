<?php include '../includes/header.php'; ?>

<?php
if (!isset($_SESSION['user_id'])) {
  echo "<div class='alert alert-danger text-center mt-5'><a href='../auth/login.php' class='text-danger fw-bold'>Please log in.</a></div>";
  include '../includes/footer.php';
  exit();
}
?>

<style>
  .hero {
    background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.6)), url('../assets/images/back2.jpg') center/cover no-repeat;
    color: white;
    padding: 100px 0;
    text-align: center;
  }
  .hero h1 {
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
  }
  .card-hover {
    transition: 0.3s ease-in-out;
    border: none;
    border-radius: 15px;
  }
  .card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  }
  .circle-icon {
    background-color: #e8f0fa;
    color: royalblue;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 2rem;
    margin: 0 auto;
  }
  .fw-gold {
    color: #e4b93d;
  }
  .fw-silver {
    color: silver;
  }
</style>

<!-- Hero Section -->
<div class="hero">
  <div class="container">
    <h1>Welcome, <?php echo $_SESSION['user_name']; ?> 🎓</h1>
    <p class="lead fw-silver">Proud Member of the Greenfield Alumni Community</p>
  </div>
</div>

<!-- Main Section -->
<div class="container py-5">

  <div class="row g-4">

    <!-- Alumni Directory -->
    <div class="col-md-4">
      <div class="card text-center p-4 card-hover">
        <div class="circle-icon mb-3">🎓</div>
        <h5 class="fw-bold text-primary">Alumni Directory</h5>
        <p class="text-muted">Connect across our community.</p>
        <a href="directory.php" class="text-decoration-none fw-bold text-primary">Explore →</a>
      </div>
    </div>

    <!-- Messages -->
    <div class="col-md-4">
      <div class="card text-center p-4 card-hover">
        <div class="circle-icon mb-3">📩</div>
        <h5 class="fw-bold text-primary">Messages</h5>
        <p class="text-muted">Connect with old classmates.</p>
        <a href="inbox.php" class="text-decoration-none fw-bold text-primary">Inbox →</a>
      </div>
    </div>

    <!-- Jobs / Advertisement -->
    <div class="col-md-4">
      <div class="card text-center p-4 card-hover">
        <div class="circle-icon mb-3">📃</div>
        <h5 class="fw-bold text-primary">Jobs / Advertisement</h5>
        <p class="text-muted">Post or explore career listings.</p>
        <a href="view_advertisements.php" class="text-decoration-none fw-bold text-primary">View →</a>
      </div>
    </div>

  </div>

  <div class="row g-4 mt-3">

    <!-- Upcoming Events -->
    <div class="col-md-4">
      <div class="card text-center p-4 card-hover">
        <div class="circle-icon mb-3">📅</div>
        <h5 class="fw-bold text-primary">Upcoming Events</h5>
        <p class="text-muted">View and add upcoming events!</p>
        <a href="events.php" class="text-decoration-none fw-bold text-primary">See All →</a>
      </div>
    </div>

    <!-- Profile -->
    <div class="col-md-4">
      <div class="card text-center p-4 card-hover">
        <div class="circle-icon mb-3">👥</div>
        <h5 class="fw-bold text-primary">Profile</h5>
        <p class="text-muted">Update your achievements and skills.</p>
        <a href="profile.php" class="text-decoration-none fw-bold text-primary">My Profile →</a>
      </div>
    </div>

  </div>

  <hr class="my-5">

  <!-- Logout -->
  <div class="text-center mt-4">
    <a href="../auth/logout.php" class="btn btn-danger rounded-pill px-4 fw-bold">Logout</a>
  </div>

</div>

<?php include '../includes/footer.php'; ?>
