<?php 
include '../includes/header.php';
require '../config/database.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    $file = $_FILES['file'];

    // Define the recipient email
    $to = "preciouscasmir04@gmail.com";
    $subject = "Votcas support contact form";
    
    // Create the email headers
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Create the email body
    $body = "<h2>Contact Form Submission</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Message:</strong> {$message}</p>";

    // Check if a file is uploaded
    if ($file['error'] == 0) {
        $file_content = chunk_split(base64_encode(file_get_contents($file['tmp_name'])));
        $file_name = $file['name'];

        $boundary = md5(time());

        // Update headers for attachment
        $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

        // Update body for attachment
        $body = "--{$boundary}\r\n" .
                "Content-Type: text/html; charset=UTF-8\r\n" .
                "Content-Transfer-Encoding: 7bit\r\n\r\n" .
                $body . "\r\n\r\n" .
                "--{$boundary}\r\n" .
                "Content-Type: application/octet-stream; name=\"{$file_name}\"\r\n" .
                "Content-Transfer-Encoding: base64\r\n" .
                "Content-Disposition: attachment; filename=\"{$file_name}\"\r\n\r\n" .
                $file_content . "\r\n\r\n" .
                "--{$boundary}--";
    }

    // Send the email
    if (mail($to, $subject, $body, $headers)) {
        echo '<div class="alert alert-success" role="alert">Message sent successfully!</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Failed to send message. Please try again.</div>';
    }
}
?>


  <div class="container mt-4 mb-5">
      <div class="row">
        <div class="col-md-6">
          <div class="card bg-light shadow">
            <div class="card-body">
              <h2 class="card-title fw-bold fs-2">Contact or Support</h2>
              <p class="card-text">For any inquiries or assistance, please feel free to contact our support team.</p>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <i class="fa fa-envelope me-2"></i>Email: preciouscasmir04@gmail.com
                </li>
                <li class="list-group-item">
                  <i class="fa fa-phone me-2"></i>Phone: +2348173406858
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card bg-light shadow">
            <div class="card-body">
              <h5 class="card-title">Send us a message</h5>
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                  <label for="name" class="form-label">Your Name</label>
                  <input type="text" class="form-control" name="name" required id="name">
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Your Email</label>
                  <input type="email" class="form-control" name="email" required id="email">
                </div>
                <div class="mb-3">
                  <label for="message" class="form-label">Message</label>
                  <textarea class="form-control" required name="message" id="message" rows="5"></textarea>
                </div>
                <div class="mb-3">
                  <label for="file" class="form-label">Upload File (Optional):</label>
                  <input type="file" class="form-control" id="file" name="file">
                </div>
                <button type="submit" class="btn btn-success">Send Message</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
     <div class="container py-5">
    <h3 class="mb-4">📋 Alumni Feedback Survey</h3>
    <form>
      <div class="mb-3">
        <label for="q1" class="form-label">How satisfied are you with the platform?</label>
        <select class="form-select" id="q1">
          <option>Very Satisfied</option>
          <option>Satisfied</option>
          <option>Neutral</option>
          <option>Dissatisfied</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="q2" class="form-label">Suggestions for improvement:</label>
        <textarea class="form-control" rows="3"></textarea>
      </div>
      <button type="submit" class="btn btn-success">Submit Feedback</button>
    </form>
  </div>


    </div>

  </div>

<?php include('../includes/footer.php');?>