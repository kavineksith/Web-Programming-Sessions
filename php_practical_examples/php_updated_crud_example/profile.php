<?php
// Start session
session_start();

// Include functions
require_once 'functions.php';

// Initialize message variable
$message = "";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php?message=" . urlencode("Please login to view your profile."));
    exit();
}

// Check for message in URL (after redirect)
if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

// Get user data
$user = getUserById($_SESSION['user_id']);
$userData = $user->fetch_assoc();

// Get user's posts
$userPosts = getUserPosts($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">User Profile</h2>
        
        <!-- Display messages -->
        <?php if (isset($message) && !empty($message)): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- User Profile Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Profile Information</h5>
                <a href="update_profile.php" class="btn btn-primary btn-sm">Edit Profile</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <?php if (isset($userData['profile_image']) && !empty($userData['profile_image'])): ?>
                            <img src="<?php echo htmlspecialchars($userData['profile_image']); ?>" alt="Profile" class="img-fluid rounded-circle" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 200px; height: 200px; margin: 0 auto;">
                                <h1><?php echo strtoupper(substr($userData['username'], 0, 1)); ?></h1>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <h4 class="mb-3"><?php echo htmlspecialchars($userData['username']); ?></h4>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
                        <p><strong>Member Since:</strong> <?php echo date('F j, Y', strtotime($userData['created_at'])); ?></p>
                        <p><strong>Total Posts:</strong> <?php echo $userPosts ? $userPosts->num_rows : 0; ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- User's Posts -->
        <div class="card">
            <div class="card-header">
                <h5>My Posts</h5>
            </div>
            <div class="card-body">
                <?php if ($userPosts && $userPosts->num_rows > 0): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $userPosts->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo strlen($row['description']) > 100 ? htmlspecialchars(substr($row['description'], 0, 100)) . '...' : htmlspecialchars($row['description']); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">You haven't created any posts yet.</div>
                    <div class="text-center">
                        <a href="create.php" class="btn btn-success">Create Your First Post</a>
                    </div>
                <?php endif; ?>
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