<?php
// Start session
session_start();

// Include functions
require_once 'functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php?message=" . urlencode("Please login to delete posts."));
    exit();
}

// Get post ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?message=" . urlencode("Post ID is required."));
    exit();
}

$id = validateInput($_GET['id']);

// Check if post exists
$post = getPostById($id);
if (!$post || $post->num_rows === 0) {
    header("Location: index.php?message=" . urlencode("Post not found."));
    exit();
}

// Delete the post
$message = deletePost($id);

// Redirect after deletion
header("Location: index.php?message=" . urlencode($message));
exit();
?>