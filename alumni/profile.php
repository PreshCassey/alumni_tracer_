<?php 
include '../includes/header.php';
require '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'><a href='../auth/login.php'>Please log in to access your profile.</a></div>";
    include '../includes/footer.php';
    exit();
}

$user_id = $_SESSION['user_id'];

// ===== UPDATE LOGIC =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dob = $_POST['dob'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $contact_number = $_POST['contact_number'] ?? null;
    $hometown = $_POST['hometown'] ?? null;
    $current_location = $_POST['current_location'] ?? null;
    $job_position = $_POST['job_position'] ?? null;
    $qualification = $_POST['qualification'] ?? null;
    $company = $_POST['company'] ?? null;

// File uploads
$profile_image = null;
$resume = null;

// Function to validate uploads
function validate_upload($file, $allowed_types, $max_kb) {
    if (isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
        // Check size
        $file_size_kb = round($file['size'] / 1024, 2);
        if ($file_size_kb > $max_kb) {
            throw new Exception("File too large! Max allowed size is {$max_kb}KB.");
        }

        // Secure MIME type check
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($file_type, $allowed_types)) {
            throw new Exception("Invalid file type: {$file_type}");
        }

        // Sanitize file name
        $safe_name = preg_replace("/[^a-zA-Z0-9_\.-]/", "_", basename($file['name']));
        $new_filename = time() . "_" . $safe_name;

        return $new_filename;
   }
         return null;
        }

        // Ensure uploads directory exists
        $upload_dir = '../uploads';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        try {
            // Profile Image Validation (300KB max, JPG/PNG only)
            if (!empty($_FILES['profile_image']['name'])) {
                $profile_image = validate_upload($_FILES['profile_image'], ['image/jpeg', 'image/jpg', 'image/png'], 300);
                move_uploaded_file($_FILES['profile_image']['tmp_name'], "$upload_dir/$profile_image");
            }

            // Resume Validation (500KB max, PDF only)
            if (!empty($_FILES['resume']['name'])) {
                $resume = validate_upload($_FILES['resume'], ['application/pdf'], 500);
                move_uploaded_file($_FILES['resume']['tmp_name'], "$upload_dir/$resume");
            }

        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
        }


    // Check if record exists
    $check = $conn->prepare("SELECT id FROM user_details WHERE user_id = ?");
    $check->execute([$user_id]);
    $exists = $check->fetch();

    if ($exists) {
        // Update
        $sql = "UPDATE user_details SET dob = ?, gender = ?, contact_number = ?, hometown = ?, current_location = ?, job_position = ?, qualification = ?, company = ?";

        if ($profile_image) $sql .= ", profile_image = '$profile_image'";
        if ($resume) $sql .= ", resume = '$resume'";
        
        $sql .= " WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$dob, $gender, $contact_number, $hometown, $current_location, $job_position, $qualification, $company, $user_id]);
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO user_details (user_id, dob, gender, contact_number, hometown, current_location, job_position, qualification, company, profile_image, resume)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $dob, $gender, $contact_number, $hometown, $current_location, $job_position, $qualification, $company, $profile_image, $resume]);
    }

    echo "<div class='alert alert-success'>Profile updated successfully.</div>";
}

// ===== FETCH USER INFO =====
$stmt = $conn->prepare("
    SELECT u.id, u.first_name, u.last_name, u.email, u.graduation_year, u.matric_no, u.course, u.created_at,
           d.dob, d.gender, d.contact_number, d.hometown, d.current_location, d.profile_image, 
           d.job_position, d.qualification, d.company, d.resume
    FROM users u
    LEFT JOIN user_details d ON u.id = d.user_id
    WHERE u.id = ?
");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2 class="mb-4">My Profile</h2>

    <form method="POST" enctype="multipart/form-data">
        <!-- Display basic info -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>First Name:</label>
                <input type="text" class="form-control" value="<?= $user['first_name'] ?>" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label>Last Name:</label>
                <input type="text" class="form-control" value="<?= $user['last_name'] ?>" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label>Email:</label>
                <input type="email" class="form-control" value="<?= $user['email'] ?>" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label>Graduation Year:</label>
                <input type="text" class="form-control" value="<?= $user['graduation_year'] ?>" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label>Matric No:</label>
                <input type="text" class="form-control" value="<?= $user['matric_no'] ?>" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label>Course:</label>
                <input type="text" class="form-control" value="<?= $user['course'] ?>" readonly>
            </div>

            <!-- Editable fields -->
            <div class="col-md-6 mb-3">
                <label>Date of Birth:</label>
                <input type="date" name="dob" class="form-control" value="<?= $user['dob'] ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label>Gender:</label>
                <select name="gender" class="form-control">
                    <option value="">Select</option>
                    <option value="Male" <?= ($user['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= ($user['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Contact Number:</label>
                <input type="text" name="contact_number" class="form-control" value="<?= $user['contact_number'] ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label>Hometown:</label>
                <input type="text" name="hometown" class="form-control" value="<?= $user['hometown'] ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Current Location:</label>
                <input type="text" name="current_location" class="form-control" value="<?= $user['current_location'] ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label>Job Position:</label>
                <input type="text" name="job_position" class="form-control" value="<?= $user['job_position'] ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Qualification:</label>
                <input type="text" name="qualification" class="form-control" value="<?= $user['qualification'] ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Company:</label>
                <input type="text" name="company" class="form-control" value="<?= $user['company'] ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label>Profile Image:</label><br>
                <?php if ($user['profile_image']) { ?>
                    <img src="../uploads/<?= $user['profile_image'] ?>" alt="Profile Image" width="100"><br>
                <?php } ?>
                <input type="file" name="profile_image" class="form-control">
            </div>

            <div class="col-md-12 mb-3">
                <label>Upload Resume (PDF):</label><br>
                <?php if ($user['resume']) { ?>
                    <a class="nav-link" href="../uploads/<?= $user['resume'] ?>" target="_blank">View Resume</a><br>
                <?php } ?>
                <input type="file" name="resume" class="form-control">
            </div>
        </div>

        <button type="submit" class="btn btn-success">Update Profile</button>
    </form>
</div>

  <div class="container py-5">
    <h3 class="mb-4">🔐 Privacy & Security</h3>
    <form>
      <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" id="twoFA" checked>
        <label class="form-check-label" for="twoFA">Enable Two-Factor Authentication</label>
      </div>
      <div class="mb-3">
        <label for="privacy" class="form-label">Who can view your profile?</label>
        <select class="form-select" id="privacy">
          <option>Only Alumni</option>
          <option>Private</option>
        </select>
      </div>
      <button class="btn btn-success">Save Settings</button>
    </form>
  </div>

<?php include '../includes/footer.php'; ?>
