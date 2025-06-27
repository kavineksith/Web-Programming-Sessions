<?php
// Start session
session_start();

// Include functions
require_once 'functions.php';

// Initialize message variable
$message = "";

// Handle user signup
if (isset($_POST['signup'])) {
    $username = validateInput($_POST['username']);
    $email = validateInput($_POST['email']);
    $password = validateInput($_POST['password']);
    $profile_image = isset($_FILES['profile_image']) ? $_FILES['profile_image'] : null;
    
    $message = registerUser($username, $email, $password, $profile_image);
    
    // Redirect after successful signup
    if ($message == "Signup successful.") {
        header("Location: index.php?message=" . urlencode($message));
        exit();
    }
}

// Handle user login
if (isset($_POST['login'])) {
    $email = validateInput($_POST['email']);
    $password = validateInput($_POST['password']);
    
    $message = loginUser($email, $password);
    
    // Redirect after successful login
    if ($message == "Login successful.") {
        header("Location: index.php?message=" . urlencode($message));
        exit();
    }
}

// Handle logout
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    setcookie("user", "", time() - 3600, "/");
    
    header("Location: index.php?message=" . urlencode("Logout successful."));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Authentication</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">User Authentication</h2>
        
        <!-- Display messages -->
        <?php if (isset($message) && !empty($message)): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <!-- User Signup Form -->
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Signup</h5>
                    </div>
                    <div class="card-body">
                        <form action="auth.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="profile_image" class="form-label">Profile Image (optional)</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image">
                                <div class="form-text">Upload JPG, JPEG, PNG or GIF (max 5MB)</div>
                            </div>
                            <button type="submit" class="btn btn-primary" name="signup">Sign Up</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- User Login Form -->
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>Login</h5>
                    </div>
                    <div class="card-body">
                        <form action="auth.php" method="POST">
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="loginEmail" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="loginPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="loginPassword" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="login">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Back to Home -->
        <div class="text-center mt-3">
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>