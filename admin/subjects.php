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
            <div class="row d-flex align-items-center">
                <div  class="col-4">
                    <div class="row ">
                        <div class="col-6">
                            <a href="../admin/academic-plan.php" class="nav_links">
                                <span class="nav_acad">Academic Plan</span>
                            </a>
                        </div>
                        <div class="col-6 ">
                            <a href="../admin/subjects.php" class="nav_links">
                                <span class="nav_sub">Subjects</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mb-5">

                <div class="row  d-flex align-items-center mt-4">
                    <div class="header-table col-4">
                        <h3>Subjects</h3>
                    </div>
                    <div class="col-8">
                    <div class="row  d-flex align-items-center justify-content-end">
                    <div class="col-2">
                        <select class="form-select " id="select-year">
                            <option>All</option>
                            <option>CS1</option>
                            <option>CS2</option>
                            <option>CS3</option>
                            <option>CS4</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <select class="form-select  " id="select-subtype">
                            <option>all</option>
                            <option>Lab</option>
                            <option>Lec</option>
                        </select>
                    </div>

                        <div class="col-4">
                            <select class="form-select " id="select-department">
                                <option>Institute of Technology</option>
                                <option>Computer Science</option>
                            </select>
                        </div>

                        <div class="searchbar col-3">
                            <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                        </div>
                        <div class="col-1 add-faculty d-flex justify-content-end">
                            <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                        </div>
                    </div>
                </div>
                </div>

                <div class="sched-container mb-4 p-3">
                    <div class="sched-table ">
                            <table id="example" class="table">
                                <thead>
                                    <tr>
                                        <th>Subject ID</th>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th>Unit</th>
                                        <th>Department</th>
                                        <th>Focus</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody id="tabularTableBody">
                                <?php foreach ($subject as $subjects){ ?>
                                    <tr>

                                        <td><?php echo $subjects['id'];?></td>
                                        <td><?php echo $subjects['subjectcode'];?></td>
                                        <td><?php echo $subjects['subjectname'];?></td>
                                        <td><?php echo $subjects['type'];?></td>
                                        <td><?php echo $subjects['unit'];?></td>
                                        <td><?php echo $subjects['departmentname'];?></td>
                                        <td><?php echo $subjects['focus'];?></td>


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
                                    <?php }?>
                                </tbody>
                            </table>


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
                                        <form id="facultyForm" class="row g-3 mt-4 needs-validation" action="../database/addsubject.php" method="POST" novalidate="">
                                            <h5>Department</h5>
                                            <div class="row ">
                                                <div class="col-md-6">
                                                <label for="">Select Department</label>
                                                    <select class="form-select" id="department" name="departmentid" required="">
                                                        <option selected="" disabled="" value="">Choose...</option>
                                                        <?php foreach($department as $departments){ ?>
                                                        <option value="<?php echo $departments['id'];?>"><?php echo $departments['name'];?></option>
                                                        <?php } ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <h5>Subject Information</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="subcode">Subject Code</label>
                                                    <input class="form-control" id="subcode" type="text" name="subjectcode" required />
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="subname">Subject Name</label>
                                                    <input class="form-control" id="subname" type="text" name="subjectname" required />
                                                </div>
                                            </div>
                                            <h5>Subject Details</h5>
                                            <!--<div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="yearlvl">Year Level</label>
                                                    <select class="form-select" id="yearlvl" required="">
                                                        <option selected="" disabled="" value="">Choose...</option>
                                                        <option>1st year</option>
                                                        <option>2nd year</option>
                                                        <option>3rd year</option>
                                                        <option>4th year</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="offered">Offered</label>
                                                    <select class="form-select" id="offered" required="">
                                                        <option selected="" disabled="" value="">Choose...</option>
                                                        <option>1st semester</option>
                                                        <option>2nd semester</option>
                                                    </select>
                                                </div>
                                            </div>-->
                                            <div class="row ml-5 ">
                                                <div class="col-6">
                                                    <div class="form-check ">
                                                        <div class="row mt-3 ">
                                                                <div class="col-md-2">
                                                                    <label class="form-label ml-5" for="subtype">Type </label>
                                                                    <h5>Lec  </h5>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label class="form-label" for="unit">Unit</label>
                                                                    <select class="form-select" id="unit" required="" name="lecunit">
                                                                        <option selected="" disabled="" value="">Choose...</option>
                                                                        <option value="2.0">2.0</option>
                                                                        <option value="3.0">3.0</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label" for="subname">Subject hours</label>
                                                                    <input class="form-control" id="subname" type="number" required  />
                                                                </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                                <div class="col-md-2">
                                                                    <h5>Lab <input class="form-check-input " type="checkbox" id="checkbox-1" name="lab" data-bulk-select-row="data-bulk-select-row" /></h5>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <select class="form-select" id="unit" required="" name="labunit">
                                                                        <option selected="" disabled="" value="">Choose...</option>

                                                                        <option selected value="3.0">3.0</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input class="form-control" id="subname" type="number" required  />
                                                                </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-6 mt-3">
                                                    <div class="row">
                                                        <label class="form-label" for="subname">Program Focus</label>
                                                        <select class="form-select" id="department" required="" name="focus">
                                                            <option selected="" disabled="" value="">Choose...</option>
                                                            <option>Major</option>
                                                            <option>Minor</option>
                                                        </select>
                                                        <div class="row mt-3">
                                                            <label for="">Mark check if the Subject requires <span>Masters</span></label>
                                                            <h5 class="mt-3 "> <input class="form-check-input " type="checkbox" id="checkbox-1" name="masters" data-bulk-select-row="data-bulk-select-row" /> Required</h5>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">


                                                </div>
                                            </div>

                                    </div>
                                </div>
                    <div class="modal-footer d-flex justify-content-between">

                        <button type="button" class="cancel" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="confirm">Done</button>

                    </div>
                    </form>
                </div>
            </div>

        </div>

    </main>
</body>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/subjects.css">
    <script src="../js/main.js"></script>
    <?php
        require_once('../include/js.php')
    ?>
</html>



<script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
