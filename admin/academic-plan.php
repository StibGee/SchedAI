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
        require_once('../classes/department.php');
        
        
        $collegeid=$_SESSION['collegeid'];
        
        $db = new Database();
        $pdo = $db->connect();

        $curriculum = new Curriculum($pdo);
        $department = new Department($pdo);
        $collegedepartment = $department->getcollegedepartment($collegeid);
        $initialcollegedepartment = $department->getinitialcollegedepartment($collegeid);

        
        $calendar = $curriculum->getcollegecurriculum($collegeid);
        $collegeid=$_SESSION['collegeid'];
        
        if(isset($_POST['departmentid'])){
            $_SESSION['departmentid'] = $_POST['departmentid'];
        }elseif(isset($_SESSION['departmentid'])){
            $_SESSION['departmentid']=$_SESSION['departmentid'];
        } else {
            $_SESSION['departmentid'] = $initialcollegedepartment;
        }
        echo $_SESSION['departmentid'];
        $departmentinfo = $department->getdepartmentinfo($_SESSION['departmentid']);
    ?>
    <main>
        <div class="container mb-1">
            <div class="row d-flex align-items-center">
                <div class="col-5">
                    <h3><?php echo $departmentinfo['abbreviation']; ?> Curriculum Plan</h3>
                </div>
                <div class="col-3">
                    <form class="mb-0" action="academic-plan.php" method="POST">
                        <select class="form-select form-select-sm" id="select-classtype" name="departmentid" onchange="this.form.submit()">
                            <?php foreach ($collegedepartment as $collegedepartments){ ?> 
                                <option value="<?php echo $collegedepartments['id'];?>"><?php echo $collegedepartments['name'];?></option>
                            <?php } ?>
                           
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
                <div class="modal-header border-0">
                    <h4 class="modal-title" id="formModalLabel">Add New Curriculum</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5">
                    <form action="../processing/curriculumprocessing.php" method="POST">
                        <input type="text" value="add" name="action" hidden>
                        <input type="hidden" value="<?php echo $collegeid;?>" name="collegeid" >
                        <input type="hidden" value="1" name="curriculumplan" >
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="startyear">Enter Year</label>
                                <div class="input-group mt-2">
                                    <input type="number" name="academicyear" id="startyear" class="form-control form-control-sm" style="width: 120px;">
                                    <span class="input-group-text">-</span>
                                    <input type="number" name="endyear" id="endyear" class="form-control form-control-sm" style="width: 120px;">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="select-department">Select Department</label>
                                    <select class="form-select form-select-sm mt-2" id="select-department" name="departmentid">
                                        <?php foreach ($collegedepartment as $collegedepartments){ ?> 
                                        <option value="<?php echo $collegedepartments['id'];?>"><?php echo $collegedepartments['name'];?></option>
                                  
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="select-semester">Select Semester</label>
                                    <select name="semester" class="form-select form-select-sm mt-2" id="select-semester">
                                        <option value="1">First Semester</option>
                                        <option value="2">Second Semester</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Done</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/academic-plan.css">
    <script src="../js/main.js"></script>
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
