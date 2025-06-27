<?php
// Start session
session_start();

// Include functions
require_once 'functions.php';

// Initialize message variable
$message = "";

// Check for message in URL (after redirect)
if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

// Fetch all posts for display
$posts = getAllPosts();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Post Management System</h2>
        
        <!-- Display messages -->
        <?php if (isset($message) && !empty($message)): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Navigation -->
        <div class="mb-3">
            <?php if ($isLoggedIn): ?>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
                        <?php if (isset($_SESSION['profile_image']) && !empty($_SESSION['profile_image'])): ?>
                            <img src="<?php echo htmlspecialchars($_SESSION['profile_image']); ?>" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                        <?php endif; ?>
                        <a href="profile.php" class="btn btn-info btn-sm">View Profile</a>
                    </div>
                    <div>
                        <a href="create.php" class="btn btn-success me-2">Create New Post</a>
                        <form action="auth.php" method="POST" class="d-inline">
                            <button type="submit" class="btn btn-secondary" name="logout">Logout</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-end">
                    <a href="auth.php" class="btn btn-primary">Login/Register</a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Search Form -->
        <div class="card mb-3">
            <div class="card-header">
                <h5>Search Posts</h5>
            </div>
            <div class="card-body">
                <form action="search.php" method="POST">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchQuery" name="query" placeholder="Search for posts..." required>
                        <button type="submit" class="btn btn-info" name="search">Search</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Posts Table -->
        <div class="card">
            <div class="card-header">
                <h5>All Posts</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($posts && $posts->num_rows > 0): ?>
                            <?php while ($row = $posts->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['title']; ?></td>
                                    <td><?php echo strlen($row['description']) > 100 ? substr($row['description'], 0, 100) . '...' : $row['description']; ?></td>
                                    <td>
                                        <?php if (!empty($row['image_path'])): ?>
                                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Post Image" style="max-width: 100px; max-height: 100px;">
                                        <?php else: ?>
                                            No Image
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No posts found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>