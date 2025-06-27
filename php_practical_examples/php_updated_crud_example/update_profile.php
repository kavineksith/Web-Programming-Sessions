<?php
// Start session
session_start();

// Include functions
require_once 'functions.php';

// Initialize message variable
$message = "";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php?message=" . urlencode("Please login to update your profile."));
    exit();
}

// Get user data
$user = getUserById($_SESSION['user_id']);
$userData = $user->fetch_assoc();

// Handle profile update
if (isset($_POST['update_profile'])) {
    $username = validateInput($_POST['username']);
    $email = validateInput($_POST['email']);
    $current_password = validateInput($_POST['current_password']);
    $new_password = isset($_POST['new_password']) ? validateInput($_POST['new_password']) : '';
    $profile_image = isset($_FILES['profile_image']) ? $_FILES['profile_image'] : null;
    
    // Update profile
    $message = updateUserProfile($_SESSION['user_id'], $username, $email, $current_password, $new_password, $profile_image);
    
    if (strpos($message, "successful") !== false) {
        // Refresh user data after update
        $user = getUserById($_SESSION['user_id']);
        $userData = $user->fetch_assoc();
        
        // Update session variables
        $_SESSION['username'] = $userData['username'];
        if (isset($userData['profile_image'])) {
            $_SESSION['profile_image'] = $userData['profile_image'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Update Profile</h2>
        
        <!-- Display messages -->
        <?php if (isset($message) && !empty($message)): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Update Profile Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Edit Profile Information</h5>
            </div>
            <div class="card-body">
                <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-4 text-center">
                            <?php if (isset($userData['profile_image']) && !empty($userData['profile_image'])): ?>
                                <img src="<?php echo htmlspecialchars($userData['profile_image']); ?>" alt="Current Profile" class="img-fluid rounded-circle mb-3" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 200px; height: 200px; margin: 0 auto;">
                                    <h1><?php echo strtoupper(substr($userData['username'], 0, 1)); ?></h1>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label for="profile_image" class="form-label">Profile Image</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image">
                                <div class="form-text">Upload JPG, JPEG, PNG or GIF (max 5MB)</div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                            </div>
                            
                            <hr>
                            
                            <h5 class="mb-3">Change Password (Optional)</h5>
                            
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <div class="form-text">Required to confirm changes</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                                <div class="form-text">Leave blank to keep current password</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary" name="update_profile">Save Changes</button>
                        <a href="profile.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>