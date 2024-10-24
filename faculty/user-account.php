<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
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
            <main>
            <div class="container ">
                <div class="row">
                    <div class="text d-flex align-items-center" >
                        <h2> Hola !!! </h2> <span><?php echo  $facultyinfo['fname'];?></span>
                    </div>
                </div>
                <h5>My Account</h5>
                <div class="container mt-5">
                    <form>
                        <div class="form-group">
                            <label for="email">Username</label>
                            <input readonly type="email" class="form-control" id="email" placeholder="Enter email"  value="<?php echo $facultyinfo['username'];?>">
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

        </main>
</body>
<script src="../js/main.js"></script>
<link rel="stylesheet" href="../css/main.css">
<link rel="stylesheet" href="../css/faculty-css/user-account.css">

<?php
        require_once('../include/js.php')
    ?>


</html>
