<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body >

    <?php
        require_once('../include/nav.php');
        require_once('../database/datafetch.php');
        if(isset($_POST['departmentid'])){
            $_SESSION['academicplandepartmentid'] = $_POST['departmentid'];
        } else {
            $_SESSION['academicplandepartmentid'] = 1;
        }
        
    ?>
    <main>
        <div class="container mb-1">
            <div class="row d-flex align-items-center">
                <div class="col-5">
                    <h3><?php echo ($_SESSION['academicplandepartmentid'] == 1) ? "BSCS" : "BSIT"; ?> Curriculum Plan</h3>
                </div>
                <div class="col-3">
                    <form class="mb-0" action="academic-plan.php" method="POST">
                        <select class="form-select form-select-sm" id="select-classtype" name="departmentid" onchange="this.form.submit()">
                            <option value="1">BSCS</option>
                            <option value="2">IT</option>
                            <option value="" selected>Choose a department</option>
                        </select>
                    </form>
                </div>
                <div class="col-1">
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

            <div class="curriculum-sched mt-4">
                <form id="curriculum-form" class="mb-0" action="academicplan-view.php" method="POST">
                    <input type="hidden" name="academicplanyear" id="year-field">
                    <input type="hidden" name="academicplansem" id="sem-field">
                    <input type="hidden" name="academicplancalendarid" id="calendarid-field">
                </form>

                <table class="mb-0 table table-hover">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Semester</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $seenyear = [];
                        foreach ($calendar as $calendars) {
                            if (!in_array($calendars['name'], $seenyear)) {
                                $seenyear[] = $calendars['name'];
                                $displayyear = $calendars['name'];
                            } else {
                                $displayyear = '';
                            }
                        ?>
                            <tr onclick="submitcurriculumform('<?php echo htmlspecialchars($calendars['year']); ?>', '<?php echo htmlspecialchars($calendars['sem']); ?>', '<?php echo htmlspecialchars($calendars['id']); ?>')">
                                <th scope="row"><?php echo htmlspecialchars($displayyear); ?></th>
                                <td><?php echo htmlspecialchars($calendars['sem']); ?></td>
                                <td>
                                    <div class="actions">
                                        <i class="fas fa-edit"></i>
                                        <i class="fas fa-trash"></i>
                                        <i class="fas fa-eye"></i>
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
    <link rel="stylesheet" href="../css/academic-plan.css">
    <script src="../js/schedule.js"></script>
    <script>
        function submitcurriculumform(year, sem, calendarid) {
            document.getElementById('year-field').value = year;
            document.getElementById('sem-field').value = sem;
            document.getElementById('calendarid-field').value = calendarid;
            document.getElementById('curriculum-form').submit();
        }
        document.querySelector('.btn-close').addEventListener('click', function() {
        console.log('Modal is being closed');
    }); 
    </script>
    <script src="../js/main.js"></script>
    
    <?php
        require_once('../include/js.php')
    ?>
    


</html>
