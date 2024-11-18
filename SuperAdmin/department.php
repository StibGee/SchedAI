<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body>

    <?php

        require_once('../include/admin-nav.php');
        require_once('../classes/db.php');
        require_once('../classes/department.php');
        if (isset($_POST['collegeid'])){
            $collegeid=$_POST['collegeid'];
            $_SESSION['collegeid']=$collegeid;
        }else{
            $collegeid=$_SESSION['collegeid'];
        }
        

        $db = new Database();
        $pdo = $db->connect();

        $department = new Department($pdo);
        $collegedepartment = $department->getcollegedepartment($collegeid);
    ?>
<main>
<div class="container mb-1">
            <div class="row d-flex align-items-center">
                <div class="col-9">
                    <h3>
                        <button class="button" onclick="window.location.href='colleges.php'">
                            <i class="fa-solid fa-circle-arrow-left"></i>
                        </button>
                        Department
                        
                    </h3>
                </div>
                <div class="col-3 d-flex align-items-center justify-content-center">
                        <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                        
                        </div>
            </div>

            <div class="colleges mt-4">
                <table id="example">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Department Abbreviation</th>
                            <th>Department</th>
                        
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count=1; foreach($collegedepartment as $collegedepartments){ ?>
                        <tr data-href="college_details.html">
                            <td class="seal-cell"><?php echo $count;?></td>
                            <td><?php echo $collegedepartments['abbreviation'];?></td>
                            <td><?php echo $collegedepartments['name'];?></td>
                          
                    
            
                            <td>
                                <!--div class="action-dropdown">
                                    <button class="action-button">...</button>
                                    <div class="action-dropdown-content">
                                        <a href="#">View Faculty</a>
                                        <a href="#">View Schedule</a>
                                    </div>
                                </div>-->
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editdepartment<?php echo $collegedepartments['id']; ?>" onclick="event.stopPropagation();"><i class="fas fa-edit"></i></button>
                                <form action="../processing/departmentprocessing.php" method="post" style="display:inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $collegedepartments['id']; ?>">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this department?');"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                        <!-- Modal Form -->
                        <div class="modal fade" id="editdepartment<?php echo $collegedepartments['id']; ?>" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg mt-6" role="document">
                            <div class="modal-content border-0">
                                <div class="modal-header border-0">
                                    <h4 class="modal-title" id="formModalLabel">Edit Department</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body px-5">
                                    <form action="../processing/departmentprocessing.php" method="POST">
                                        <input type="text" value="editdepartment" name="action" hidden>
                                        <input type="number" value="<?php echo $collegedepartments['id'];?>" name="departmentid" hidden>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="subname">Department Name</label>
                                                    <input class="form-control" id="subname" type="text" name="departmentname" value="<?php echo $collegedepartments['name'];?>" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="subname">Department Abbreviation</label>
                                                    <input class="form-control" id="subname" type="text" name="abbreviation" value="<?php echo $collegedepartments['abbreviation'];?>" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="subname">Year levels</label>
                                                    <input class="form-control" id="yearlvl" type="number" name="yearlvl" value="<?php echo $collegedepartments['yearlvl'];?>" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <!--<label for="profile-image">Upload Seal</label>
                                                    <div class="profile-image-container">
                                                        <label for="profile-image">
                                                            <img id="profile-image-preview" src="../img/icons/upload.png" alt="Profile Image" />
                                                        </label>
                                                        <input type="file" id="profile-image" name="profile-image" accept="image/*" style="display: none;" />
                                                    </div>-->
                                                </div>
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
                        <?php $count+=1; } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal Form -->
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mt-6" role="document">
            <div class="modal-content border-0">
                <div class="modal-header border-0">
                    <h4 class="modal-title" id="formModalLabel">Add New Department</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5">
                    <form class="needs-validation" novalidate action="../processing/departmentprocessing.php" method="POST">
                        <input type="text" value="add" name="action" hidden>
                        <input type="number" value="<?php echo $collegeid;?>" name="collegeid" hidden>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="subname">Department Name</label>
                                    <input class="form-control" id="subname" type="text" name="departmentname" pattern="^[A-Za-z\s]+$" minlength="2" maxlength="50" required />
                                    <div class="invalid-feedback">
                                    Please enter a valid department abbreviaton.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="subname">Department Abbreviation</label>
                                    <input class="form-control" id="subname" type="text" name="abbreviation" pattern="^[A-Za-z\s]+$" minlength="2" maxlength="50" required />
                                    <div class="invalid-feedback">
                                    Please enter a valid department abbreviation.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="subname">Year levels</label>
                                    <input class="form-control" id="yearlvl" type="number" name="yearlvl" minlength="1" maxlength="9" min="1" required />
                                    <div class="invalid-feedback">
                                    Please enter a valid year level.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <!--<label for="profile-image">Upload Seal</label>
                                    <div class="profile-image-container">
                                        <label for="profile-image">
                                            <img id="profile-image-preview" src="../img/icons/upload.png" alt="Profile Image" />
                                        </label>
                                        <input type="file" id="profile-image" name="profile-image" accept="image/*" style="display: none;" />
                                    </div>-->
                                </div>
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

</main>
</body>
<link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/superadmin/department.css">
    <script src="../js/facultyloading.js"></script>
    <?php
        require_once('../include/js.php')
    ?>
</html>

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