<?php session_start(); ?>

<body id="body-pd">
    <header class="header" id="header">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
        <div class="user d-flex justify-content-center align-items-center">
            <div class="header-text">
                <h5><?php echo $_SESSION['fname']; ?></h5>
            </div>
            <div class="dropdown">
                <img src="../img/icons/user.png" alt="" class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                    <li class="ms-3"> 
                        <form action="../processing/facultyprocessing.php" method="POST" style="display: inline;">
                            <input type="text" name="action" value="logout" hidden>
                            <button type="submit" name="logout" class="dropdown-item" style="background: none; border: none; padding: 0; margin: 0;">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#" class="nav_logo d-flex justify-content-center">
                    <img src="../img/logo/Sched-logo1.png" width="100" alt="Logo">
                </a>
                <div class="nav_list">
                    <a href="../faculty/dashboard.php" class="nav_link activenav">
                        <i class="fa-solid fa-spinner"></i>
                        <span class="nav_name">Assigned Schedule</span>
                    </a>
                    <a href="../faculty/profile.php" class="nav_link">
                        <i class="fa-regular fa-calendar"></i>
                        <span class="nav_name">My Profile</span>
                    </a>
                    <a href="../faculty/user-account.php" class="nav_link">
                        <i class="fa-solid fa-gear"></i>
                        <span class="nav_name">Account</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>

    

    
</body>
</html>
