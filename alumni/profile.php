<?php
session_start();
include('../config/database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$alumni = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<h2>Welcome, <?php echo $alumni['first_name']; ?>!</h2>
<p>Email: <?php echo $alumni['email']; ?></p>
<p>Graduation Year: <?php echo $alumni['graduation_year']; ?></p>
