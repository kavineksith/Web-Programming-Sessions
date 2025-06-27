<?php
// Include database configuration
require_once 'config.php';

// User authentication functions
function registerUser($username, $email, $password) {
    global $conn;
    
    if (empty($username) || empty($email) || empty($password)) {
        return "All fields are required.";
    } elseif (!preg_match("/^[a-zA-Z0-9]{5,}$/", $username)) {
        return "Invalid username. Must be at least 5 characters long.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    } elseif (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/", $password)) {
        return "Password must be at least 6 characters long and contain letters and numbers.";
    }
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    
    if ($stmt->execute()) {
        return "Signup successful.";
    } else {
        return "Error: " . $stmt->error;
    }
}

function loginUser($email, $password) {
    global $conn;
    
    if (empty($email) || empty($password)) {
        return "Both email and password are required.";
    }
    
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            setcookie("user", $row['username'], time() + 3600, "/");
            return "Login successful.";
        } else {
            return "Invalid credentials.";
        }
    } else {
        return "No user found.";
    }
}

// Post management functions
function createPost($title, $description) {
    global $conn;
    
    if (empty($title) || empty($description)) {
        return "Both title and description are required.";
    }
    
    $stmt = $conn->prepare("INSERT INTO posts (title, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $description);
    
    if ($stmt->execute()) {
        return "Record created successfully.";
    } else {
        return "Error: " . $stmt->error;
    }
}

function updatePost($id, $title, $description) {
    global $conn;
    
    if (empty($id) || empty($title) || empty($description)) {
        return "ID, title, and description are required.";
    }
    
    $stmt = $conn->prepare("UPDATE posts SET title = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $description, $id);
    
    if ($stmt->execute()) {
        return "Record updated successfully.";
    } else {
        return "Error updating record.";
    }
}

function deletePost($id) {
    global $conn;
    
    if (empty($id)) {
        return "ID is required to delete the record.";
    }
    
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        return "Record deleted successfully.";
    } else {
        return "Error deleting record.";
    }
}

function searchPosts($query) {
    global $conn;
    
    if (empty($query)) {
        return "Search query cannot be empty.";
    }
    
    $stmt = $conn->prepare("SELECT * FROM posts WHERE title LIKE ? OR description LIKE ?");
    $searchParam = "%$query%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $stmt->execute();
    
    return $stmt->get_result();
}

function getAllPosts() {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM posts ORDER BY id DESC");
    $stmt->execute();
    
    return $stmt->get_result();
}

function getPostById($id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    return $stmt->get_result();
}
?>