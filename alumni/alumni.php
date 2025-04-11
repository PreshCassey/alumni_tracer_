<?php include '../includes/header.php' ?>
<div class="container py-5">

    <div class="row">
      <!-- Profile Section -->
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm p-3">
          <h5>Your Profile</h5>
          <p><strong>Email:</strong> example@example.com</p>
          <p><strong>Graduation Year:</strong> 2022</p>
          <a href="edit_profile.php" class="btn btn-sm btn-outline-primary">Edit Profile</a>
        </div>
      </div>

      <!-- Resume Upload -->
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm p-3">
          <h5>Upload Resume</h5>
          <p>Share your resume to connect with opportunities.</p>
          <a href="../resume/upload.php" class="btn btn-sm btn-outline-success">Upload Resume</a>
        </div>
      </div>

      <!-- Events -->
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm p-3">
          <h5>Upcoming Events</h5>
          <ul>
            <li>Annual Alumni Meetup - May 20</li>
            <li>Career Growth Webinar - June 2</li>
          </ul>
          <a href="../events/list_events.php" class="btn btn-sm btn-outline-info">View All Events</a>
        </div>
      </div>

      <!-- Messages -->
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm p-3">
          <h5>Messages</h5>
          <p>Check your inbox and connect with old classmates.</p>
          <a href="../messages/inbox.php" class="btn btn-sm btn-outline-dark">Go to Messages</a>
        </div>
      </div>
    </div>

    <div class="text-center mt-5">
      <a href="../auth/logout.php" class="btn btn-danger rounded-pill px-4">Logout</a>
    </div>
  </div>