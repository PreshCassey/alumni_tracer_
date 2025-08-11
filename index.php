<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Tracer</title>
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>  
  <!-- Nav tabs -->
  <header class="Container-fluid mx-5">
      <nav class="navbar px-5 fixed-top bg-light navbar-expand-lg custom_nav-container ">
        <a class="navbar-brand" href="index.php">
          <img src="./assets/images/Logo (2).png" alt="" srcset="" width="50" height="50">
          <span class="text-success font-weight-bold">ALUMNI CONNECT</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <div class="d-flex mx-auto flex-column flex-lg-row align-items-center">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link text-success" href="#about"> About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link text-success" href="#contact">Contact Us</a>
              </li>
            </ul>
          </div>
          <div class="d-flex align-items-center btn-container">
            <a href="auth/login.php"> <span class="mx-3 text-success font-weight-bold">Login</span></a>
            <a href="auth/register.php"><span class="mx-3 text-success font-weight-bold">Register</span></a>
          </div>
        </div>
      </nav>
  </header>

 <!-- Hero Section -->
  <section class="text-white bg-hero img-fluid text-center pt-5 p-5">
    <div class="container p-5">
      <h1 class="display-4">Alumni Connect: Building Lifelong Networks</h1>
      <p class="lead">Reignite connections, explore new opportunities, and contribute to a thriving alumni community.</p>
      <a href="auth/register.php" class="btn btn-success btn-lg m-2"><i class="bi bi-person-plus"></i> Join the Network</a>
      <!-- <a href="#" class="btn btn-outline-success btn-lg m-2"><i class="bi bi-journal-richtext"></i> Explore Alumni Stories</a> -->
    </div>
  </section>

  <!-- About Section -->
  <section class="py-5" id="about">
    <div class="container">
      <h2 class="mb-4 text-success">About Our Community</h2>
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
      <h2 class="mb-4 text-center text-success">Career Resources</h2>
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="card h-100">
            <img src="./assets/images/showcasex.jpg" class="card-img-top" alt="Profile" width="200" height="200">
            <div class="card-body">
              <h5 class="card-title">Showcase Your Expertise</h5>
              <p>Create a digital profile with your career history and skills. Connect, control privacy, and get verified.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card h-100">
            <img src="./assets/images/job.jpg" class="card-img-top" alt="Job Board" width="200" height="200">
            <div class="card-body">
              <h5 class="card-title">Job Board/Advertisment</h5>
              <p>Find exclusive job postings tailored for alumni, covering a range of industries and experience levels.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card h-100">
            <img src="./assets/images/mentorsh.jpg" class="card-img-top" alt="Mentorship">
            <div class="card-body">
              <h5 class="card-title">Events</h5>
              <p>Find Exicting Alumni Events, Find Meetup events, connect with friends and like-minded people. 
                Meet people near you who share your interests.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card h-100">
            <img src="./assets/images/alumnfr.jpg" class="card-img-top" alt="Development">
            <div class="card-body">
              <h5 class="card-title">Alumni</h5>
              <p>Find and connect with Alumni all over the world.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Alumni Spotlight -->
  <section class="py-5">
    <div class="container text-center">
      <h2 class="text-success">Featured Alumnus Spotlight</h2>
      <p class="lead">Inspiring Journey of Innovation</p>
      <img src="./assets/images/matric.jpeg" class="img-fluid rounded my-3" alt="Alumnus Story">
      <p class="text-muted">set ~ 2021-2025</p>
    </div>
  </section>

  <!-- Events Section -->
  <section class="bg-light py-5">
    <div class="container">
      <h2 class="mb-4 text-center text-success">Community Events</h2>
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

  <section>
    <!-- Alumni Stories Section -->
    <div class="container my-5">
      <h2 class="text-center mb-2 text-success fw-bold">Alumni Stories</h2>
      <p class="text-center mb-5 fw-bold">Discover success narratives of our accomplished alumni. Read stories of career growth, cross-industry collaboration, and their lasting impact.</p>


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
                <h4 class="text-success">Praise Peter Ayuba</h4>
                <small class="text-muted">Class of 2025 – Cyber Security</small>
                <p class="mt-3">
                  "Greenfield University gave me more than a degree, it gave me growth, lifelong friendships, and a clear sense of purpose. From unforgettable campus moments to building my final year alumni tracer application, every experience shaped who I am today.
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
                <h4 class="text-success">Philemon Oluwadamilola Agbor</h4>
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
                <h4 class="text-success">Casmir Precious Amarachi</h4>
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
                <img src="./assets/images/me.jpg" class="rounded-circle img-fluid" alt="Alumni 3">
              </div>
              <div class="col-md-6">
                <h4 class="text-success">Casmir Precious Amarachi</h4>
                <small class="text-muted">Class of 2025 – Info Technology</small>
                <p class="mt-3">
                 "University was more than lectures and exams, it was where I discovered who I am. I learned resilience in the face of challenges, found friends who became family, and built dreams that still guide me today."
                </p>
              </div>
            </div>
          </div>

        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#alumniStoriesCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon bg-success rounded-circle p-2"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#alumniStoriesCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon bg-success rounded-circle p-2"></span>
        </button>
      </div>
    </div>
  </section>

  <!-- Give Back Section -->
  <section class="bg-success text-white text-center py-5">
    <div class="container text-center">
      <h2 class="text-white">Give Back Programs</h2>
      <p class="lead">Support the next generation of leaders.</p>
      <p>Contribute to scholarships, mentor students, or lead a professional workshop. Every donation helps strengthen our community.</p>
      <a href="#" class="btn btn-secondary px-5 mt-3">Support Now</a>
    </div>
  </section>


  <?php include 'includes/footer.php' ?>
