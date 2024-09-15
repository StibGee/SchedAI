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
            <h3>Curriculum Plan</h3>
            <div class="row d-flex align-items-center">
                    <div class="row  d-flex align-items-center justify-content-end">
                        <div class="department col-4">
                            <select class="form-select form-select-sm" id="select-department">
                                <option>Information Technology</option>
                                <option>Computer Science</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <select class="form-select form-select-sm" id="select-classtype">
                                <option>all</option>
                                <option>lec</option>
                                <option>lab</option>
                            </select>
                        </div>
                        <div class="col-3 d-flex align-items-center justify-content-start">
                            <button onclick="window.location.href='setup-acadplan.php'"> <img src="../img/icons/add-icon.png" alt=""></button>
                        </div>
                    </div>
            </div>

            <div class="curriculum-sched mt-4">
                <table class="mb-0 table table-hover ">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Period</th>
                            <th>department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($calendar AS $calendars){ ?>
                        <tr>
                            <th scope="row"><?php echo $calendars['name'];?></th>
                            <td><?php if ($calendars['sem']==1){ echo '1st semester';} else{ echo '2nd semester';}?></td>
                            <td></td>
                            <td>
                                <div class="actions">
                                    <a href="edit.php?id=123" class="action-link"><i class="fas fa-edit"></i></a>
                                    <a href="delete.php?id=123" class="action-link"><i class="fas fa-trash"></i></a>
                                    <a href="academicplan-view.php" class="action-link"><i class="fas fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
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
                                            <!--<h5>Department</h5>
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
                                            </div>-->
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
                                                                    <h5>Lec </h5>
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

                                                                        <option selected value="1.0">1.0</option>
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
    </main>
</body>

    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/academic-plan.css">
    <script src="../js/main.js"></script>
    <?php
        require_once('../include/js.php')
    ?>


</html>
