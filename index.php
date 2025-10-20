<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ALUMEX — Connecting Graduates. Tracing Impact.</title>
  <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
  <link rel="shortcut icon" href="assets/images/nobglogo.png" type="image/x-icon">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>

  <!-- ===== NAVBAR ===== -->
  <header>
    <nav class="navbar navbar-expand-lg fixed-top shadow-sm">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
          <img src="assets/images/nobglogo.png" alt="ALUMEX Logo" width="100" height="100" class="me-2">
          <span class="text-gold fw-bold fs-4">ALUMEX</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-center" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
            <li class="nav-item"><a class="nav-link" href="#careers">Resources</a></li>
            <li class="nav-item"><a class="nav-link" href="#stories">Stories</a></li>
            <li class="nav-item"><a class="nav-link" href="#events">Events</a></li>
            <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
          </ul>
          <div class="ms-lg-4">
            <a href="auth/login.php" class="btn btn-outline-light me-2">Login</a>
            <a href="auth/register.php" class="btn btn-gold">Join Now</a>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <!-- ===== HERO ===== -->
   
  <section class="text-white bg-hero img-fluid text-white text-center d-flex align-items-center" style="min-height:100vh;">
    <div class="container mt-5 pt-5">
      <h1 class="display-4 fw-bold text-uppercase mb-3">Connecting Graduates. Tracing Impact.</h1>
      <p class="lead mb-4">Reignite your network, explore opportunities, and celebrate alumni success stories.</p>
      <a href="auth/register.php" class="btn btn-gold btn-lg px-4"><i class="bi bi-person-plus"></i> Join ALUMEX</a>
    </div>
  </section>

  <!-- ===== ABOUT ===== -->
  <section id="about" class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold text-blue">About ALUMEX</h2>
        <p class="text-muted">The Alumni Experience & Tracer System</p>
      </div>
      <div class="row g-4">
        <div class="col-md-6">
          <p><strong>Purpose:</strong> ALUMEX fosters lifelong connections among graduates and serves as a digital bridge between alumni and their alma mater.</p>
          <p><strong>Mission:</strong> Empowering alumni to grow, give back, and stay connected to a thriving community of change-makers.</p>
        </div>
        <div class="col-md-6">
          <p><strong>Benefits:</strong> Unlock mentorship opportunities, career resources, and a global network for professional collaboration.</p>
          <p><strong>Impact:</strong> Every alumnus leaves a legacy — ALUMEX ensures it’s celebrated and remembered.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== CAREER RESOURCES ===== -->
  <section id="careers" class="py-5">
    <div class="container text-center">
      <h2 class="fw-bold text-blue mb-5">Career & Alumni Resources</h2>
      <div class="row g-4">
        <div class="col-md-3">
          <div class="card h-100">
            <img src="assets/images/showcasex.jpg" class="card-img-top" alt="Showcase">
            <div class="card-body">
              <h5 class="card-title text-blue">Showcase Your Expertise</h5>
              <p>Create a digital alumni profile to highlight your journey, skills, and achievements.</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card h-100">
            <img src="assets/images/job.jpg" class="card-img-top" alt="Jobs">
            <div class="card-body">
              <h5 class="card-title text-blue">Job Board</h5>
              <p>Access alumni-tailored job opportunities and professional openings worldwide.</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card h-100">
            <img src="assets/images/mentorsh.jpg" class="card-img-top" alt="Events">
            <div class="card-body">
              <h5 class="card-title text-blue">Events</h5>
              <p>Attend alumni gatherings, conferences, and mentorship meetups near you.</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card h-100">
            <img src="assets/images/alumnfr.jpg" class="card-img-top" alt="Network">
            <div class="card-body">
              <h5 class="card-title text-blue">Global Network</h5>
              <p>Find and connect with alumni across the globe through shared stories and experiences.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== STORIES ===== -->
  <section id="stories" class="py-5 bg-light">
    <div class="container text-center">
      <h2 class="fw-bold text-blue mb-4">Alumni Stories</h2>
      <p class="fw-bold text-muted mb-5">Discover the journeys, growth, and inspiration of our graduates.</p>
  
        <div id="alumniStoriesCarousel" class="carousel slide" data-bs-ride="carousel">
          <!-- Indicators -->
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#alumniStoriesCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#alumniStoriesCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#alumniStoriesCarousel" data-bs-slide-to="2"></button>
            <button type="button" data-bs-target="#alumniStoriesCarousel" data-bs-slide-to="3"></button>
          </div>

          <!-- Carousel Items -->
          <div class="carousel-inner">
            <!-- Story 1 -->
            <div class="carousel-item active">
              <div class="row justify-content-center align-items-center text-center">
                <div class="col-md-4">
                  <img src="./assets/images/pip.jpg" class="rounded-circle img-fluid" alt="Alumni 1">
                </div>
                <div class="col-md-6">
                  <h4 class="text-blue">Praise Peter Ayuba</h4>
                  <small class="text-muted">Class of 2025 – Cyber Security</small>
                  <p class="mt-3">
                    "Greenfield University gave me more than a degree, it gave me growth, lifelong friendships, and a clear sense of purpose.
                  <br>
                  As an alumna, I believe our journey doesn’t end at graduation. It’s the start of giving back, staying connected, and creating opportunities for one another. This is my story, and it will always be part of the Greenfield story."
                  </p>
                </div>
              </div>
            </div>

            <!-- Story 2 -->
            <div class="carousel-item">
              <div class="row justify-content-center align-items-center text-center">
                <div class="col-md-4">
                  <img src="./assets/images/Phil.jpg" class="rounded-circle img-fluid" alt="Alumni 2">
                </div>
                <div class="col-md-6">
                  <h4 class="text-blue">Philemon Oluwadamilola Agbor</h4>
                  <small class="text-muted">Class of 2025 – Infomation Technology</small>
                  <p class="mt-3">
                    "I’m Philemon OluwaDamilola Agbor, a proud graduate of Greenfield University and a member of GFU 21 Class of 2025, with a degree in Information Technology.From my humble beginnings in 100lvl as a Fresher to my 400lvl Final year,my journey at Greenfield University was one of growth, resilience, and purpose. Every challenge became a lesson, every success a reminder that dreams are worth chasing. As an Alumni, I believe our journey doesn’t end at graduation, I have cemented my name as one of the best Graduating students in my set and as one of the best students that has passed through Greenfield University.
              This is my story and I will always be part of the growth of this University!"
                  </p>
                </div>
              </div>
            </div>

            <!-- Story 3 -->
            <div class="carousel-item">
              <div class="row justify-content-center align-items-center text-center">
                <div class="col-md-4">
                  <img src="./assets/images/me.jpg" class="rounded-circle img-fluid" alt="Alumni 3">
                </div>
                <div class="col-md-6">
                  <h4 class="text-blue">Casmir Precious Amarachi</h4>
                  <small class="text-muted">Class of 2025 – Info Technology</small>
                  <p class="mt-3">
                  "University was more than lectures and exams, it was where I discovered who I am. I learned resilience in the face of challenges, found friends who became family, and built dreams that still guide me today."
                  </p>
                </div>
              </div>
            </div>

            
            <!-- Story 4 -->
            <div class="carousel-item">
              <div class="row justify-content-center align-items-center text-center">
                <div class="col-md-4">
                  <img src="./assets/images/chi.jpg" class="rounded-circle img-fluid" alt="Alumni 3">
                </div>
                <div class="col-md-6">
                  <h4 class="text-blue">Ruth Chidogo Udah</h4>
                  <small class="text-muted">Class of 2025 – Bio Technology</small>
                  <p class="mt-3">
                  "Greenfield was full of experiences for me the good, the bad, and all the in-betweens. But through it all, I learned, I grew, and I made friends I’ll always cherish. Greenfield will always be a place where my dreams found their wings. 
                  <br>
                  Every moment shaped me, and I’m glad to call myself a proud Greenfield alumna, and I look forward to watching Greenfield grow even more beautifully in the years ahead."            
                  </p>
                </div>
              </div>
            </div>

          </div>

          <!-- Controls -->
          <button class="carousel-control-prev" type="button" data-bs-target="#alumniStoriesCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon btn-gold rounded-circle p-2"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#alumniStoriesCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon btn-gold rounded-circle p-2"></span>
          </button>
        </div>
    </div>
  </section>


  <!-- ===== EVENTS ===== -->
  <section id="events" class="py-5">
    <div class="container text-center">
      <h2 class="fw-bold text-blue mb-5">Community Events</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100">
            <div class="card-body">
              <i class="bi bi-camera-video-fill display-4 text-blue"></i>
              <h5 class="mt-3">Virtual Networking</h5>
              <p>Connect globally in live interactive alumni sessions and meetups.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100">
            <div class="card-body">
              <i class="bi bi-people-fill display-4 text-blue"></i>
              <h5 class="mt-3">Annual Conference</h5>
              <p>Engage in workshops, panels, and collaborative learning experiences.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100">
            <div class="card-body">
              <i class="bi bi-geo-alt-fill display-4 text-blue"></i>
              <h5 class="mt-3">Regional Meetups</h5>
              <p>Reunite with alumni in your city — build memories and partnerships that last.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== GIVE BACK ===== -->
  <section class="text-white text-center py-5 mb-2" style="background: var(--alumex-gold);">
    <div class="container">
      <h2 class="fw-bold text-blue">Give Back Programs</h2>
      <p class="lead">Support the next generation of leaders.</p>
      <p>Contribute to scholarships, mentorship programs, or community initiatives. Every contribution matters.</p>
      <a href="#" class="btn btn-alumex mt-3 px-5">Support Now</a>
    </div>
  </section>

  <!-- ===== FOOTER ===== -->
  <?php include 'includes/footer.php'; ?>

  <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

