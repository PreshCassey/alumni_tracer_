<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Tracer</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>  
  <!-- Nav tabs -->
  <header class="Container-fluid mx-5">
      <nav class="navbar px-5 fixed-top bg-light navbar-expand-lg custom_nav-container ">
        <a class="navbar-brand" href="index.php"><span>ALUMNI CONNECT</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <div class="d-flex mx-auto flex-column flex-lg-row align-items-center">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="#about-us"> About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#contactus">Contact Us</a>
              </li>
            </ul>
          </div>
          <div class="d-flex align-items-center btn-container">
            <a href="auth/login.php"> <span class="mx-3">Login</span></a>
            <a href="auth/register.php"><span class="mx-3">Register</span></a>
          </div>
        </div>
      </nav>
  </header>

 <!-- Hero Section -->
  <section class="bg-primary text-white text-center pt-5 p-5">
    <div class="container p-5">
      <h1 class="display-4">Alumni Connect: Building Lifelong Networks</h1>
      <p class="lead">Reignite connections, explore new opportunities, and contribute to a thriving alumni community.</p>
      <a href="#" class="btn btn-light btn-lg m-2"><i class="bi bi-person-plus"></i> Join the Network</a>
      <a href="#" class="btn btn-outline-light btn-lg m-2"><i class="bi bi-journal-richtext"></i> Explore Alumni Stories</a>
    </div>
  </section>

  <!-- About Section -->
  <section class="py-5">
    <div class="container">
      <h2 class="mb-4">About Our Community</h2>
      <ul class="list-group list-group-flush">
        <li class="list-group-item"><strong>Purpose:</strong> To foster lasting connections among graduates and provide a platform for ongoing engagement.</li>
        <li class="list-group-item"><strong>Benefits:</strong> Unlock career opportunities, mentorship, and a supportive community for professional and personal growth.</li>
        <li class="list-group-item"><strong>Mission:</strong> We empower alumni to achieve their full potential through collaboration and mutual support.</li>
        <li class="list-group-item"><strong>Impact:</strong> Strong alumni relationships build a valuable legacy and contribute to the institution's success.</li>
      </ul>
    </div>
  </section>

  <!-- Career Resources -->
  <section class="bg-light py-5">
    <div class="container">
      <h2 class="mb-4 text-center">Career Resources</h2>
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="card h-100">
            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Profile">
            <div class="card-body">
              <h5 class="card-title">Showcase Your Expertise</h5>
              <p>Create a digital profile with your career history and skills. Connect, control privacy, and get verified.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card h-100">
            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Job Board">
            <div class="card-body">
              <h5 class="card-title">Job Board</h5>
              <p>Find exclusive job postings tailored for alumni, covering a range of industries and experience levels.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card h-100">
            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Mentorship">
            <div class="card-body">
              <h5 class="card-title">Mentorship Program</h5>
              <p>Get matched with experienced alumni for career guidance and personal development support.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card h-100">
            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Development">
            <div class="card-body">
              <h5 class="card-title">Professional Development</h5>
              <p>Attend workshops and seminars to enhance your skills and stay ahead in your field.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Alumni Spotlight -->
  <section class="py-5">
    <div class="container text-center">
      <h2>Featured Alumnus Spotlight</h2>
      <p class="lead">Inspiring Journey of Innovation</p>
      <img src="https://via.placeholder.com/600x300" class="img-fluid rounded my-3" alt="Alumnus Story">
      <p>Discover success narratives of our accomplished alumni. Read stories of career growth, cross-industry collaboration, and their lasting impact.</p>
    </div>
  </section>

  <!-- Events Section -->
  <section class="bg-light py-5">
    <div class="container">
      <h2 class="mb-4 text-center">Community Events</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 text-center">
            <div class="card-body">
              <i class="bi bi-camera-video-fill display-4 text-primary"></i>
              <h5 class="card-title mt-3">Virtual Networking</h5>
              <p>Engage in online events to connect with alumni around the world.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 text-center">
            <div class="card-body">
              <i class="bi bi-people-fill display-4 text-primary"></i>
              <h5 class="card-title mt-3">Annual Conference</h5>
              <p>Attend our flagship event packed with professional development and reunions.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100 text-center">
            <div class="card-body">
              <i class="bi bi-geo-alt-fill display-4 text-primary"></i>
              <h5 class="card-title mt-3">Regional Meetups</h5>
              <p>Join local gatherings for in-person networking and meaningful conversations.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Give Back Section -->
  <section class="py-5">
    <div class="container text-center">
      <h2>Give Back Programs</h2>
      <p class="lead">Support the next generation of leaders.</p>
      <p>Contribute to scholarships, mentor students, or lead a professional workshop. Every donation helps strengthen our community.</p>
    </div>
  </section>

  <!-- Connect Section -->
  <section class="bg-primary text-white text-center py-5">
    <div class="container">
      <h2>Connect and Grow</h2>
      <ul class="list-unstyled lead">
        <li><i class="bi bi-instagram me-2"></i>Social Media Integration</li>
        <li><i class="bi bi-phone me-2"></i>Mobile App Features</li>
      </ul>
      <a href="#" class="btn btn-light mt-3">Download the App</a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3">
    <div class="container">
      <p>&copy; 2025 Alumni Connect. All rights reserved. <a href="">| Precious Amarachi Casmir</a></p>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

