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
            <h3>Subjects</h3>
            <div class="row d-flex align-items-center">
                <div  class="col-4">
                    <div class="row tab">
                        <div class="col-6">
                            <a href="../admin/academic-plan.php" class="nav_links">
                                <span class="nav_acad">Academic Plan</span>
                            </a>
                        </div>
                        <div class="col-6 ">
                            <a href="../admin/curriculum-subjects.php" class="nav_links">
                                <span class="nav_sub">Subjects</span>
                            </a>

                        </div>
                    </div>
                </div>
                <div class="col-8">
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
                        <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="curriculum-sched mt-4">
                <table class="mb-0 table table-hover ">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Period</th>
                            <th>Department</th>
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
                                    <a href="subjects.php" class="action-link"><i class="fas fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
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
                        <form action="allocate-sub1.php" method="POST">
                            <div class="academic-year ">
                                <div class="form-group academic-year">
                                    <label for="">Select Curriculum</label>
                                    <div class="col-6">
                                        <input type="text" name="academicyear" class="form-control form-control-sm" style="width: 120px;" value="<?php echo date('Y'); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="department ">
                                <label for="">Select Department</label>
                                <select class="form-select form-select-sm mt-2" id="select-department" name="departmentid">
                                    <option value="1">Computer Science</option>
                                    <option value="2">Information</option>
                                </select>
                            </div>

                            <div class="semester">
                                <label for="">Select Semester</label>
                                <select name="semester" class="form-select form-select-sm mt-2" id="select-department">
                                    <option value="1">First Semester</option>
                                    <option value="2">Second Semester</option>
                                </select>
                            </div>
                        </form>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="cancel" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="confirm">Done</button>
                    </div>
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
