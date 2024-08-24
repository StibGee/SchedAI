<?php session_start();?>
<body id="body-pd">
    <header class="header" id="header">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
        <div class="user d-flex justify-content-center align-items-center">
            <div class="header-text">
                <h5><?php echo $_SESSION['fname'];?></h5>
            </div>
            <img src="../img/icons/user.png" width="40" height="40" alt="">
        </div>
    </header>
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#" class="nav_logo d-flex justify-content-center ">
                    <img src="../img/logo/Sched-logo1.png" width="100">
                </a>
                <div class="nav_list">
                    <a href="../admin/facultyloading.php" class="nav_link active">
                    <i class="fa-solid fa-spinner"></i>
                        <span class="nav_name">Faculty Loading</span>
                    </a>
                    <a href="../admin/schedule.php" class="nav_link">
                    <i class="fa-regular fa-calendar"></i>
                        <span class="nav_name">Schedule</span>
                    </a>
                    <a href="../admin/academic-plan.php" class="nav_link">
                    <i class="fa-solid fa-diagram-project"></i>
                        <span class="nav_name">Academic Plan</span>
                    </a>
                    <a href="../admin/faculty.php" class="nav_link">
                    <i class="fa-solid fa-users"></i>
                        <span class="nav_name">Faculty</span>
                    </a>
                    <a href="../admin/room.php" class="nav_link">
                    <i class="fa-solid fa-shop"></i>
                        <span class="nav_name">Rooms</span>
                    </a>
                    <a href="../admin/account.php" class="nav_link">
                    <i class="fa-solid fa-gears"></i>
                        <span class="nav_name">Accounts</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>
</body>
