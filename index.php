<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/login.css">

</head>
<body>
    <main>
        <div class="row landing  m-0">
            <div class="col-5 p-0">
                <div class="carousel">
                    <img src="/assets/img/logo/Sched-logo1.png" alt="">
                </div>
            </div>
            <div class="container col-5 p-0">
                <h2> College Of Computing Studies</h2>
                <h4> Western Mindanao State University</h4>
                <div class="admin-login">
                    <button id="admin-log"><img src="/assets/img/icons/admin.png" width="25" alt=""> Admin</button>

                </div>
                <div class="faculty-login">
                    <button id="faculty-log"><img src="/assets/img/icons/faculty.PNG" width="25" alt=""> faculty</button>
                </div>
                <div id="loginModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <form id="loginForm" action="./resources/admin/facultyloading.php" method="GET" onsubmit="return validateForm()">
                            <h2><span class="entypo-login"><i class="fa fa-sign-in"></i></span> Login</h2>
                            <button type="submit" class="submit"><span class="entypo-lock"><i class="fa fa-lock"></i></span></button>
                            <span class="entypo-user inputUserIcon">
                                <i class="fa fa-user"></i>
                            </span>
                            <input type="text" class="user" id="username" placeholder="username" required/>
                            <span class="entypo-key inputPassIcon" onclick="togglePasswordVisibility()">
                                <i class="fa fa-eye" id="eyeIcon"></i>
                            </span>
                            <input type="password" class="pass" id="password" placeholder="password" required/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>


</body>
<script src="/assets/js/login.js"></script>
</html>
