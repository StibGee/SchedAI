<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
        $_SESSION['currentpage']='faculty';

        if (!isset($_GET['faculty'])){
            $_SESSION['loading']=1;
        }
    ?>

<link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/faculty.css">
<body >

    <?php
        require_once('../include/nav.php');
        require_once('../classes/db.php');
        require_once('../classes/faculty.php');
        require_once('../classes/department.php');

        $db = new Database();
        $pdo = $db->connect();

        $faculty = new Faculty($pdo);
        $department = new Department($pdo);

        if($_SESSION['scheduling']=='college'){
            $departmentall = $department->getalldepartment();
            $facultyall = $faculty->getallfacultycollege($_SESSION['collegeid']);
        }else{
            $departmentall = $department->getalldepartment();
            $facultyall = $faculty->getallfacultydepartment($_SESSION['departmentid']);
        }
        $collegedepartment = $department->getcollegedepartment($_SESSION['collegeid']);
        

        

    ?>

    <main>
        <div class="container mb-1 containerfaculty">
            <div class="row  d-flex align-items-center">
                <div class="header-table col-3">
                    <h3>Instructors</h3>
                </div>
                    <div class="col-3">
                        <!--<select class="form-select form-select-sm col" id="select-department">
                            <option>Institute of Technology</option>
                            <option>Computer Science</option>
                        </select>-->
                    </div>
                    <div class="col-1">
                        <!--<select class="form-select  form-select-sm " id="select-position">
                            <option>all</option>
                            <option>Dean</option>
                            <option>Visiting</option>
                        </select>-->
                    </div>

                    <div class="searchbar col-3 ">
                        <!--<input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">-->
                    </div>
                    <div class="col-2 add-faculty d-flex justify-content-end">
                    <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                    </div>

            </div>

            <div class="sched-container my-4 p-3">
                <div class="sched-table ">
                        <table id="example" class="table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Contact No.</th>
                                    <th>Department</th>
                                    <th>Teaching Hours</th>
                                    <th>Type</th>
                                    <th>Degree</th>
                                    <th>Start Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tabularTableBody">
                                <?php $i=1; foreach ($facultyall AS $facultys){ ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $facultys['fname']." ".$facultys['mname']." ".$facultys['lname']; ?></td>
                                    <td><?php echo $facultys['gender']; ?></td>
                                    <td><?php echo $facultys['contactno']; ?></td>
                                    <td><?php echo $facultys['abbreviation']; ?></td>
                                    <td><?php echo $facultys['teachinghours']; ?></td>
                                    <td><?php echo $facultys['type']; ?></td>
                                    <td><?php echo $facultys['rank']; ?></td>
                                    <td><?php echo $facultys['startdate']; ?></td>
                                    <td>
                                    <div style="display: flex;">
                                        <!-- Edit Icon -->
                                        <form action="profiling.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="facultyid" value="<?php echo $facultys['facultyid']; ?>">
                                            <button type="submit" class="btn btn-warning" >
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </form>


                                        <form action="../processing/facultyprocessing.php" method="post" style="display: inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $facultys['facultyid']; ?>">
                                            <button type="submit" class="btn btn-danger ms-1" onclick="return confirm('Are you sure you want to delete this faculty?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>

                                    </td>
                                </tr>
                                <?php $i+=1;} ?>
                            </tbody>
                        </table>


                    </div>

                </div>

            </div>
        </div>
        <!-- Modal Form -->
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mt-6" role="document">
                <div class="modal-content border-0">
                    <div class="modal-body p-3">
                        <div class="close-btn-container position-absolute top-0 end-0 mt-3 me-3">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="scrollable-content form-content p-4" style="max-height: 400px; overflow-y: auto;">
                            <h2 class="head-label">Add faculty</h2>
                                <div class="container form">
                                    <form id="facultyForm" class="needs-validation" novalidation action="../processing/facultyprocessing.php" method="POST" class="row g-3" >
                                        <input type="text" name="action" id="" value="addfaculty" hidden>
                                        <input type="number" name="collegeid" id="" value="<?php echo $_SESSION['collegeid'];?>" hidden>
                                        <div class="col-6">
                                            <label class="form-label" for="department">Deparment</label>
                                            <select class="form-select form-select-sm" id="select-classtype" name="departmentid">
                                                <?php foreach ($collegedepartment as $collegedepartments){?>
                                                    <option value="<?php echo $collegedepartments['id'];?>"><?php echo $collegedepartments['name'];?></option>
                                                <?php } ?>
                                                <option value="" >Choose a department</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                    Please enter a department.
                                                </div>
                                        </div>
                                        <h5 class="mt-4">Personal Information</h5>
                                        <div class="row mt-2">
                                            <div class="col-md-5">
                                                <label class="form-label" for="firstname">First name</label>
                                                <input class="form-control" id="firstname" type="text" name="fname" pattern="^[A-Za-z\s]+$" minlength="2" maxlength="50" required>
                                                <div class="invalid-feedback">
                                                    Please enter a valid first name.
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label" for="lastname">Last name</label>
                                                <input class="form-control" id="lastname" type="text" name="lname" pattern="^[A-Za-z\s]+$" minlength="2" maxlength="50" required>
                                                <div class="invalid-feedback">
                                                    Please enter a valid last name.
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label" for="midleinit">Middle Initial</label>
                                                <input class="form-control" id="midleinit" type="text" name="mname" pattern="^[A-Za-z\s]+$" minlength="1" maxlength="50">
                                                <div class="invalid-feedback">
                                                    Please enter a valid middle Initial.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-4">
                                                <label class="form-label" for="contactnumber">Contact Number</label>
                                                <input class="form-control" id="contactnumber" name="contactno" type="number" minlength="11" maxlength="50" required>
                                                <div class="invalid-feedback">
                                                    Please enter a valid contact number (ex. 09xxxxxxxxx).
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="email">Email Address</label>
                                                <input class="form-control" id="email" name="emailadd" type="email" minlength="2" maxlength="50" required>

                                                <div class="invalid-feedback">
                                                    Please enter a valid email address.
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label" for="gender">Gender</label>
                                                <select class="form-select" id="gender" name="gender" required>
                                                    <option disabled>Choose...</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Male">Female</option>
                                                </select>
                                                <div class="invalid-feedback">Please select a Gender</div>
                                            </div>
                                        </div>



                                        <h5  class="mt-4">Faculty Information</h5>
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <label class="form-label" for="position">Type</label>
                                                <select class="form-select" id="position" name="type" required="">
                                                    <option disabled value="">Choose...</option>
                                                    <option value="Regular">Regular </option>
                                                    <option value="Contractual">Contractual</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please enter a type.
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label" for="startdate">Start Date</label>
                                                <input class="form-control" id="startdate" type="date" name="startdate" required>
                                                <div class="invalid-feedback">
                                                    Please enter a valid date.
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-6">
                                                <label class="form-label" for="rank">Highest Degree</label>
                                                <select class="form-select" id="position" name="rank" required="">
                                                    <option disabled>Please select a highest degree </option>
                                                    <option selected value="Bachelors">Bachelors Degree</option>
                                                    <option value="Masters">Masters Degree</option>
                                                    <option value="Doctorate">Doctorate Degree</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label" for="teachinghours">Teaching Hours</label>
                                                <input class="form-control" id="teachinghours" type="number" name="teachinghours" required placeholder="Hours/Week">
                                            </div>

                                        </div>
                                        <h5  class="mt-4">Account Details</h5>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <label class="form-label" for="email">Username</label>
                                                <input class="form-control" id="email" type="text" name="username" minlength="2" maxlength="50" required>
                                                <div class="invalid-feedback">
                                                    Please enter a valid username.
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="password">Password</label>
                                                <input class="form-control" id="password" type="text" name="password" minlength="8" maxlength="50" required>
                                                <div class="invalid-feedback">
                                                    Please enter a password (at least 8 characters).
                                                </div>
                                            </div>
                                        </div>

                                </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit"  class="btn btn-success">Done</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </main>
</body>



    <?php
        require_once('../include/js.php')
    ?>
    <script>
    $(document).ready(function() {
        $('#example').DataTable({
            "lengthChange": false
        });
    });
</script>


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
