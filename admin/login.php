<?php
session_start();
require '../config/database.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === "" || $password === "") {
        $error = "Please enter both username and password.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT admin_id, username, password FROM admin_users WHERE username = ?");
            $stmt->execute([$username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin) {
                // Plain text password check (since not hashed yet)
                if ($password === $admin['password']) {
                    $_SESSION['admin_id'] = $admin['admin_id'];
                    $_SESSION['admin_username'] = $admin['username'];

                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "Incorrect password.";
                }
            } else {
                $error = "No admin found with that username.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Login - Greenfield Alumni</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f4f8f5; display: flex; justify-content: center; align-items: center; height: 100vh;">

<div class="login-card p-4 bg-white rounded shadow" style="max-width: 400px; width: 100%;">
    <div class="text-center mb-4">
        <h3 class="text-success">Admin Login</h3>
        <p class="text-muted small">Sign in to manage the alumni system</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="login.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" required class="form-control">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" required class="form-control">
        </div>
        <button type="submit" class="btn btn-success w-100">Login</button>
    </form>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>