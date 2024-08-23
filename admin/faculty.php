<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body >

    <?php
        require_once('../include/nav.php');
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
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Id Number</th>
                                    <th>Department</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Contact No.</th>
                                    <th>Gender</th>
                                    <th>Start Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tabularTableBody">
                                <tr>
                                    <td>qb001</td>
                                    <td>Computer Science</td>
                                    <td>Rouel Sebastian Quilantang</td>
                                    <td>Cloutchase</td>
                                    <td>09*******</td>
                                    <td>male</td>
                                    <td>june 17, 1987</td>
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
                                <form id="facultyForm" class="row g-3 needs-validation" novalidate="">
                                    <h5>Personal Information</h5>
                                    <div class="row mt-2">
                                    <div class="col-md-5">
                                        <label class="form-label" for="firstname">First name</label>
                                        <input class="form-control" id="firstname" type="text" required="" />
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label" for="lastname">Last name</label>
                                        <input class="form-control" id="lastname" type="text" required="" />
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label" for="midleinit">MI</label>
                                        <input class="form-control" id="midleinit" type="text" required="" />
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                    </div>

                                    <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label" for="contactnumber">Contact Number</label>
                                        <input class="form-control" id="contactnumber" type="tel" required />
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="birthdate">Birthdate</label>
                                        <input class="form-control" id="birthdate" type="date" required />
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label" for="gender">Gender</label>
                                        <select class="form-select" id="gender" required="">
                                        <option selected="" disabled="" value="">Choose...</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a Gender</div>
                                    </div>
                                    </div>

                                    <h5>Account Details</h5>
                                    <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label" for="email">Email</label>
                                        <input class="form-control" id="email" type="email" required />
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="password">Password</label>
                                        <input class="form-control" id="password" type="password" required />
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                    </div>

                                    <h5>Faculty Information</h5>
                                    <div class="row mt-2">
                                    <div class="col-6">
                                        <label class="form-label" for="position">Rank/Position</label>
                                        <select class="form-select" id="position" required="">
                                        <option selected="" disabled="" value="">Choose...</option>
                                        <option>Dean</option>
                                        <option>Visiting Lecturer</option>
                                        </select>
                                        <div class="invalid-feedback">Please select a Gender</div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" for="startdate">Start Date</label>
                                        <input class="form-control" id="startdate" type="date" required />
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>
                                    </div>

                                    <div class="row">
                                    <div class="col-6">
                                        <label class="form-label" for="department">Deparment</label>
                                        <select class="form-select" id="department" required="">
                                        <option selected="" disabled="" value="">Choose...</option>
                                        <option>Information Technology</option>
                                        <option>Computer Science</option>
                                        </select>
                                        <div class="invalid-feedback">Please select Department</div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" for="teachinghours">Teaching Hours</label>
                                        <input class="form-control" id="teachinghours" type="number" required placeholder="Hours/Week" />
                                    </div>
                                    </div>

                                </form>
                                </div>
                            </div>
                    <div class="modal-footer d-flex justify-content-between">

                        <button type="button" class="cancel" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="button" class="confirm" onclick="window.location.href='faculty.php'">Done</button>
                    </div>
            </div>
    </main>
</body>
<link rel="stylesheet" href="../css/main.css">
<link rel="stylesheet" href="../css/faculty.css">
<script src="../js/faculty.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</html>

