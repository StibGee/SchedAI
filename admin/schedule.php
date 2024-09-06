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
            <div class="row d-flex align-items-center">
                <div class="col-5">
                    <h3>Academic Schedules</h3>
                </div>
                <div class="department col-3">
                    <select class="form-select form-select-sm" id="select-department">
                        <option>Information Technology</option>
                        <option>Computer Science</option>
                    </select>
                </div>
                <div class="col-1">
                    <select class="form-select form-select-sm" id="select-classtype">
                        <option>all</option>
                        <option>lec</option>
                        <option>lab</option>
                    </select>
                </div>
                <div class="col-2 d-flex justify-content-end">
                    <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                </div>
            </div>
            <div class="curriculum-sched mt-4">
                <table class="mb-0 table table-hover">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Period</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr onclick="window.location.href='../admin/final-sched.php'">
                            <th scope="row">2021-2022</th>
                            <td>Second Semester</td>
                            <td>Computer Science</td>
                            <td>
                                <div class="actions">
                                    <i class="fas fa-edit"></i>
                                    <i class="fas fa-trash"></i>
                                    <i class="fas fa-eye"></i>
                                </div>
                            </td>
                        </tr>
                        <tr onclick="window.location.href='../admin/final-sched.php'">
                            <th scope="row">2021-2022</th>
                            <td>First Semester</td>
                            <td>Computer Science</td>
                            <td>
                                <div class="actions">
                                    <i class="fas fa-edit"></i>
                                    <i class="fas fa-trash"></i>
                                    <i class="fas fa-eye"></i>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Form -->
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mt-6" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                    <form action="../database/generatesched.php" method="post">
                        <div class="rounded-top-3 bg-body-tertiary p-2">

                            <h2 class="head-label">Generate New Schedule</h2>
                            <div class="container mt-4">
                                    <div class="form-group academic-year">
                                        <h5>Select Academic Year</h5>
                                        <input type="text" name="academicyear" class="form-control form-control-sm" style="width: 120px;" value="<?php echo date('Y'); ?>" readonly>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group department ">
                                                <h5>Select Department</h5>
                                                <select name="departmentid" class="form-select form-select-sm " id="select-department">
                                                    <option value="1">Computer Science</option>
                                                    <option value="2">Information Technology</option>

                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-6">
                                            <div class="form-group semester">
                                                <h5>Select Semester</h5>
                                                <select class="form-select form-select-sm" name="semester" id="select-department">
                                                    <option value="1">First Semester</option>
                                                    <option value="2">Second Semester</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group num-of-section">
                                        <div class="row">
                                            <h5>Student Sections</h5>
                                            <table class="table mx-2" >
                                                <thead>
                                                    <tr>
                                                        <th style="border: none;">Year</th>
                                                        <th style="border: none;">Number of Sections</th>
                                                        <th style="border: none;">Select Curriculum</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="border: none;">First Year</td>
                                                        <td style="border: none;">
                                                            <input placeholder="Input No. of Sections" type="number" name="section1" class="form-control form-control-sm" style="width: 200px;">
                                                        </td>
                                                        <td style="border: none;">
                                                            <select class="form-select form-select-sm m-0">
                                                                <option>2018-2021</option>
                                                                <option>2022-2025</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border: none;">Second Year</td>
                                                        <td style="border: none;">
                                                            <input placeholder="Input No. of Sections" type="number" name="section2" class="form-control form-control-sm" style="width: 200px;">
                                                        </td>
                                                        <td style="border: none;">
                                                            <select class="form-select form-select-sm m-0">
                                                                <option>2018-2021</option>
                                                                <option>2022-2025</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border: none;">Third Year</td>
                                                        <td style="border: none;">
                                                            <input placeholder="Input No. of Sections" type="number" name="section3" class="form-control form-control-sm" style="width: 200px;">
                                                        </td>
                                                        <td style="border: none;">
                                                            <select class="form-select form-select-sm m-0">
                                                                <option>2018-2021</option>
                                                                <option>2022-2025</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border: none;">Fourth Year</td>
                                                        <td style="border: none;">
                                                            <input placeholder="Input No. of Sections" type="number" name="section4" class="form-control form-control-sm" style="width: 200px;">
                                                        </td>
                                                        <td style="border: none;">
                                                            <select class="form-select form-select-sm m-0">
                                                                <option>2018-2021</option>
                                                                <option>2022-2025</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">

                            <button type="button" class="cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="confirm">Done</button>
                        </div>
                    </div>

                </div>
                </div>
            </div>
            </form>
        </div>
    </main>
</body>

    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/sched.css">
    <script src="../js/schedule.js"></script>
    <?php
        require_once('../include/js.php')
    ?>

</html>
