-- Create database
CREATE DATABASE IF NOT EXISTS student_management;
USE student_management;

-- Create users table for authentication
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'teacher', 'staff') NOT NULL DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(10) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    dob DATE NOT NULL,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create courses table
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(10) NOT NULL UNIQUE,
    course_name VARCHAR(100) NOT NULL,
    credit_hours INT NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create enrollment table
CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrollment_date DATE NOT NULL,
    grade VARCHAR(2),
    status ENUM('active', 'completed', 'dropped') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- Create attendance table
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('present', 'absent', 'late') NOT NULL,
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Insert default admin user
-- Default password is 'admin123' (hashed)
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$8xaUZ8wIH0Z5OQQiLBBrs.NfB3.wfshOLTwtvJa2J4FIz9hV5YYBi', 'admin');

-- Insert some sample data for testing
INSERT INTO students (student_id, name, email, phone, dob, address) VALUES
('S00001', 'John Smith', 'john@example.com', '1234567890', '2000-01-15', '123 Main St, Anytown'),
('S00002', 'Sarah Johnson', 'sarah@example.com', '2345678901', '2001-03-22', '456 Oak Ave, Somewhere'),
('S00003', 'Michael Brown', 'michael@example.com', '3456789012', '2000-07-10', '789 Pine Rd, Nowhere');

INSERT INTO courses (course_code, course_name, credit_hours, description) VALUES
('CS101', 'Introduction to Computer Science', 3, 'Fundamental concepts of computer science'),
('MATH201', 'Calculus I', 4, 'Introduction to differential and integral calculus'),
('ENG105', 'English Composition', 3, 'Principles of effective writing');

-- Create indexes for better performance
CREATE INDEX idx_student_name ON students(name);
CREATE INDEX idx_student_email ON students(email);
CREATE INDEX idx_student_id ON students(student_id);
CREATE INDEX idx_course_code ON courses(course_code);
CREATE INDEX idx_enrollment_student ON enrollments(student_id);
CREATE INDEX idx_enrollment_course ON enrollments(course_id);
CREATE INDEX idx_attendance_student ON attendance(student_id);
CREATE INDEX idx_attendance_date ON attendance(date);