<?php
session_start(); // Start the session to access session variables
// Check if the user is logged in, if not redirect to login page

if (!isset($_SESSION["user"]) || $_SESSION["user"] !== "loggedIn") {
    header('Location: ../login.php');
    exit();
}

require_once '../config/config.php';

// Fetch students

// get the total count of students

// Fetch teachers
$teachers = mysqli_query($conn, "SELECT * FROM teachers ORDER BY id DESC");
$totalTeachers = mysqli_num_rows($teachers);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bootstrap 5 Dashboard</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <style>
        /* Move these internal CSS styles to an external CSS file located in the assets/css folder for better maintainability and cleaner code structure. */
        body {
            min-height: 100vh;
            display: flex;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
        }

        .sidebar .nav-link {
            color: #ffffff;
        }

        .sidebar .nav-link:hover {
            background-color: #495057;
            padding-left: 20px;
        }

        .content {
            flex: 1;
            padding: 20px;
            animation: fadeIn 0.6s ease-in-out;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 0.8s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column p-3 text-white">
        <h4 class="text-white"><i class="fas fa-chart-line me-2"></i>Dashboard</h4>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="#" class="nav-link text-white"><i class="fas fa-home me-2"></i>Home</a></li>
            <li><a href="#" class="nav-link text-white"><i class="fas fa-user-graduate me-2"></i>Students</a></li>
            <li><a href="#" class="nav-link text-white"><i class="fas fa-chalkboard-teacher me-2"></i>Teachers</a></li>
            <li><a href="logout.php" class="nav-link text-white"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="content">
        <!-- Cards Row -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-user-graduate me-2"></i>Total Students</h5>
                        <p class="card-text"> <!-- total count of the students --> </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-chalkboard-teacher me-2"></i>Total Teachers</h5>
                        <p class="card-text"><?= $totalTeachers ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-warning mb-3">
                    <div class="card-body">
                        <!-- Add the current status have active (students or teachers) -->
                        <h5 class="card-title"><i class="fas fa-clock me-2"></i>Placeholder</h5>
                        <p class="card-text">Coming Soon</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-danger mb-3">
                    <div class="card-body">
                        <!-- show the total sum of the students and teachers -->
                        <h5 class="card-title"><i class="fas fa-database me-2"></i>Placeholder</h5>
                        <p class="card-text">Coming Soon</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-user-graduate me-2"></i>Students
                </div>
                <div>
                    <input type="text" class="form-control form-control-sm d-inline-block w-auto"
                        placeholder="Search..." onkeyup="searchTable(this, 'studentsTable')">
                    <a href="add_student.php" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Insert</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="studentsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- complete table body and show the list of students -->

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Teachers Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-chalkboard-teacher me-2"></i>Teachers
                </div>
                <div>
                    <input type="text" class="form-control form-control-sm d-inline-block w-auto"
                        placeholder="Search..." onkeyup="searchTable(this, 'teachersTable')">
                    <a href="add_teacher.php" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Insert</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="teachersTable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($teacher = mysqli_fetch_assoc($teachers)) {
                            echo "<tr>
                                <td>{$i}</td>
                                <td>{$teacher['name']}</td>
                                <td>{$teacher['email']}</td>
                                <td>{$teacher['department']}</td>
                                <td>
                                    <a href='view_teacher.php?id={$teacher['id']}' class='btn btn-sm btn-primary'><i class='fas fa-eye'></i></a>
                                    <a href='edit_teacher.php?id={$teacher['id']}' class='btn btn-sm btn-warning'><i class='fas fa-edit'></i></a>
                                    <a href='delete_teacher.php?id={$teacher['id']}' onclick='return confirm(\"Delete this teacher?\")' class='btn btn-sm btn-danger'><i class='fas fa-trash'></i></a>
                                </td>
                            </tr>";
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        function searchTable(input, tableId) {
            const filter = input.value.toLowerCase();
            const rows = document.querySelectorAll(`#${tableId} tbody tr`);
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        }
    </script>
</body>

</html>