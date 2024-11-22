<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <main>
        <div class="row landing m-0 d-flex">
            <div class="col-lg-5 col-md-5 col-12 p-0 text-center">
                <div class="carousel">
                    <img src="./img/logo/logo(1).png" alt="Sched Logo" class="responsive-img">
                </div>
            </div>
            <div class="container col-lg-7 col-md-5 col-12 p-0">
                <div class="login p-4">
                    <div class="text-center m-2">
                        <h4>Western Mindanao State University</h4>
                        <span>Faculty Preference-Based Scheduler</span>
                    </div>
                    <form id="loginForm" action="./processing/facultyprocessing.php" method="POST">
                        <input type="hidden" name="action" value="login">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="btn mt-5 d-flex justify-content-center">
                            <button id="login-btn" type="submit" class="d-flex justify-content-center">Login</button>
                        </div>
                        <?php if (isset($_SESSION['error']) && $_SESSION['error'] === 'wrongpassword'): ?>
                            <p class="text-danger text-center">Wrong Password!</p>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
