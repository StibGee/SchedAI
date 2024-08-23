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
                <div class="text d-flex align-items-center">
                    <h2> Hola !!! </h2> <span> Role</span>
                </div>
            </div>
            <div class="row d-flex align-items-center">
                <div class="col-5">
                    <h3>Curriculum Schedules</h3>
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
                <div class="col-3 ">
                    <button class="generate" data-bs-toggle="modal" data-bs-target="#formModal">New</button>



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
                        <tr onclick="window.location.href='/resources/admin/final-sched.php'">
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
                        <tr onclick="window.location.href='/resources/admin/final-sched.php'">
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
                <h5 class="modal-title" id="formModalLabel">Modal Title</h5>
                
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              
            </div>
            <div class="modal-body p-3">
                
                <div class="rounded-top-3 bg-body-tertiary p-4">
                    <h2 class="head-label">Generate New Schedule</h2>
                    <div class="container mt-4">
                        <form>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group department ">
                                        <h5>Select Department</h5>
                                        <select class="form-select form-select-sm " id="select-department">
                                            <option>Information Technology</option>
                                            <option>Computer Science</option>
                                        </select>
                                    </div>
                                    <div class="form-group academic-year">
                                        <h5>Select Academic Year</h5>
                                        <select class="form-select form-select-sm" id="select-academic-year"></select>
                                    </div>
                                    <div class="form-group room-availability">
                                        <h5>Add Room Availability</h5>
                                        <div class="rooms ml-3">
                                            <div class="g-3 row">
                                                <label class="form-label col-form-label col-form-label-sm col-lg-2">Lec</label>
                                                <div class="col">
                                                    <input placeholder="Input No. of Rooms" type="number" class="form-control form-control-sm" style="width: 200px;">
                                                </div>
                                            </div>
                                            <div class="g-3 row">
                                                <label class="form-label col-form-label col-form-label-sm col-lg-2">lab</label>
                                                <div class="col">
                                                    <input placeholder="Input No. of Rooms" type="number" class="form-control form-control-sm" style="width: 200px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group semester">
                                        <h5>Select Semester</h5>
                                        <select class="form-select form-select-sm" id="select-department">
                                            <option>First Semester</option>
                                            <option>Second Semester</option>
                                        </select>
                                    </div>
                                    <div class="form-group num-of-section">
                                        <h5>Student Sections</h5>
                                        <div class="g-3 row">
                                            <label class="form-label col-form-label col-form-label-sm col-lg-2">First Year</label>
                                            <div class="col">
                                                <input placeholder="Input No. of Sections" type="number" class="form-control form-control-sm" style="width: 200px;">
                                            </div>
                                        </div>
                                        <div class="g-3 row">
                                            <label class="form-label col-form-label col-form-label-sm col-lg-2">Second Year</label>
                                            <div class="col">
                                                <input placeholder="Input No. of Sections" type="number" class="form-control form-control-sm" style="width: 200px;">
                                            </div>
                                        </div>
                                        <div class="g-3 row">
                                            <label class="form-label col-form-label col-form-label-sm col-lg-2">Third Year</label>
                                            <div class="col">
                                                <input placeholder="Input No. of Sections" type="number" class="form-control form-control-sm" style="width: 200px;">
                                            </div>
                                        </div>
                                        <div class="g-3 row">
                                            <label class="form-label col-form-label col-form-label-sm col-lg-2 pr-5">Forth Year</label>
                                            <div class="col">
                                                <input placeholder="Input No. of Sections" type="number" class="form-control form-control-sm" style="width: 200px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
              
                    <button type="button" class="cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="confirm" onclick="window.location.href='generate-sched.php'">Done</button>
                </div>
            </div>
        </div>
        </div>
    </div>
    </main>
</body>

    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/sched.css">
    <script src="../js/main.js"></script>
    <?php
        require_once('../include/js.php')
    ?>

</html>
