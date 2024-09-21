<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body >

    <?php
        require_once('../include/nav.php');
        require_once('../classes/db.php');
        require_once('../classes/curriculum.php');

        if(isset($_POST['departmentid'])){
            $_SESSION['academicplandepartmentid'] = $_POST['departmentid'];
            $academicplandepartmentid=$_SESSION['academicplandepartmentid'];
        }elseif(isset($_SESSION['academicplandepartmentid'])){
            $academicplandepartmentid=$_SESSION['academicplandepartmentid'];
        }else{
            $academicplandepartmentid=1;
            $_SESSION['academicplandepartmentid'] = $academicplandepartmentid;
        }
        $db = new Database();
        $pdo = $db->connect();

        $curriculum = new Curriculum($pdo);
        $calendar = $curriculum->getallcurriculums();
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
                                        <a href="edit_room.php?id=<?php echo $calendars['id']; ?>" class="btn btn-warning">Edit</a>
                                        <form action="../processing/curriculumprocessing.php" method="post" style="display:inline;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $calendars['id']; ?>">
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this curriculum?');">Delete</button>
                                        </form>
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
                        <form action="../processing/curriculumprocessing.php" method="POST">
                            <input type="text" value="add" name="action" hidden>
                            <div class="academic-year ">
                                <div class="form-group academic-year">
                                    <label for="">Enter Year</label>
                                    <div class="col-6">
                                        <span><input type="number" name="academicyear" id="startyear" class="form-control form-control-sm" style="width: 120px;">-<input type="number" name="" id="endyear" class="form-control form-control-sm" style="width: 120px;"></span>
                                        
                                    </div>
                                </div>
                            </div>
                            <!--<div class="department ">
                                <label for="">Select Department</label>
                                <select class="form-select form-select-sm mt-2" id="select-department" name="departmentid">
                                    <option value="1">Computer Science</option>
                                    <option value="2">Information</option>
                                </select>
                            </div>-->
                            <div class="col-6">
                                <span><input type="checkbox" name="curriculumplan" id="" value="yes"><label for="">New curriculum plan</label></span>
                                        
                            </div>
                            <div class="semester">
                                <label for="">Select Semester</label>
                                <select name="semester" class="form-select form-select-sm mt-2" id="select-department">
                                    <option value="1">First Semester</option>
                                    <option value="2">Second Semester</option>
                                </select>
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
    <script>
        const startYearInput = document.getElementById('startyear');
        const endYearInput = document.getElementById('endyear');

        startYearInput.addEventListener('input', function() {
            const startYear = parseInt(startYearInput.value);
            if (!isNaN(startYear)) {
                endYearInput.value = startYear + 1;
            }
        });
        endYearInput.addEventListener('input', function() {
            const endYear = parseInt(endYearInput.value);
            if (!isNaN(endYear)) {
                startYearInput.value = endYear - 1;
            }
        });
    </script>
    <script src="../js/main.js"></script>
    
    <?php
        require_once('../include/js.php')
    ?>
    


</html>
