<?php
session_start();

if (!isset($_SESSION["user"]) || $_SESSION["user"] !== "loggedIn") {
    header('Location: ../login.php');
    exit();
}

require_once '../../config/config.php';

$sql = "SELECT * FROM teachers ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Teachers</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-light">
    <div class="container mt-5">
        <!-- Alert message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info alert-dismissible fade show text-center" role="alert">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Teacher List</h5>
                <a href="add_teacher.php" class="btn btn-sm btn-light"><i class="fas fa-plus"></i> Add Teacher</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Salary</th>
                            <th>Join Date</th>
                            <th>Experience</th>
                            <th>Status</th>
                            <th>Birthday</th>
                            <th>Gender</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && mysqli_num_rows($result) > 0): ?>
                            <?php $count = 1; ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?= $count++ ?></td>
                                    <td><?= $row['name'] ?></td>
                                    <td><?= $row['email'] ?></td>
                                    <td><?= $row['phone'] ?></td>
                                    <td><?= $row['department'] ?></td>
                                    <td><?= $row['position'] ?></td>
                                    <td><?= $row['salary'] ?></td>
                                    <td><?= $row['join_date'] ?></td>
                                    <td><?= $row['experience'] ?> yrs</td>
                                    <td>
                                        <span class="badge <?= $row['status'] === 'Active' ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>
                                    <td><?= $row['birthday'] ?></td>
                                    <td><?= $row['gender'] ?></td>
                                    <td><?= $row['address'] ?></td>
                                    <td>
                                        <a href="edit_teacher.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                        <a href="delete_teacher.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this teacher?');" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="14" class="text-center">No teachers found.</td>
                            </tr>
                        <?php endif; ?>
                        <?php mysqli_close($conn); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (for alert dismissal) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>