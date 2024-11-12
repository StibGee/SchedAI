<!DOCTYPE html>
<html lang="en">
<?php
        
        require_once('../include/head.php');
        $_SESSION['currentpage']='user';
        require_once('../include/admin-nav.php');

        require_once('../classes/db.php');
        require_once('../classes/curriculum.php');
        require_once('../classes/department.php');
        require_once('../classes/college.php');
        require_once('../classes/schedule.php');
        require_once('../classes/faculty.php');
        require_once('../classes/email.php');
        
            
        $scheduling=False;
        $db = new Database();
        $pdo = $db->connect();
        
        
        $curriculum = new Curriculum($pdo);
        $email = new Email($pdo);
        $faculty = new Faculty($pdo);
        $schedule = new Schedule($pdo);
        $college = new College($pdo);
        $department = new Department($pdo);
        
       
        $calendar = $curriculum->getallcurriculumsschedule();
        $alldepartment=$department->getalldepartment();
        $authorizeduser=$faculty->getallauthorizedfaculty();
    ?>

<body>

    <?php

        
        
    ?>
<main>
<div class="container mb-1">
            <div class="row d-flex align-items-center">
                <div class="col-9">
                    <h3>
                        <button class="button" onclick="window.location.href='landing.php'">
                            <i class="fa-solid fa-circle-arrow-left"></i>
                        </button>
                        Authorize Users
                    </h3>
                </div>
                <div class="col-3 d-flex align-items-center justify-content-end">
                        <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                        </div>
            </div>

            <div class="colleges mt-4">

                <table class="mb-0 table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>College/Department</th>
                            <th>Role</th>
                            <th>Email</th>
                            
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1;?>
                        <?php foreach($authorizeduser AS $authorizedusers){?>
                            <tr data-href="../SuperAdmin/department.php">
                                <td><?php echo $i;?></td>
                                <td><?php echo $authorizedusers['fname'].' '.$authorizedusers['lname'];?></td>
                                <td><?php echo ($authorizedusers['role'] == 'collegesecretary') ? $authorizedusers['collegename'] : ($authorizedusers['role'] == 'departmenthead' ? $authorizedusers['departmentname'] : 'No role assigned'); ?></td>
                                <td><?php echo ($authorizedusers['role'] == 'collegesecretary') ? 'College Secretary' : ($authorizedusers['role'] == 'departmenthead' ? 'Department Head' : 'Unknown Role'); ?></td>
                                <td><?php echo $authorizedusers['email'];?></td>
                                <td></td>
                                
                            </tr>
                        <?php $i++; } ?>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal Form -->
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mt-6" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header border-0">
                        <h4 class="modal-title ml-3" id="formModalLabel">Add Authorize User</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-5">
                        <form action="../processing/facultyprocessing.php" method="POST">
                            <input type="text" value="addrootfaculty" name="action" hidden>
                            <div class="row">
                                
                                <div class="form-group col-md-4">
                                    <label class="form-label" for="departmentid">Select Department</label>
                                    <select class="form-select" id="departmentid" name="departmentid" required="">
                                        <option selected="" disabled="">Select Department</option>
                                        <?php foreach ($alldepartment AS $alldepartments){?>
                                            <option value="<?php echo $alldepartments['id'];?>"><?php echo $alldepartments['abbreviation'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="form-label" for="role">Select Role</label>
                                    <select class="form-select" id="role" name="role" required="">
                                        <option selected="" disabled="">Select Role</option>
                                        <option value="departmenthead">Department Head</option>
                                        <option value="collegesecretary">College Secretary</option>
            
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="emailadd">Email Address</label>
                                    <input type="email" class="form-control" id="emailadd" name="emailadd" required>
                                </div>
                            </div>
                           
                            <div class="row mt-3">
                                <div class="col-md-5">
                                    <label class="form-label" for="fname">First Name</label>
                                    <input type="text" class="form-control" id="fname" name="fname" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label" for="lname">Last Name</label>
                                    <input type="text" class="form-control" id="lname" name="lname" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="mname">MI (Optional)</label>
                                    <input type="text" class="form-control" id="mname" name="mname">
                                </div>

                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="email">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>

                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Done</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

</main>
</body>
<link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/superadmin/users.css">
    <script src="../js/facultyloading.js"></script>
    <?php
        require_once('../include/js.php')
    ?>
</html>
