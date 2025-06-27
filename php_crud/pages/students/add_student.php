<?php

// php code goes here
// This file is for inserting a new student into the database

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Insert Student</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Insert Student</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="studentName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="studentName" placeholder="Enter full name">
                    </div>
                    <div class="mb-3">
                        <label for="studentEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="studentEmail" placeholder="Enter email">
                    </div>
                    <div class="mb-3">
                        <label for="studentStatus" class="form-label">Status</label>
                        <select class="form-select" id="studentStatus">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Submit</button>
                    <a href="students-view.html" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>