<?php
// Start session
session_start();

// Include functions
require_once 'functions.php';

// Initialize message variable
$message = "";
$posts = null;

// Handle search
if (isset($_POST['search'])) {
    $query = validateInput($_POST['query']);
    
    if (empty($query)) {
        $message = "Search query cannot be empty.";
    } else {
        $posts = searchPosts($query);
        
        if ($posts->num_rows === 0) {
            $message = "No posts found matching your search.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Search Results</h2>
        
        <!-- Display messages -->
        <?php if (isset($message) && !empty($message)): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Search Form -->
        <div class="card mb-3">
            <div class="card-header">
                <h5>Search Again</h5>
            </div>
            <div class="card-body">
                <form action="search.php" method="POST">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchQuery" name="query" placeholder="Search for posts..." value="<?php echo isset($_POST['query']) ? $_POST['query'] : ''; ?>" required>
                        <button type="submit" class="btn btn-info" name="search">Search</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Search Results -->
        <?php if ($posts && $posts->num_rows > 0): ?>
            <div class="card">
                <div class="card-header">
                    <h5>Results</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $posts->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['title']; ?></td>
                                    <td><?php echo strlen($row['description']) > 100 ? substr($row['description'], 0, 100) . '...' : $row['description']; ?></td>
                                    <td>
                                        <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Back to Home -->
        <div class="text-center mt-3">
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>