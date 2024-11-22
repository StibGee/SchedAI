<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/setup.css">
    <script src="../js/schedule.js"></script>

<body >

    <?php
        require_once('../include/nav.php');
        require_once('../database/datafetch.php');
    ?>
    <main>
        <div class="container mb-1">
            <div class="row d-flex align-items-center">
                <div  class="col-4">
                    <h5>New Curriculum Plan</h5>
                </div>
            </div>
            <div class="container mt-5 ">
                <form action="allocate-sub1.php" method="POST">
                        <div class="academic-year col-4 ">
                            <div class="form-group academic-year">
                                <label for="">Select Curriculum</label>
                                <div class="col-6">
                                    <input type="text" name="academicyear" class="form-control form-control-sm" style="width: 120px;" value="<?php echo date('Y'); ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="department col-4 ">
                            <label for="">Select Department</label>
                            <select class="form-select form-select-sm mt-2" id="select-department" name="departmentid">
                                <option value="1">Computer Science</option>
                                <option value="2">Information</option>
                            </select>
                        </div>

                        <div class="semester col-4 ">
                            <label for="">Select Semester</label>
                            <select name="semester" class="form-select form-select-sm mt-2" id="select-department">
                                <option value="1">First Semester</option>
                                <option value="2">Second Semester</option>
                            </select>
                        </div>
                    </form>
            </div>
            <div class="btn col-5 mt-4 d-flex justify-content-between ">
                <button type="button" class="cancel" onclick="window.location.href='academic-plan.php'">Cancel</button>
                <button type="button" class="confirm" onclick="window.location.href='academic-plan.php'">Done</button>
            </div>
        </div>

    </main>
</body>


    <?php
        require_once('../include/js.php')
    ?>

</html>
