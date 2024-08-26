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
                    <h5>New Academic Plan</h5>
                </div>

            </div>
            <div class="container mt-5 ">
                <div class="row ">
                    <div class="department col-4 ">
                        <label for="">Select Department</label>
                        <select class="form-select form-select-sm mt-2" id="select-department">
                            <option>Information Technology</option>
                            <option>Computer Science</option>
                        </select>
                    </div>
                    <div class="academic-year col-4 ">
                        <div class="form-group academic-year">
                            <label for="">Select Academic Year</label>
                            <select class="form-select form-select-sm mt-2" id="select-academic-year"></select>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="semester col-4 ">
                        <label for="">Select Semester</label>
                        <select class="form-select form-select-sm mt-2" id="select-department">
                            <option>First Semester</option>
                            <option>Second Semester</option>
                        </select>
                    </div>
                    <div class="academic-year col-4 ">
                        <label for="">Load Subjects</label>
                        <div class="load-sub d-flex justify-content-between align-items-center  p-1  mt-2">
                            <label for="" class="mx-3 ">Load subjects per year level</label>
                            <button type="button" class="view " onclick="window.location.href='allocate-sub.php'">Load</button>
                        </div>
                        <div class="d-flex justify-content-end">
                            <label for="">*important</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn col-8 mt-4 d-flex justify-content-between ">
                <button type="button" class="cancel" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="confirm" onclick="window.location.href='academic-plan.php'">Done</button>
            </div>


        </div>

    </main>
</body>

    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/setup.css">
    <script src="../js/schedule.js"></script>

    <?php
        require_once('../include/js.php')
    ?>

</html>
