<?php session_start();?>
<body id="body-pd">
    <header class="header px-5" id="header">
            <div class="row">
                <div class="text d-flex align-items-center justify-content-center m-0">
                    <h2> Hola !!! </h2><span></span>
                </div>
            </div>
        <div class="user d-flex justify-content-center align-items-center">
            <div class="header-text">
                <h5><?php echo $_SESSION['fname'];?></h5>
            </div>
            <div class="dropdown">
                <img src="../img/icons/user.png" width="40" height="40" alt="" class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="../admin/facultyloading.php">Profile</a></li>
                    <li><a class="dropdown-item" href="../faculty/profiling.php">user(temporary)</a></li>
                    <li>
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
        a{
            text-decoration: none !important;
        }
</style>
