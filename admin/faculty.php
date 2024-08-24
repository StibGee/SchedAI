<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body >

    <?php
        require_once('../include/nav.php');
        require_once('../database/datafetch.php');
    ?>

    <main>
        <div class="container mb-1">
            <div class="row">
                <div class="text d-flex align-items-center ">
                    <h2> Hola !!! </h2> <span> Role</span>
                </div>
            </div>
            <div class="row  d-flex align-items-center">
                <div class="header-table col-3">
                    <h3>Instructors</h3>
                </div>
                    <div class="col-3">
                        <select class="form-select form-select-sm col" id="select-department">
                            <option>Institute of Technology</option>
                            <option>Computer Science</option>
                        </select>
                    </div>
                    <div class="col-1">
                        <select class="form-select  form-select-sm " id="select-position">
                            <option>all</option>
                            <option>Dean</option>
                            <option>Visiting</option>
                        </select>
                    </div>

                    <div class="searchbar col-3 ">
                        <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
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
                                    <th>ID Number</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Contact No.</th>
                                    <th>Department</th>
                                    <th>Teaching Hours</th>
                                    <th>Type</th>
                                    <th>Rank</th>
                                    <th>Start Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tabularTableBody">
                                <?php foreach ($faculty AS $facultys){ ?>
                                <tr>
                                    <td><?php echo $facultys['facultyid']; ?></td>
                                    <td><?php echo $facultys['fname']." ".$facultys['mname']." ".$facultys['lname']; ?></td>
                                    <td><?php echo $facultys['gender']; ?></td>
                                    <td><?php echo $facultys['contactno']; ?></td>
                                    <td><?php echo $facultys['departmentname']; ?></td>
                                    <td><?php echo $facultys['teachinghours']; ?></td>
                                    <td><?php echo $facultys['type']; ?></td>
                                    <td><?php echo $facultys['rank']; ?></td>
                                    <td><?php echo $facultys['startdate']; ?></td>
                                    <td>
                                        <button type="button" id="dropdownMenuButton" class="btn-dots" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </td>
                                </tr>
                                <?php } ?>
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
                            <div class="position-absolute top-0 end-0 mt-3 me-3 z-1">

                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="rounded-top-3 form p-4">
                                <h2 class="head-label">Add Subject</h2>
                                <div class="container form ">
                                <form id="facultyForm" action="../database/addfaculty.php" method="POST" class="row g-3 needs-validation" novalidate="">
                                    <h5>Personal Information</h5>
                                    <div class="col-md-5">
                                        <label class="form-label" for="firstname">Faculty ID</label>
                                        <input class="form-control" id="facultyid" name="facultyid" type="text" required>
                                        
                                    </div>
                                    <div class="row mt-2">
                                    
                                    <div class="col-md-5">
                                        <label class="form-label" for="firstname">First name</label>
                                        <input class="form-control" id="firstname" type="text" name="fname" required>
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label" for="lastname">Last name</label>
                                        <input class="form-control" id="lastname" type="text" name="lname" required>
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label" for="midleinit">MI</label>
                                        <input class="form-control" id="midleinit" type="text" name="mname" required="" />
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                    </div>

                                    <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label" for="contactnumber">Contact Number</label>
                                        <input class="form-control" id="contactnumber" name="contactno" type="tel" required />
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="birthdate">Birthdate</label>
                                        <input class="form-control" id="birthdate" name="bday" type="date" required />
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label" for="gender">Gender</label>
                                        <select class="form-select" id="gender" name="gender" required="">
                                        <option selected="" disabled="" >Choose...</option>
                                        <option value="Male">Male</option>
                                        <option value="Male">Female</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a Gender</div>
                                    </div>
                                    </div>

                                    <h5>Account Details</h5>
                                    <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label" for="email">Username</label>
                                        <input class="form-control" id="email" type="text" name="username" required>
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="password">Password</label>
                                        <input class="form-control" id="password" type="text" name="password" required>
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                    </div>

                                    <h5>Faculty Information</h5>
                                    <div class="row mt-2">
                                    <div class="col-6">
                                        <label class="form-label" for="position">Type</label>
                                        <select class="form-select" id="position" name="type" required="">
                                        <option selected="" disabled="" value="">Choose...</option>
                                        <option value="regular">Regular </option>
                                        <option value="contractual">Contractual</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a Gender</div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" for="startdate">Start Date</label>
                                        <input class="form-control" id="startdate" type="date" name="startdate" required />
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                    </div>

                                    <div class="row">
                                    <div class="col-6">
                                        <label class="form-label" for="department">Deparment</label>
                                        <select class="form-select" id="department" name="departmentid" required >
                                        <option selected="" disabled="" value="">Choose...</option>
                                        <?php foreach($department as $departments){ ?>
                                        <option value="<?php echo $departments['id'];?>"><?php echo $departments['name'];?></option>
                                        <?php } ?>
                                        </select>
                                        <div class="invalid-feedback">Please select Department</div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" for="teachinghours">Teaching Hours</label>
                                        <input class="form-control" id="teachinghours" type="number" name="teachinghours" required placeholder="Hours/Week" />
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" for="rank">Rank</label>
                                        <select class="form-select" id="position" name="rank" required="">
                                        <option selected  value="None">Choose...</option>
                                        <option value="phd">PhD </option>
                                        <option value="masters">Masters</option>
                                        </select>
                                    </div>
                                    </div>

                                
                                </div>
                            </div>
                    <div class="modal-footer d-flex justify-content-between">

                        <button type="button" class="cancel" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="confirm">Done</button>
                        </form>
                    </div>
            </div>
    </main>
</body>
<link rel="stylesheet" href="../css/main.css">
<link rel="stylesheet" href="../css/faculty.css">
<?php
        require_once('../include/js.php')
    ?>
<script src="../js/faculty.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
</html>

