<?php 
include '../includes/header.php';
require '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger text-center mt-4'>
            <a href='../auth/login.php'>Please log in to access your profile.</a>
          </div>";
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

    $profile_image = null;
    $resume = null;

    // Validate upload helper
    function validate_upload($file, $allowed_types, $max_kb) {
        if (isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
            $file_size_kb = round($file['size'] / 1024, 2);
            if ($file_size_kb > $max_kb) {
                throw new Exception("File too large! Max allowed is {$max_kb}KB.");
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($file_type, $allowed_types)) {
                throw new Exception("Invalid file type: {$file_type}");
            }

            $safe_name = preg_replace("/[^a-zA-Z0-9_\.-]/", "_", basename($file['name']));
            return time() . "_" . $safe_name;
        }
        return null;
    }

    $upload_dir = '../uploads';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    try {
        if (!empty($_FILES['profile_image']['name'])) {
            $profile_image = validate_upload($_FILES['profile_image'], ['image/jpeg', 'image/png'], 300);
            move_uploaded_file($_FILES['profile_image']['tmp_name'], "$upload_dir/$profile_image");
        }

        if (!empty($_FILES['resume']['name'])) {
            $resume = validate_upload($_FILES['resume'], ['application/pdf'], 500);
            move_uploaded_file($_FILES['resume']['tmp_name'], "$upload_dir/$resume");
        }

        // Check if user already has details
        $check = $conn->prepare("SELECT id FROM user_details WHERE user_id = ?");
        $check->execute([$user_id]);
        $exists = $check->fetch();

        if ($exists) {
            $sql = "UPDATE user_details SET 
                dob = ?, gender = ?, contact_number = ?, hometown = ?, 
                current_location = ?, job_position = ?, qualification = ?, company = ?";

            if ($profile_image) $sql .= ", profile_image = '$profile_image'";
            if ($resume) $sql .= ", resume = '$resume'";

            $sql .= " WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$dob, $gender, $contact_number, $hometown, $current_location, $job_position, $qualification, $company, $user_id]);
        } else {
            $stmt = $conn->prepare("INSERT INTO user_details 
                (user_id, dob, gender, contact_number, hometown, current_location, job_position, qualification, company, profile_image, resume)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $dob, $gender, $contact_number, $hometown, $current_location, $job_position, $qualification, $company, $profile_image, $resume]);
        }

        echo "<div class='alert alert-success alert-dismissible fade show mt-4 text-center'>
                Profile updated successfully.
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";

        logAction($conn, $_SESSION['user_id'], 'Profile Update', 'Updated profile fields');

    } catch (Exception $e) {
        echo "<div class='alert alert-danger alert-dismissible fade show mt-4 text-center'>
                " . htmlspecialchars($e->getMessage()) . "
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    }
}

// ===== FETCH USER INFO =====
$stmt = $conn->prepare("
    SELECT u.id, u.first_name, u.last_name, u.email, u.graduation_year, u.matric_no, u.course, 
           d.dob, d.gender, d.contact_number, d.hometown, d.current_location, d.profile_image, 
           d.job_position, d.qualification, d.company, d.resume
    FROM users u
    LEFT JOIN user_details d ON u.id = d.user_id
    WHERE u.id = ?
");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h3 class="mb-4 text-blue fw-bold">My Profile</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <!-- Basic Info -->
                    <div class="col-md-6">
                        <label>First Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label>Last Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label>Graduation Year</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['graduation_year']) ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label>Matric No</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['matric_no']) ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label>Course</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['course']) ?>" readonly>
                    </div>

                    <!-- Editable Info -->
                    <div class="col-md-6">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" class="form-control" value="<?= htmlspecialchars($user['dob']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select</option>
                            <option value="Male" <?= ($user['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= ($user['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" value="<?= htmlspecialchars($user['contact_number']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Hometown</label>
                        <input type="text" name="hometown" class="form-control" value="<?= htmlspecialchars($user['hometown']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Current Location</label>
                        <input type="text" name="current_location" class="form-control" value="<?= htmlspecialchars($user['current_location']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Job Position</label>
                        <input type="text" name="job_position" class="form-control" value="<?= htmlspecialchars($user['job_position']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Qualification</label>
                        <input type="text" name="qualification" class="form-control" value="<?= htmlspecialchars($user['qualification']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Company</label>
                        <input type="text" name="company" class="form-control" value="<?= htmlspecialchars($user['company']) ?>">
                    </div>

                    <!-- Uploads -->
                    <div class="col-md-6">
                        <label>Profile Image</label><br>
                        <?php if ($user['profile_image']): ?>
                            <img src="../uploads/<?= htmlspecialchars($user['profile_image']) ?>" class="rounded-circle mb-2" width="90">
                        <?php endif; ?>
                        <input type="file" name="profile_image" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Resume (PDF)</label><br>
                        <?php if ($user['resume']): ?>
                            <a href="../uploads/<?= htmlspecialchars($user['resume']) ?>" target="_blank" class="btn btn-outline-silver btn-sm mb-2">View Resume</a><br>
                        <?php endif; ?>
                        <input type="file" name="resume" class="form-control">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-alumex px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Privacy Section -->
    <div class="card mt-4 shadow-sm border-0">
        <div class="card-body">
            <h4 class="mb-3 text-blue">🔐 Privacy & Security</h4>
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
                <button type="button" class="btn btn-outline-silver">Save Settings</button>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
