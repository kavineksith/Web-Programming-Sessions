<?php
    session_start();

    if (!isset($_SESSION["user"]) || $_SESSION["user"] !== "loggedIn") {
        header('Location: ../login.php');
        exit();
    }

    require_once '../../config/config.php';

    $teacher = [];

    if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
        $id = intval($_GET["id"]);
        $result = mysqli_query($conn, "SELECT * FROM teachers WHERE id = $id");
        if ($result && mysqli_num_rows($result) > 0) {
            $teacher = mysqli_fetch_assoc($result);
        } else {
            echo "Teacher not found.";
            exit();
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["updateTeacher"])) {
        $id = intval($_POST["teacherId"]);
        $name = $_POST["teacherName"];
        $email = $_POST["teacherEmail"];
        $phone = $_POST["teacherPhone"];
        $address = $_POST["teacherAddress"];
        $department = $_POST["teacherDepartment"];
        $position = $_POST["teacherPosition"];
        $salary = $_POST["teacherSalary"];
        $joinDate = $_POST["teacherJoinDate"];
        $experience = $_POST["teacherExperience"];
        $status = $_POST["teacherStatus"];
        $birthday = $_POST["teacherBirthday"];
        $gender = $_POST["gender"];

        $sql = "UPDATE teachers SET
                    name = '$name',
                    email = '$email',
                    phone = '$phone',
                    address = '$address',
                    department = '$department',
                    position = '$position',
                    salary = '$salary',
                    join_date = '$joinDate',
                    experience = '$experience',
                    status = '$status',
                    birthday = '$birthday',
                    gender = '$gender'
                WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            echo "<div class='alert alert-success text-center'>Teacher updated successfully.</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Error: " . mysqli_error($conn) . "</div>";
        }

        mysqli_close($conn); // Close the database connection
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Teacher</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Edit Teacher</h5>
            </div>
            <div class="card-body">
                <form action="edit_teacher.php?id=<?= $teacher['id'] ?>" method="POST" onsubmit="return validateForm()">
                    <input type="hidden" name="teacherId" value="<?= $teacher['id'] ?>">

                    <div class="mb-3">
                        <label for="teacherName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="teacherName" name="teacherName" value="<?= $teacher['name'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="teacherEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="teacherEmail" name="teacherEmail" value="<?= $teacher['email'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="teacherPhone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="teacherPhone" name="teacherPhone" value="<?= $teacher['phone'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="teacherAddress" class="form-label">Address</label>
                        <input type="text" class="form-control" id="teacherAddress" name="teacherAddress" value="<?= $teacher['address'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="teacherDepartment" class="form-label">Department</label>
                        <select class="form-select" id="teacherDepartment" name="teacherDepartment" required>
                            <option value="Mathematics" <?= $teacher['department'] == 'Mathematics' ? 'selected' : '' ?>>Mathematics</option>
                            <option value="Science" <?= $teacher['department'] == 'Science' ? 'selected' : '' ?>>Science</option>
                            <option value="History" <?= $teacher['department'] == 'History' ? 'selected' : '' ?>>History</option>
                            <option value="Literature" <?= $teacher['department'] == 'Literature' ? 'selected' : '' ?>>Literature</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="teacherPosition" class="form-label">Position</label>
                        <select class="form-select" id="teacherPosition" name="teacherPosition" required>
                            <option value="Teacher" <?= $teacher['position'] == 'Teacher' ? 'selected' : '' ?>>Teacher</option>
                            <option value="Assistant" <?= $teacher['position'] == 'Assistant' ? 'selected' : '' ?>>Assistant</option>
                            <option value="Head of Department" <?= $teacher['position'] == 'Head of Department' ? 'selected' : '' ?>>Head of Department</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="teacherSalary" class="form-label">Salary</label>
                        <input type="number" class="form-control" id="teacherSalary" name="teacherSalary" value="<?= $teacher['salary'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="teacherJoinDate" class="form-label">Joining Date</label>
                        <input type="date" class="form-control" id="teacherJoinDate" name="teacherJoinDate" value="<?= $teacher['join_date'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="teacherExperience" class="form-label">Experience (in years)</label>
                        <input type="number" class="form-control" id="teacherExperience" name="teacherExperience" value="<?= $teacher['experience'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="teacherStatus" class="form-label">Status</label>
                        <select class="form-select" id="teacherStatus" name="teacherStatus" required>
                            <option value="Active" <?= $teacher['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                            <option value="Inactive" <?= $teacher['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="teacherBirthday" class="form-label">Birthday</label>
                        <input type="date" class="form-control" id="teacherBirthday" name="teacherBirthday" value="<?= $teacher['birthday'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Gender</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="Male" <?= $teacher['gender'] == 'Male' ? 'checked' : '' ?> required>
                            <label class="form-check-label">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="Female" <?= $teacher['gender'] == 'Female' ? 'checked' : '' ?>>
                            <label class="form-check-label">Female</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="Other" <?= $teacher['gender'] == 'Other' ? 'checked' : '' ?>>
                            <label class="form-check-label">Other</label>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="infoCorrect">
                        <label class="form-check-label" for="infoCorrect">All of the information provided is correct</label>
                    </div>

                    <button type="submit" name="updateTeacher" class="btn btn-warning text-dark"><i class="fas fa-save me-1"></i>Update</button>
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