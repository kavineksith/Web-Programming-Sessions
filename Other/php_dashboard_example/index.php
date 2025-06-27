<?php
// Database Configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "student_management";

// Start session for authentication
session_start();

// Connect to database
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Authentication Functions
function authenticateUser($username, $password, $conn) {
    $username = $conn->real_escape_string($username);
    
    $query = "SELECT id, username, password, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
    }
    return false;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function logout() {
    session_unset();
    session_destroy();
}

// Input Validation Functions
function validateName($name) {
    return preg_match("/^[a-zA-Z ]{2,50}$/", $name);
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone) {
    return preg_match("/^[0-9]{10}$/", $phone);
}

function validateStudentID($id) {
    return preg_match("/^S[0-9]{5}$/", $id);
}

function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

// Handle Form Submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Login Form
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if (empty($username) || empty($password)) {
            $error = "Username and password are required";
        } else {
            if (authenticateUser($username, $password, $conn)) {
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid username or password";
            }
        }
    }
    
    // Logout
    if (isset($_POST['logout'])) {
        logout();
        header("Location: index.php");
        exit();
    }
    
    // Add Student
    if (isset($_POST['add_student'])) {
        $student_id = $_POST['student_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $dob = $_POST['dob'];
        $address = $_POST['address'];
        $errors = [];
        
        if (!validateStudentID($student_id)) {
            $errors[] = "Student ID must be in format S00000";
        }
        
        if (!validateName($name)) {
            $errors[] = "Name must contain only letters and spaces (2-50 characters)";
        }
        
        if (!validateEmail($email)) {
            $errors[] = "Please enter a valid email address";
        }
        
        if (!validatePhone($phone)) {
            $errors[] = "Phone number must be 10 digits";
        }
        
        if (!validateDate($dob)) {
            $errors[] = "Please enter a valid date of birth (YYYY-MM-DD)";
        }
        
        if (empty($errors)) {
            $sql = "INSERT INTO students (student_id, name, email, phone, dob, address) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $student_id, $name, $email, $phone, $dob, $address);
            
            if ($stmt->execute()) {
                $success = "Student added successfully";
            } else {
                $errors[] = "Error: " . $stmt->error;
            }
        }
    }
    
    // Update Student
    if (isset($_POST['update_student'])) {
        $id = $_POST['id'];
        $student_id = $_POST['student_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $dob = $_POST['dob'];
        $address = $_POST['address'];
        $errors = [];
        
        if (!validateStudentID($student_id)) {
            $errors[] = "Student ID must be in format S00000";
        }
        
        if (!validateName($name)) {
            $errors[] = "Name must contain only letters and spaces (2-50 characters)";
        }
        
        if (!validateEmail($email)) {
            $errors[] = "Please enter a valid email address";
        }
        
        if (!validatePhone($phone)) {
            $errors[] = "Phone number must be 10 digits";
        }
        
        if (!validateDate($dob)) {
            $errors[] = "Please enter a valid date of birth (YYYY-MM-DD)";
        }
        
        if (empty($errors)) {
            $sql = "UPDATE students SET student_id=?, name=?, email=?, phone=?, dob=?, address=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $student_id, $name, $email, $phone, $dob, $address, $id);
            
            if ($stmt->execute()) {
                $success = "Student updated successfully";
            } else {
                $errors[] = "Error: " . $stmt->error;
            }
        }
    }
    
    // Delete Student
    if (isset($_POST['delete_student'])) {
        $id = $_POST['id'];
        
        $sql = "DELETE FROM students WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $success = "Student deleted successfully";
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            display: flex;
            min-height: 100vh;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 100px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            transition: all 0.3s;
            position: fixed;
            height: 100%;
        }
        
        .content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .header h2 {
            margin: 0;
        }
        
        .menu {
            padding: 20px 0;
        }
        
        .menu-item {
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            display: block;
            transition: all 0.3s;
        }
        
        .menu-item:hover, .menu-item.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-danger {
            background-color: #dc3545;
        }
        
        .btn-success {
            background-color: #28a745;
        }
        
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f8f9fa;
        }
        
        .search-container {
            margin-bottom: 20px;
            display: flex;
        }
        
        .search-container input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
        }
        
        .search-container button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
        
        .pagination {
            display: flex;
            list-style: none;
            justify-content: center;
        }
        
        .pagination a {
            margin: 0 5px;
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #007bff;
        }
        
        .pagination a.active {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <?php if (!isLoggedIn()): ?>
    <!-- Login Form -->
    <div class="login-container">
        <h2 style="margin-bottom: 20px;">Student Management System</h2>
        
        <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn" style="width: 100%;">Login</button>
        </form>
    </div>
    <?php else: ?>
    
    <!-- Dashboard -->
    <div class="sidebar">
        <div class="header">
            <h2>SMS Dashboard</h2>
        </div>
        <div class="menu">
            <a href="#" class="menu-item active" data-page="dashboard">Dashboard</a>
            <a href="#" class="menu-item" data-page="students">Students</a>
            <a href="#" class="menu-item" data-page="add-student">Add Student</a>
            <form method="POST" action="">
                <button type="submit" name="logout" class="menu-item" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;">Logout</button>
            </form>
        </div>
    </div>
    
    <div class="content">
        <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $err): ?>
                <li><?php echo $err; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <!-- Dashboard Page -->
        <div class="page" id="dashboard">
            <div class="card">
                <h2 style="margin-bottom: 20px;">Welcome, <?php echo $_SESSION['username']; ?>!</h2>
                <p>This is the Student Management System Dashboard. Use the sidebar to navigate.</p>
                
                <?php
                // Count total students
                $result = $conn->query("SELECT COUNT(*) as total FROM students");
                $total_students = $result->fetch_assoc()['total'];
                ?>
                
                <div style="display: flex; justify-content: space-between; margin-top: 30px;">
                    <div style="background-color: #007bff; color: white; padding: 20px; border-radius: 8px; flex: 1; margin-right: 15px;">
                        <h3>Total Students</h3>
                        <p style="font-size: 32px; margin-top: 10px;"><?php echo $total_students; ?></p>
                    </div>
                    
                    <?php
                    // Get today's date
                    $today = date('Y-m-d');
                    
                    // Count students added today
                    $stmt = $conn->prepare("SELECT COUNT(*) as today FROM students WHERE DATE(created_at) = ?");
                    $stmt->bind_param("s", $today);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $today_students = $result->fetch_assoc()['today'];
                    ?>
                    
                    <div style="background-color: #28a745; color: white; padding: 20px; border-radius: 8px; flex: 1;">
                        <h3>New Today</h3>
                        <p style="font-size: 32px; margin-top: 10px;"><?php echo $today_students; ?></p>
                    </div>
                </div>
                
                <h3 style="margin-top: 30px;">Recent Students</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Date Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM students ORDER BY created_at DESC LIMIT 5";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['student_id'] . "</td>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . date('Y-m-d', strtotime($row['created_at'])) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No students found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Students Page -->
        <div class="page" id="students" style="display: none;">
            <div class="card">
                <h2 style="margin-bottom: 20px;">Student Management</h2>
                
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Search by name, email, or ID...">
                    <button id="searchBtn">Search</button>
                </div>
                
                <table id="studentsTable">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Date of Birth</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Pagination setup
                        $limit = 10;
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $offset = ($page - 1) * $limit;
                        
                        // Search functionality
                        $search = isset($_GET['search']) ? $_GET['search'] : '';
                        $searchCondition = '';
                        if (!empty($search)) {
                            $search = $conn->real_escape_string($search);
                            $searchCondition = "WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR student_id LIKE '%$search%'";
                        }
                        
                        // Count total results
                        $countSql = "SELECT COUNT(*) as total FROM students $searchCondition";
                        $countResult = $conn->query($countSql);
                        $total = $countResult->fetch_assoc()['total'];
                        $totalPages = ceil($total / $limit);
                        
                        // Fetch students
                        $sql = "SELECT * FROM students $searchCondition ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['student_id'] . "</td>";
                                echo "<td>" . $row['name'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['phone'] . "</td>";
                                echo "<td>" . $row['dob'] . "</td>";
                                echo "<td>";
                                echo "<button class='btn btn-success edit-btn' data-id='" . $row['id'] . "'>Edit</button> ";
                                echo "<form method='POST' action='' style='display:inline;'>";
                                echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                                echo "<button type='submit' name='delete_student' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this student?\")'>Delete</button>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                                
                                // Hidden row for edit form
                                echo "<tr class='edit-form' id='edit-" . $row['id'] . "' style='display:none;background-color:#f9f9f9;'>";
                                echo "<td colspan='6'>";
                                echo "<form method='POST' action=''>";
                                echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                                echo "<div style='display:flex;flex-wrap:wrap;gap:15px;'>";
                                echo "<div style='flex:1;min-width:200px;'>";
                                echo "<label>Student ID</label>";
                                echo "<input type='text' name='student_id' class='form-control' value='" . $row['student_id'] . "' required>";
                                echo "</div>";
                                echo "<div style='flex:1;min-width:200px;'>";
                                echo "<label>Name</label>";
                                echo "<input type='text' name='name' class='form-control' value='" . $row['name'] . "' required>";
                                echo "</div>";
                                echo "<div style='flex:1;min-width:200px;'>";
                                echo "<label>Email</label>";
                                echo "<input type='email' name='email' class='form-control' value='" . $row['email'] . "' required>";
                                echo "</div>";
                                echo "<div style='flex:1;min-width:200px;'>";
                                echo "<label>Phone</label>";
                                echo "<input type='text' name='phone' class='form-control' value='" . $row['phone'] . "' required>";
                                echo "</div>";
                                echo "<div style='flex:1;min-width:200px;'>";
                                echo "<label>Date of Birth</label>";
                                echo "<input type='date' name='dob' class='form-control' value='" . $row['dob'] . "' required>";
                                echo "</div>";
                                echo "<div style='flex:2;min-width:300px;'>";
                                echo "<label>Address</label>";
                                echo "<textarea name='address' class='form-control' rows='3'>" . $row['address'] . "</textarea>";
                                echo "</div>";
                                echo "<div style='flex:2;min-width:100%;display:flex;justify-content:flex-end;align-items:flex-end;'>";
                                echo "<button type='button' class='btn' style='background-color:#6c757d;margin-right:10px;' onclick='document.getElementById(\"edit-" . $row['id'] . "\").style.display=\"none\"'>Cancel</button>";
                                echo "<button type='submit' name='update_student' class='btn btn-success'>Update Student</button>";
                                echo "</div>";
                                echo "</div>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No students found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>" <?php echo ($page == $i) ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Add Student Page -->
        <div class="page" id="add-student" style="display: none;">
            <div class="card">
                <h2 style="margin-bottom: 20px;">Add New Student</h2>
                
                <form method="POST" action="" id="addStudentForm">
                    <div class="form-group">
                        <label for="student_id">Student ID</label>
                        <input type="text" class="form-control" id="student_id" name="student_id" placeholder="Format: S00000" required>
                        <small id="student_id_error" class="error"></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <small id="name_error" class="error"></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <small id="email_error" class="error"></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="10 digits" required>
                        <small id="phone_error" class="error"></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" required>
                        <small id="dob_error" class="error"></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" name="add_student" class="btn btn-success">Add Student</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Navigation
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (this.getAttribute('data-page')) {
                    e.preventDefault();
                    const targetPage = this.getAttribute('data-page');
                    
                    // Hide all pages
                    document.querySelectorAll('.page').forEach(page => {
                        page.style.display = 'none';
                    });
                    
                    // Show target page
                    document.getElementById(targetPage).style.display = 'block';
                    
                    // Update active menu item
                    document.querySelectorAll('.menu-item').forEach(menuItem => {
                        menuItem.classList.remove('active');
                    });
                    this.classList.add('active');
                }
            });
        });
        
        // Edit button functionality
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const editForm = document.getElementById('edit-' + id);
                
                // Hide any open edit forms
                document.querySelectorAll('.edit-form').forEach(form => {
                    form.style.display = 'none';
                });
                
                // Show this edit form
                editForm.style.display = 'table-row';
            });
        });
        
        // Search functionality
        document.getElementById('searchBtn').addEventListener('click', function() {
            const searchValue = document.getElementById('searchInput').value.trim();
            window.location.href = '?search=' + encodeURIComponent(searchValue);
        });
        
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('searchBtn').click();
            }
        });
        
        // Client-side validation for add student form
        document.getElementById('addStudentForm').addEventListener('submit', function(e) {
            let hasErrors = false;
            
            // Reset error messages
            document.querySelectorAll('.error').forEach(error => {
                error.textContent = '';
            });
            
            // Validate Student ID
            const studentId = document.getElementById('student_id').value;
            if (!/^S[0-9]{5}$/.test(studentId)) {
                document.getElementById('student_id_error').textContent = 'Student ID must be in format S00000';
                document.getElementById('student_id_error').style.color = 'red';
                hasErrors = true;
            }
            
            // Validate Name
            const name = document.getElementById('name').value;
            if (!/^[a-zA-Z ]{2,50}$/.test(name)) {
                document.getElementById('name_error').textContent = 'Name must contain only letters and spaces (2-50 characters)';
                document.getElementById('name_error').style.color = 'red';
                hasErrors = true;
            }
            
            // Validate Email
            const email = document.getElementById('email').value;
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                document.getElementById('email_error').textContent = 'Please enter a valid email address';
                document.getElementById('email_error').style.color = 'red';
                hasErrors = true;
            }
            
            // Validate Phone
            const phone = document.getElementById('phone').value;
            if (!/^[0-9]{10}$/.test(phone)) {
                document.getElementById('phone_error').textContent = 'Phone number must be 10 digits';
                document.getElementById('phone_error').style.color = 'red';
                hasErrors = true;
            }
            
            // Validate Date of Birth
            const dob = document.getElementById('dob').value;
            if (!dob) {
                document.getElementById('dob_error').textContent = 'Please enter a valid date of birth';
                document.getElementById('dob_error').style.color = 'red';
                hasErrors = true;
            }
            
            if (hasErrors) {
                e.preventDefault();
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>