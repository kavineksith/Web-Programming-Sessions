<?php
// Start session
session_start();

// Include functions
require_once 'functions.php';

// Initialize message variable
$message = "";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php?message=" . urlencode("Please login to update posts."));
    exit();
}

// Get post ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?message=" . urlencode("Post ID is required."));
    exit();
}

$id = validateInput($_GET['id']);
$post = getPostById($id);

// Check if post exists
if (!$post || $post->num_rows === 0) {
    header("Location: index.php?message=" . urlencode("Post not found."));
    exit();
}

$postData = $post->fetch_assoc();

// Handle post update
if (isset($_POST['update'])) {
    $title = validateInput($_POST['title']);
    $description = validateInput($_POST['description']);
    $post_image = isset($_FILES['post_image']) ? $_FILES['post_image'] : null;
    
    $message = updatePost($id, $title, $description, $post_image);
    
    // Redirect after successful update
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
    <title>Update Post</title>
    <!-- Bootstrap CSS -->
    <link href="./bootstrap.min.css />
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Update Post</h2>
        
        <!-- Display messages -->
        <?php if (isset($message) && !empty($message)): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Update Post Form -->
        <div class="card mb-3">
            <div class="card-header">
                <h5>Edit Post #<?php echo $id; ?></h5>
            </div>
            <div class="card-body">
                <form action="update.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo $postData['title']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required><?php echo $postData['description']; ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="post_image" class="form-label">Post Image</label>
                        <?php if (!empty($postData['image_path'])): ?>
                            <div class="mb-2">
                                <img src="<?php echo htmlspecialchars($postData['image_path']); ?>" alt="Current Post Image" style="max-width: a200px; max-height: 200px;" class="img-thumbnail">
                                <p class="form-text">Current image</p>
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="post_image" name="post_image">
                        <div class="form-text">Upload a new image (JPG, JPEG, PNG or GIF, max 5MB) or leave empty to keep current image</div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-warning" name="update">Update Post</button>
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