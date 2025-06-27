<?php

// Start the session and destroy it

session_start();
session_unset();
session_destroy();
setcookie("user", "", time() - 3600, "/");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Logged Out</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .card {
            max-width: 400px;
        }
    </style>
</head>

<body>

    <div class="card shadow">
        <div class="card-body">
            <i class="fas fa-sign-out-alt fa-3x text-danger mb-3"></i>
            <h4 class="card-title">You have been logged out</h4>
            <p class="card-text text-muted">Thank you for using the dashboard.</p>
            <a href="login.php" class="btn btn-primary mt-2">
                <i class="fas fa-sign-in-alt me-1"></i>Login Again
            </a>
        </div>
    </div>

</body>

</html>