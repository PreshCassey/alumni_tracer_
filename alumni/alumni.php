<?php include '../includes/header.php' ?>
<?php
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'>Please log in.</div>";
    include '../includes/footer.php';
    exit();
}
?>
<div class="container">
  
    <p class="text-center my-4 display-4">Welcome to Greenfield University Alumni Connect</p>
    <form class="d-flex my-4" method="GET">
      <input class="form-control me-2" name="query" type="search" placeholder="Search by name" aria-label="Search">
      <input class="form-control mx-2" name="query" type="search" placeholder="grad year" aria-label="Search">
      <input class="form-control mx-2" name="query" type="search" placeholder="course" aria-label="Search">
      <input class="form-control mx-2" name="query" type="search" placeholder="matric no" aria-label="Search">
      <button class="btn btn-outline-success px-5 mx-3" type="submit">Search</button>
    </form>

    <?php if (isset($_SESSION['user_id'])): ?>
        <h2 class="mb-3 mt-4">Hello, <?php echo $_SESSION['user_name']; ?>! 👋</h2>

        <?php else: ?>
      <?php  header("Location: ../index.php"); ?>
    <?php endif; ?>
</div> 
<div class="container py-5">
    <div class="row">
      <!-- Profile Section -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm p-3">
              <img src="../assets/images/profile_picture.jpg" class="card-img-top" alt="Profile Picture">
              <div class="card-body">
                  <h5 class="card-title">View Profile</h5>
                  <p class="card-text">Highlight your latest professional achievements and relevant skills</p>
              </div>
              <div class="d-grid gap-2"> <a role="button" class="btn card-btn btn-success fw-medium py-2" href="profile.php">View Profile</a> </div>
          </div>
        </div>

      <!-- Alumni Friends -->
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm p-3">
              <img src="../assets/images/social_image.jpg" class="card-img-top" alt="Social Image">
              <div class="card-body">
                  <h5 class="card-title">Alumni Friends</h5>
                  <p class="card-text">Expand your network. Reconnect with your alma mater</p>
              </div>
              <div class="d-grid gap-2"> <a role="button" class="btn card-btn btn-success fw-medium py-2" href="view_alumni.php">View Alumni</a> </div>
          </div>
      </div>

      <!-- Events/News -->
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm p-3">
              <img src="../assets/images/social_event.jpg" class="card-img-top" alt="Social Event">
              <div class="card-body">
                  <h5 class="card-title">Events/News</h5>
                  <p class="card-text">Keep an eye out below for our evolving list of events</p>
              </div>
              <div class="d-grid gap-2"> <a role="button" class="btn card-btn btn-success fw-medium py-2" href="events.php">View News/Events</a> </div>
          </div>
      </div>

      <!-- Advertisements -->
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm p-3">
              <img src="../assets/images/advertisement_photo.jpeg" class="card-img-top" alt="Advertisement Photo">
              <div class="card-body">
                  <h5 class="card-title">Advertisements</h5>
                  <p class="card-text">Access exclusive job listings, workshops, seminars to nurture your professional growth</p>
              </div>
              <div class="d-grid gap-2"> <a role="button" class="btn card-btn btn-success fw-medium py-2" href="view_advertisements.php">View Advertisements</a> </div>
          </div>
      </div>

      <!-- Messages -->
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm p-3">
          <h5>Messages</h5>
          <p>Check your inbox and connect with old classmates.</p>
          <a href="inbox.php" class="btn btn-sm btn-outline-dark">Go to Messages</a>
        </div>
      </div>
    </div>

       <!-- Placeholder cards -->
       <hr><br><hr>

    <div class="text-center mt-5">
      <a href="../auth/logout.php" class="btn btn-danger rounded-pill px-4">Logout</a>
    </div>
  </div>



  <?php include '../includes/footer.php' ?>
