<?php
if (!isset($_SESSION['role'])){
    header("Location: ../index.php");
    exit();
}
if ($_SESSION['role'] == 'collegesecretary' || $_SESSION['role'] == 'departmenthead') {
    header("Location: ../index.php");
    exit();
}?>

<body id="body-pd">
    <header class="header" id="header">
        <div class="header_toggle p-1"> <i class='bx bx-menu' id="header-toggle"></i>
          <img src="../img/logo/logo(1).png" width="50" class="logo-img">
       </div>

        <div class="user d-flex justify-content-center align-items-center">
            <div class="header-text">
                <h5><?php echo $_SESSION['fname'];?></h5>
            </div>
            <div class="dropdown">
                <img src="../img/icons/user.png"  alt="" class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#" class="nav_logo d-flex justify-content-center">
                    <img src="../img/logo/logo(1).png" width="100">
                </a>
                <div class="nav_list">
                <a href="../faculty/dashboard.php" class="nav_link <?php if ($_SESSION['currentpage']=='schedule'){ echo 'active'; }?>">
                    <img src="../img/icons/dashboard.png" alt="" width="24">
                        <span class="nav_name"> Schedule</span>
                    </a>
                    <a href="../faculty/profile.php"  class="nav_link <?php if ($_SESSION['currentpage']=='profile'){ echo 'active'; }?>">
                    <img src="../img/icons/faculty.png" alt="" width="24">
                        <span class="nav_name">My Profile</span>
                    </a>
                    </a>
                    <a href="../faculty/user-account.php" class="nav_link <?php if ($_SESSION['currentpage']=='account'){ echo 'active'; }?>">
                    <img src="../img/icons/settings.png" alt="" width="24">
                        <span class="nav_name">Account Settings</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>
</body>

<div id="loading-screen" class="loading-screen visible">
        <svg>
            <text x="50%" y="50%" dy=".35em">
                SchedAi
            </text>
        </svg>
    </div>
<style>
        .dropdown img {
            width: 40px;
            height: 40px;
            object-fit: cover; /* Ensures the image covers the area without stretching */
            border-radius: 50%; /* Optional: Makes the image circular */
        }
        a{
            text-decoration: none !important;
        }
        .active {
            color: var(--white-color);
        }

        .active::before {
            content: '';
            position: absolute;
            left: 0;
            width: 2px;
            height: 32px;
            background-color: var(--white-color);
        }
        .nav_link.active {
            background-color: #7BB883; /* Highlight color */
            color: #fff; /* Text color */
            border-radius: 5px; /* Optional: Rounded corners */
        }

        .nav_link.active::before {
            content: '';
            position: absolute;
            left: 0;
            width: 2px;
            height: 100%;
            background-color: #fff; /* Highlight color for the left border */
        }

</style>
<script>
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
</script>
