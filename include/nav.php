<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
    if ($_SESSION['role'] != 'collegesecretary' && $_SESSION['role'] != 'departmenthead') {
        header("Location: ../index.php");
        exit();
    }    
       
}
?>
<?php
        require_once('../include/js.php')
    ?>

<?php
        require_once('../include/head.php');
    ?>
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
                <li><form action="../processing/facultyprocessing.php" method="POST" style="display: inline;">
                            <input type="text" name="action" value="logout" hidden>
                            <button type="submit" name="logout" class="dropdown-item px-3" style="background: none; border: none; padding: 0; margin: 0;">
                                Logout 
                            </button>
                        </form></li>
                </ul>
            </div>
        </div>
    </header>
    <?php if ($_SESSION['loading']==1){ ?>
    <div id="loading-screen" class="loading-screen visible">
        <svg>
            <text x="50%" y="50%" dy=".35em">
                SchedAi
            </text>
        </svg>
    </div>
    <?php } ?>
    <?php $_SESSION['loading']=0; ?>
    <div class="l-navbar " id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#" class="nav_logo d-flex justify-content-center ">
                    <img src="../img/logo/Sched-logo1.png" width="100">
                </a>
                <div class="nav_list">
                    <a href="../admin/facultyloading.php" class="nav_link <?php if ($_SESSION['currentpage']=='landing'){ echo 'active'; }?>">
                    <img src="../img/icons/load.png" alt="" width="24">
                        <span class="nav_name">Faculty Loading</span>
                    </a>
                    <a href="../admin/schedule.php" class="nav_link <?php if ($_SESSION['currentpage']=='schedule'){ echo 'active'; }?>">
                    <img src="../img/icons/sched.png" alt="" width="24">
                        <span class="nav_name">Schedule</span>
                    </a>
                    <a href="../admin/academic-plan.php" class="nav_link <?php if ($_SESSION['currentpage']=='curriculum'){ echo 'active'; }?>">
                    <img src="../img/icons/files.png" alt="" width="24">
                        <span class="nav_name">Curriculum Plan</span>
                    </a>
                    <a href="../admin/faculty.php" class="nav_link <?php if ($_SESSION['currentpage']=='faculty'){ echo 'active'; }?>">
                    <img src="../img/icons/faculty.png" alt="" width="24">
                        <span class="nav_name">Faculty</span>
                    </a>
                    <a href="../admin/room.php" class="nav_link <?php if ($_SESSION['currentpage']=='room'){ echo 'active'; }?>">
                    <img src="../img/icons/room.png" alt="" width="24">
                        <span class="nav_name">Rooms</span>
                    </a>
                    <a href="../admin/account.php" class="nav_link <?php if ($_SESSION['currentpage']=='account'){ echo 'active'; }?>">
                    <img src="../img/icons/settings.png" alt="" width="24">
                        <span class="nav_name">Accounts</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>
</body>

<style>
        .dropdown img {
            width: 40px;
            height: 40px;
            object-fit: cover; /* Ensures the image covers the area without stretching */
            border-radius: 50%; /* Optional: Makes the image circular */
        }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.querySelector('.header_toggle');
    const navNames = document.querySelectorAll('.nav_logo-name, .nav_name');

    navToggle.addEventListener('click', function() {
        navNames.forEach(navName => {
            navName.classList.toggle('hide-on-minimize');
            navName.classList.toggle('show-on-minimize');
        });
    });
    window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loading-screen').style.display = 'none';
                document.getElementById('content').style.display = 'block';

                fetch('content.php')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('content').innerHTML = data;
                    });
            }, 1500); 
        });
        document.addEventListener("DOMContentLoaded", function() {
            const navLinks = document.querySelectorAll('.nav_link');

            navLinks.forEach(link => {
                if (link.href === window.location.href) {
                    link.classList.add('fc');
                }
            });
        });

});

</script>
