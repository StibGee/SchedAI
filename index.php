<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="./css/login.css">
    <?php session_start();?>

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
                <form id="loginForm" action="./processing/facultyprocessing.php" method="POST" onsubmit="return validateForm()">
                    <input type="text" name="action" id="" value="login" hidden>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required/>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required/>
                    </div>
                    <div class="btn mt-5 d-flex justify-content-center">
                        <button type="submit" class="d-flex justify-content-center">Login</button>
                    </div>
                    <?php if (isset($_SESSION['error']) && $_SESSION['error'] == 'wrongpassword'): ?>
                        <p class="text-danger pl-4">Wrong Password!</p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    </main>


</body>
<?php if (isset($_SESSION['error']) && $_SESSION['error'] == 'wrongpassword'): ?>
    <script>
    window.onload = function() {
        document.getElementById("loginModal").style.display = "block";
    };
</script>

    <?php endif; ?>


<script src="./js/login.js"></script>
</html>
