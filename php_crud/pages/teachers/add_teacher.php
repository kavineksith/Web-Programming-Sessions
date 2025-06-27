<?php

session_start(); // Start the session to access session variables

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION["user"]) || $_SESSION["user"] !== "loggedIn") {
    header('Location: ../login.php'); // Redirect to login page if not logged in
    exit();
}

require_once '../../config/config.php'; // Include the database connection file

if (isset($_POST['addTeacher'])) {
    // Retrieve form data
    $name = $_POST['teacherName'];
    $email = $_POST['teacherEmail'];
    $phone = $_POST['teacherPhone'];
    $address = $_POST['teacherAddress'];
    $department = $_POST['teacherDepartment'];
    $position = $_POST['teacherPosition'];
    $salary = $_POST['teacherSalary'];
    $joinDate = $_POST['teacherJoinDate'];
    $experience = $_POST['teacherExperience'];
    $status = $_POST['teacherStatus'];
    $birthday = $_POST['teacherBirthday'];
    $gender = $_POST['gender'];

    // Insert data into the database
    $sql = "INSERT INTO teachers (name, email, phone, address, department, position, salary, join_date, experience, status, birthday, gender) 
            VALUES ('$name', '$email', '$phone', '$address', '$department', '$position', '$salary', '$joinDate', '$experience', '$status', '$birthday', '$gender')";

    if (mysqli_query($conn, $sql)) {
        echo "<div class='alert alert-success text-center'>Teacher added successfully.</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error: " . mysqli_error($conn) . "</div>";
    }
    
    mysqli_close($conn); // Close the database connection

};

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Teacher</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Insert Teacher</h5>
            </div>
            <div class="card-body">
                <form action="add_teacher.php" method="POST" onsubmit="return validateForm()">
                    <div class="mb-3">
                        <label for="teacherName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="teacherName" placeholder="Enter full name" name="teacherName" autocomplete="off" autofocus="on" required>
                    </div>
                    <div class="mb-3">
                        <label for="teacherEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="teacherEmail" placeholder="Enter email" name="teacherEmail" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label for="teacherPhone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="teacherPhone" placeholder="Enter phone number" name="teacherPhone" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label for="teacherAddress" class="form-label">Address</label>
                        <input type="text" class="form-control" id="teacherAddress" placeholder="Enter address" name="teacherAddress" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label for="teacherDepartment" class="form-label">Department</label>
                        <select class="form-select" id="teacherDepartment" name="teacherDepartment" required>
                            <option value="" disabled selected>Select department</option>
                            <option value="Mathematics">Mathematics</option>
                            <option value="Science">Science</option>
                            <option value="History">History</option>
                            <option value="Literature">Literature</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="teacherPosition" class="form-label">Position</label>
                        <select class="form-select" id="teacherPosition" name="teacherPosition" required>
                            <option value="" disabled selected>Select position</option>
                            <option value="Teacher">Teacher</option>
                            <option value="Assistant">Assistant</option>
                            <option value="Head of Department">Head of Department</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="teacherSalary" class="form-label">Salary</label>
                        <input type="number" class="form-control" id="teacherSalary" placeholder="Enter salary" name="teacherSalary" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label for="teacherJoinDate" class="form-label">Joining Date</label>
                        <input type="date" class="form-control" id="teacherJoinDate" name="teacherJoinDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="teacherExperience" class="form-label">Experience (in years)</label>
                        <input type="number" class="form-control" id="teacherExperience" placeholder="Enter experience" name="teacherExperience" autocomplete="off" required>
                    </div>
                    <div class="mb-3">
                        <label for="teacherStatus" class="form-label">Status</label>
                        <select class="form-select" id="teacherStatus" name="teacherStatus" required>
                            <option value="" disabled selected>Select status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="teacherBirthday" class="form-label">Birthday</label>
                        <input type="date" class="form-control" id="teacherBirthday" name="teacherBirthday" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Gender</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Male" required>
                            <label class="form-check-label" for="genderMale">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Female">
                            <label class="form-check-label" for="genderFemale">Female</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="genderOther" value="Other">
                            <label class="form-check-label" for="genderOther">Other</label>
                        </div>
                    </div>

                    <!-- Confirmation Checkbox -->
                    <div class="mb-3 form-check">
                        <!-- No usage with database, just for user focus only -->
                        <input type="checkbox" class="form-check-input" id="infoCorrect">
                        <label class="form-check-label" for="infoCorrect">All of the information provided is correct</label>
                    </div>

                    <button type="submit" name="addTeacher" class="btn btn-success"><i class="fas fa-save me-1"></i>Submit</button>
                    <a href="teachers-view.html" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
<script>
    function validateForm() {
        const checkbox = document.getElementById('infoCorrect');
        if (!checkbox.checked) {
            alert('Please confirm that all information provided is correct.');
            return false;
        }
        return true;
    }
</script>
</body>

</html>
