<?php session_start();?>
<body id="body-pd">
    <header class="header" id="header">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
        <div class="user d-flex justify-content-center align-items-center">
            <div class="header-text">
                <h5><?php echo $_SESSION['fname'];?></h5>
            </div>
            <div class="dropdown">
                <img src="../img/icons/user.png" width="40" height="40" alt="" class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="../SuperAdmin/profile.php">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Logout</a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#" class="nav_logo d-flex justify-content-center ">
                    <img src="../img/logo/Sched-logo1.png" width="100">
                </a>
                <div class="nav_list">
                <a href="../SuperAdmin/landing.php" class="nav_link active">
                    <img src="../img/icons/dashboard.png" alt="" width="24">
                        <span class="nav_name">Dashboard</span>
                    </a>
                    <a href="../SuperAdmin/users.php" class="nav_link active">
                    <img src="../img/icons/faculty.png" alt="" width="24">
                        <span class="nav_name">Users</span>
                    </a>
                    </a>
                    <a href="../SuperAdmin/colleges.php" class="nav_link">
                    <img src="../img/icons/home.png" alt="" width="24">
                        <span class="nav_name">Colleges</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>
</body>
<?php
        require_once('../include/js.php')
    ?>

<?php
        require_once('../include/head.php');
    ?>
<style>
        .dropdown img {
            width: 40px;
            height: 40px;
            object-fit: cover; /* Ensures the image covers the area without stretching */
            border-radius: 50%; /* Optional: Makes the image circular */
        }
</style>
