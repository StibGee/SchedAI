<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Logout</a></li>
                </ul>
            </div>
        </div>
    </header>
    <div id="loading-screen" class="loading-screen visible">
        <svg>
            <text x="50%" y="50%" dy=".35em">
                SchedAi
            </text>
        </svg>
    </div>
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#" class="nav_logo d-flex justify-content-center ">
                    <img src="../img/logo/Sched-logo1.png" width="100">
                </a>
                <div class="nav_list">
                    <a href="../admin/facultyloading.php" class="nav_link active">
                    <img src="../img/icons/load.png" alt="" width="24">
                        <span class="nav_name">Faculty Loading</span>
                    </a>
                    <a href="../admin/schedule.php" class="nav_link">
                    <img src="../img/icons/sched.png" alt="" width="24">
                        <span class="nav_name">Schedule</span>
                    </a>
                    <a href="../admin/academic-plan.php" class="nav_link">
                    <img src="../img/icons/files.png" alt="" width="24">
                        <span class="nav_name">Curriculum Plan</span>
                    </a>
                    <a href="../admin/faculty.php" class="nav_link">
                    <img src="../img/icons/faculty.png" alt="" width="24">
                        <span class="nav_name">Faculty</span>
                    </a>
                    <a href="../admin/room.php" class="nav_link">
                    <img src="../img/icons/room.png" alt="" width="24">
                        <span class="nav_name">Rooms</span>
                    </a>
                    <a href="../admin/account.php" class="nav_link">
                    <img src="../img/icons/settings.png" alt="" width="24">
                        <span class="nav_name">Accounts</span>
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
            // Simulate a delay (e.g., 3 seconds)
            setTimeout(function() {
                // Hide the loading screen
                document.getElementById('loading-screen').style.display = 'none';
                // Show the content
                document.getElementById('content').style.display = 'block';

                // Fetch content from PHP (optional)
                fetch('content.php')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('content').innerHTML = data;
                    });
            }, 1500); // 3000 milliseconds = 3 seconds
        });
        document.addEventListener("DOMContentLoaded", function() {
            // Get all navigation links
            const navLinks = document.querySelectorAll('.nav_link');

            // Loop through each link
            navLinks.forEach(link => {
                // Check if the link's href matches the current page's URL
                if (link.href === window.location.href) {
                    link.classList.add('active-link');
                }
            });
        });

});

</script>
