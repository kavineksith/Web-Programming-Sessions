<?php
// Start session
session_start();

// Include functions
require_once 'functions.php';

// Initialize message variable
$message = "";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php?message=" . urlencode("Please login to create posts."));
    exit();
}

// Handle post creation
if (isset($_POST['create'])) {
    $title = validateInput($_POST['title']);
    $description = validateInput($_POST['description']);
    $post_image = isset($_FILES['post_image']) ? $_FILES['post_image'] : null;
    
    $message = createPost($title, $description, $post_image);
    
    // Redirect after successful creation
    if (strpos($message, "successful") !== false) {
        header("Location: index.php?message=" . urlencode($message));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <!-- Bootstrap CSS -->
    <link href="./bootstrap.min.css />
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Create New Post</h2>
        
        <!-- Display messages -->
        <?php if (isset($message) && !empty($message)): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Create Post Form -->
        <div class="card mb-3">
            <div class="card-header">
                <h5>Post Details</h5>
            </div>
            <div class="card-body">
                <form action="create.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="post_image" class="form-label">Post Image (optional)</label>
                        <input type="file" class="form-control" id="post_image" name="post_image">
                        <div class="form-text">Upload JPG, JPEG, PNG or GIF (max 5MB)</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success" name="create">Create Post</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>