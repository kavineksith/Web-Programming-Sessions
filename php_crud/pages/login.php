<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign In</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css" />
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
            background-color: #28a745;
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
                <h3 class="mb-4">Welcome Back!</h3>
                <p>Sign in to access your dashboard and continue where you left off.</p>
                <img src="../assets/img/undraw_welcome_nk8k (2).svg" alt="Welcome Image" class="img-fluid mb-3" style="max-width: 80%;">
            </div>
            <div class="col-md-6 right-column">
                <div class="form-container">
                    <h4 class="card-title mb-4">Sign In</h4>
                    <form>
                        <div class="mb-3">
                            <label for="signinEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="signinEmail" placeholder="Enter your email"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="signinPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="signinPassword"
                                placeholder="Enter your password" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100"><i class="fas fa-sign-in-alt me-2"></i>Sign
                            In</button>
                    </form>
                    <hr>
                    <p class="text-muted">Don't have an account? <a href="register.php">Sign Up</a></p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>