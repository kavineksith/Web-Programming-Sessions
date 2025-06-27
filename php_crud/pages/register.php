

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css" />
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
            max-width: 900px;
            width: 100%;
        }

        .left-column {
            background-color: #007bff;
            color: white;
            padding: 30px;
            border-radius: 5px 0 0 5px;
        }

        .right-column {
            padding: 30px;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .form-container input {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="card shadow">
        <div class="row g-0">
            <div class="col-md-6 left-column">
                <h3 class="mb-4">Welcome to Our Dashboard</h3>
                <p>Sign up to access your personal dashboard and features.</p>
                <img src="../assets/img/undraw_welcome_nk8k.svg" alt="Welcome Image" class="img-fluid mb-3 mt-4" style="max-width: 80%;">
            </div>
            <div class="col-md-6 right-column">
                <div class="form-container">
                    <h4 class="card-title mb-4">Sign Up</h4>
                    <form action="register.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" placeholder="Enter your username"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Enter your password"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-user-plus me-2"></i>Sign
                            Up</button>
                    </form>
                    <hr>
                    <p class="text-muted">Already have an account? <a href="login.php">Sign In</a></p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>