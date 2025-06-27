<?php
// Add these functions to the existing functions.php file

// Get posts by user ID
function getUserPosts($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    return $stmt->get_result();
}

// Update user profile
function updateUserProfile($user_id, $username, $email, $current_password, $new_password = '', $profile_image = null) {
    global $conn;
    
    if (empty($user_id) || empty($username) || empty($email) || empty($current_password)) {
        return "All required fields must be filled.";
    }
    
    // Validate username format
    if (!preg_match("/^[a-zA-Z0-9]{5,}$/", $username)) {
        return "Invalid username. Must be at least 5 characters long and contain only letters and numbers.";
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }
    
    // Get current user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return "User not found.";
    }
    
    $user = $result->fetch_assoc();
    
    // Verify current password
    if (!password_verify($current_password, $user['password'])) {
        return "Current password is incorrect.";
    }
    
    // Check if username is already taken by another user
    if ($username !== $user['username']) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->bind_param("si", $username, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return "Username is already taken.";
        }
    }
    
    // Check if email is already taken by another user
    if ($email !== $user['email']) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return "Email is already registered.";
        }
    }
    
    // Handle new password if provided
    $password = $user['password'];
    if (!empty($new_password)) {
        // Validate new password
        if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/", $new_password)) {
            return "New password must be at least 6 characters long and contain letters and numbers.";
        }
        $password = password_hash($new_password, PASSWORD_DEFAULT);
    }
    
    // Handle profile image upload if provided
    $image_path = $user['profile_image'];
    if ($profile_image && $profile_image['error'] == 0 && $profile_image['size'] > 0) {
        $result = updateProfileImage($user_id, $profile_image);
        if (strpos($result, "successfully") !== false) {
            // Get the updated image path
            $stmt = $conn->prepare("SELECT profile_image FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $updated_user = $result->fetch_assoc();
            $image_path = $updated_user['profile_image'];
        } else {
            return $result; // Return error message from updateProfileImage
        }
    }
    
    // Update user record
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ?, profile_image = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $username, $email, $password, $image_path, $user_id);
    
    if ($stmt->execute()) {
        return "Profile updated successfully.";
    } else {
        return "Error updating profile: " . $stmt->error;
    }
}
?>