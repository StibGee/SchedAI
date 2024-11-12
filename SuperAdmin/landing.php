<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
        $_SESSION['currentpage']='dashboard';
    ?>

<body>

    <?php

        require_once('../include/admin-nav.php');
        require_once('../include/admin-nav.php');
        require_once('../classes/db.php');
        require_once('../classes/college.php');
        require_once('../classes/faculty.php');

        $db = new Database();
        $pdo = $db->connect();

        $college = new College($pdo);
        $countcollege = $college->countallcollege();
        $faculty = new Faculty($pdo);
        $countfaculty = $faculty->countallfaculty();
    ?>
<main>
<div class="container dashboard">
        <div class="row">
            <div class="text d-flex align-items-center">
                <h2>Hola !!!</h2> <span>Admin</span>
            </div>
        </div>
        <div class="container mt-2 ">
            <div class="row">
                <!-- Number of Colleges -->
                <div class="col-md-4">
                    <div class="card" onclick="window.location.href='../SuperAdmin/colleges.php'">
                        <div class="card-body">
                            <h5 class="card-title">Number of Colleges</h5>
                            <p class="card-text"><?php echo $countcollege;?></p>
                        </div>
                    </div>
                </div>
                <!-- Number of Users -->
                <div class="col-md-4" onclick="window.location.href='../SuperAdmin/users.php'">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Number of Faculty</h5>
                            <p class="card-text"><?php echo $countfaculty;?></p>
                        </div>
                    </div>
                </div>

                <!-- Number of Colleges that Created Schedule -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Colleges with Schedules</h5>
                            <p class="card-text">4</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex text-center mt-5">
                <div class="label ">
                    <h4>Western  Mindanao State University</h4>
                    <h3>Colleges</h3>
                </div>
                <div class="college mt-5">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <a href="../SuperAdmin/colleges.php">
                                <img src="../img/logo/ccslogo.png" alt="College 1 Logo" class="img-fluid" style="max-height: 100px;">
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="../SuperAdmin/colleges.php">
                                <img src="../img/logo/ccslogo.png" alt="College 2 Logo" class="img-fluid" style="max-height: 100px;">
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="../SuperAdmin/colleges.php">
                                <img src="../img/logo/ccslogo.png" alt="College 3 Logo" class="img-fluid" style="max-height: 100px;">
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="../SuperAdmin/colleges.php">
                                <img src="../img/logo/ccslogo.png" alt="College 4 Logo" class="img-fluid" style="max-height: 100px;">
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="../SuperAdmin/colleges.php">
                                <img src="../img/logo/ccslogo.png" alt="College 4 Logo" class="img-fluid" style="max-height: 100px;">
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="../SuperAdmin/colleges.php">
                                <img src="../img/logo/ccslogo.png" alt="College 4 Logo" class="img-fluid" style="max-height: 100px;">
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</main>
</body>
<link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/superadmin/dashboard.css">
    <script src="../js/facultyloading.js"></script>
    <?php
        require_once('../include/js.php')
    ?>
</html>
