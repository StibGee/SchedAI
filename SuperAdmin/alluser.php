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
        $authorizeduser=$faculty->getallfacultylol();
    ?>

<body>

    <?php

        
        
    ?>
<main>
<div class="container mb-1">
<div class="container mt-5">
        
            <div class="row d-flex align-items-center">
                <div class="col-9">
                    <h3>
                        <button class="button" onclick="window.location.href='landing.php'">
                            <i class="fa-solid fa-circle-arrow-left"></i>
                        </button>
                        All Users
                    </h3>
                </div>
                <div class="col-3 d-flex align-items-center justify-content-end">
                        <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                        </div>
            </div>
            <div class="container mt-5">
                <div class="d-flex gap-3">
                    <a href="alluser.php" class="btn btn-success">All Users</a>
                    <a href="users.php" class="btn btn-secondary">Authorized Users</a>
                </div>
            </div>

            <div class="colleges mt-4">

                <table id="example" class="mb-0 table">
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
                                <td><?php echo ($authorizedusers['role'] == 'collegesecretary') ? $authorizedusers['collegename'] : ($authorizedusers['role'] == 'departmenthead' ? $authorizedusers['departmentname'] : $authorizedusers['departmentname']); ?></td>
                                <td><?php echo ($authorizedusers['role'] == 'collegesecretary') ? 'College Secretary' : ($authorizedusers['role'] == 'departmenthead' ? 'Department Head' : 'Faculty'); ?></td>
                                <td><?php echo $authorizedusers['email'];?></td>
                                <td>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edituserform<?php echo $authorizedusers['facultyid']; ?>" onclick="event.stopPropagation();"><i class="fas fa-edit"></i></button>
                                    <form action="../processing/facultyprocessing.php" method="post" style="display: inline;">
                                            <input type="hidden" name="action" value="deleteadmin">
                                            <input type="hidden" name="id" value="<?php echo $authorizedusers['facultyid']; ?>">
                                            <button type="submit" class="btn btn-danger ms-1" onclick="return confirm('Are you sure you want to delete this faculty?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                    </form>
                                </td>
                                
                            </tr>
                            <div class="modal fade" id="edituserform<?php echo $authorizedusers['facultyid']; ?>" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg mt-6" role="document">
                                    <div class="modal-content border-0">
                                        <div class="modal-header border-0">
                                            <h4 class="modal-title ml-3" id="formModalLabel">Edit User</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body px-5">
                                            <form class="needs-validation" novalidate action="../processing/facultyprocessing.php" method="POST">
                                                <input type="text" value="<?php echo $authorizedusers['facultyid'];?>" name="facultyid" hidden>
                                                <input type="text" value="updaterootfaculty" name="action" hidden>
                                                <div class="row">
                                                    
                                                    <div class="form-group col-md-4">
                                                        <label class="form-label" for="departmentid">Select Department</label>
                                                        <select class="form-select" id="departmentid" name="departmentid" required="">
                                                            <option disabled>Select Department</option>
                                                            <?php foreach ($alldepartment AS $alldepartments){?>
                                                                <option <?php if($alldepartments['id']==$authorizedusers['departmentid']){ echo 'selected';};?> value="<?php echo $alldepartments['id'];?>"><?php echo $alldepartments['abbreviation'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="invalid-feedback">
                                                    Please enter a department.
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label class="form-label" for="role">Select Role</label>
                                                        <select class="form-select" id="role" name="role" required="">
                                                            <option disabled>Select Role</option>
                                                            <option <?php if($authorizedusers['role']=='departmenthead'){ echo 'selected';};?>  value="departmenthead">Department Head</option>
                                                            <option <?php if($authorizedusers['role']=='collegesecretary'){ echo 'selected';};?>  value="collegesecretary">College Secretary</option>
                                                            <option <?php if($authorizedusers['role']=='faculty'){ echo 'selected';};?>  value="faculty">Faculty</option>
                                
                                                        </select>
                                                        <div class="invalid-feedback">
                                                        Please select a role.
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label" for="emailadd">Email Address</label>
                                                        <input type="email" class="form-control" id="emailadd" name="emailadd" value="<?php echo $authorizedusers['email']; ?>">
                                                        <div class="invalid-feedback">
                                                        Please enter a valid email address.
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                <div class="row mt-3">
                                                    <div class="col-md-5">
                                                        <label class="form-label" for="fname">First Name</label>
                                                        <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $authorizedusers['fname'];?>" pattern="^[A-Za-z\s]+$" minlength="2" maxlength="50" required>
                                                    </div>
                                                    <div class="invalid-feedback">
                                                    Please enter a valid First Name.
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="form-label" for="lname">Last Name</label>
                                                        <input type="text" class="form-control" id="lname" name="lname" value="<?php echo $authorizedusers['lname'];?>" pattern="^[A-Za-z\s]+$" minlength="2" maxlength="50" required>
                                                    </div>
                                                    <div class="invalid-feedback">
                                                    Please enter a valid Last Name.
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label" for="mname">MI (Optional)</label>
                                                        <input type="text" class="form-control" id="mname" name="mname" value="<?php echo $authorizedusers['mname'];?>" pattern="^[A-Za-z\s]+$" minlength="1" maxlength="50">
                                                    </div>
                                                    <div class="invalid-feedback">
                                                    Please enter a valid Middle Name.
                                                    </div>

                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="email">Username</label>
                                                        <input type="text" class="form-control" id="username" value="<?php echo $authorizedusers['username'];?>" name="username" minlength="2" maxlength="50" required>
                                                    </div>
                                                    <div class="invalid-feedback">
                                                    Please enter a valid username.
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="password">Password</label>
                                                        <input type="password" class="form-control" id="password" name="password" minlength="5" maxlength="50" required>
                                                    </div>
                                                    <div class="invalid-feedback">
                                                    Please enter at least 8 characters.
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
                        <?php $i++; } ?>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
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

<script>
    
    (() => {
      'use strict';
      
     
      const forms = document.querySelectorAll('.needs-validation');

      
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    })();
  </script>
  <script>
    $(document).ready(function() {
        $('#example').DataTable({
            "lengthChange": false  
        });
    });
</script>
</html>
