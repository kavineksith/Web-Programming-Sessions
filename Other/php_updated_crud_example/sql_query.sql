-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS test_db;

-- Use the database
USE test_db;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create posts table
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create indexes for better performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_posts_title ON posts(title);
CREATE INDEX idx_posts_user ON posts(user_id);

-- Optional: Insert some sample data for testing
INSERT INTO users (username, email, password) VALUES
('admin', 'admin@example.com', '$2y$10$PL7Jgc3FjyUtOtfJxRZfO.MtqMX9Vo9nDdny.YOUvVk85Yb.CFJTS'), -- Password: admin123
('user1', 'user1@example.com', '$2y$10$9D1OZzOZTUfAl0T/qKB6ceatxTdXs1ywkH/17jUmmHOYbKCVFLHRe'); -- Password: user123

-- Optional: Insert sample posts
INSERT INTO posts (title, description, user_id) VALUES
('First Post', 'This is the description of the first post. It contains some sample text for testing purposes.', 1),
('Second Post', 'This is another post with some sample content. This post is created to demonstrate the functionality of the post management system.', 1),
('Introduction', 'Welcome to our post management system. This system allows you to create, read, update, and delete posts.', 2),
('Lorem Ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisi. Sed euismod, nisl eget ultricies aliquam, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl.', 2);

-- Query to get all posts with user information
SELECT p.id, p.title, p.description, p.created_at, u.username 
FROM posts p
LEFT JOIN users u ON p.user_id = u.id
ORDER BY p.created_at DESC;

-- Query to search posts
SELECT * FROM posts 
WHERE title LIKE '%search_term%' OR description LIKE '%search_term%';

-- Query to get posts by user
SELECT * FROM posts WHERE user_id = ?;

-- Query to get post by ID with user info
SELECT p.*, u.username 
FROM posts p
LEFT JOIN users u ON p.user_id = u.id
WHERE p.id = ?;

-- Add profile_image column to users table
ALTER TABLE users ADD COLUMN profile_image VARCHAR(255) DEFAULT NULL;

-- Add image_path column to posts table
ALTER TABLE posts ADD COLUMN image_path VARCHAR(255) DEFAULT NULL;