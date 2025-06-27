<?php

// php code goes here
// This file is for viewing a student from the database

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Students</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Student List</h5>
                <a href="students-insert.html" class="btn btn-sm btn-light"><i class="fas fa-plus"></i> Add Student</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
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
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>john@example.com</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <a href="students-view.html" class="btn btn-sm btn-primary"><i
                                        class="fas fa-eye"></i></a>
                                <a href="students-update.html" class="btn btn-sm btn-warning"><i
                                        class="fas fa-edit"></i></a>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <!-- Add more students here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>