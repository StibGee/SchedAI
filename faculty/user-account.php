<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>
<?php
        require_once('../include/js.php')
    ?>

<body>

    <?php

        require_once('../include/user-mainnav.php');
        require_once('../classes/db.php');
        require_once('../classes/faculty.php');
        require_once('../classes/department.php');
        $db = new Database();
        $pdo = $db->connect();

        $faculty = new Faculty($pdo);
        $department = new Department($pdo);
        $facultyinfo=$faculty->getfacultyinfo($_SESSION['id']);
        $facultysubjects=$faculty->getfacultysubjects($_SESSION['id']);
        $facultypreference=$faculty->getfacultydaytime($_SESSION['id']);
    ?>
<main class="col-sm-10 pb-5" style="height: 100vh;" id="main">
    <!-- NavBar -->
    <nav class="navbar sticky-top navbar-expand-lg border-bottom bg-body d-flex">
    <div class="container-fluid ">
        <div class="button col-4 col-sm-4">
            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseWidthExample" aria-expanded="true" aria-controls="collapseWidthExample"
                style="margin-right: 10px; padding: 0px 5px 0px 5px;" id="sidebartoggle" onclick="changeclass()">
                <i class="bi bi-arrows-expand-vertical"></i>
            </button>
            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"
                style="margin-right: 10px; padding: 2px 6px 2px 6px;" id="sidebarshow">
                <i class="bi bi-arrow-bar-right"></i>
            </button>
        </div>

    <!-- Cambair Tema -->
        <div class="user col-8 col-sm-6 d-flex justify-content-end">

            <!-- Mobile Image -->
            <div class="mobile-image-container col-5">
                <img src="../img/logo/Sched-logo1.png" alt="Mobile Image" class="mobile-image">
            </div>
            <div class="dropdown col-6 d-flex justify-content-end">
                <div class="header-text ">
                    <h5><?php echo $_SESSION['fname'];?></h5>
                </div>
                <img src="../img/icons/user.png" width="30" height="30" alt="" class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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
    </div>
    </nav>
    <div class="container custom-container p-4">
        <div class="row py-2 ">
            <span class="text-head">Account Settings</span>
        </div>
        <h5>Account Settings</h5>
        <div class="container ">
    <label for="Account Settings">Password and Security</label>
    <form>
        <div class="form-group p-3">
            <label for="email">Username</label>
            <input readonly type="email" class="form-control" id="email" placeholder="Enter email" value="<?php echo $facultyinfo['username'];?>">
        </div>

        <div class="form-group forgot-password">
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                Change Password
            </button>
        </div>
    </form>
</div>

            </div>
            <!-- Modal -->
            <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="forgotPasswordModalLabel">Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form method="POST" action="../processing/facultyprocessing.php">
                        <input type="text" name="action" value="changepass" hidden>
                        <input type="facultyid" name="facultyid" value="<?php echo $_SESSION['id'];?>" hidden>
                        <div class="mb-3">
                            <label for="text" class="form-label">Old Password</label>
                            <input type="text" class="form-control" id="email" placeholder="Enter email" name="oldpass">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Enter new password" name="newpass">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                    </form>
                    </div>
                </div>
            </div>
    </div>

    </main>
</body>
<script src="../js/main.js"></script>
<link rel="stylesheet" href="../css/faculty-css/user-account.css">


<?php
        require_once('../include/js.php')
    ?>

  <script>
    function changeclass() {
      $("#main").toggleClass('col-sm-10 col-sm-12');
    }
  </script>
<script src="color-modes.js"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</html>


</html>
