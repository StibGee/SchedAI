<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
        $_SESSION['currentpage']='curriculum';
        if (!isset($_GET['curriculum'])){
            $_SESSION['loading']=1;
        }
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
            $departmentid = $_POST['departmentid'];
            $_SESSION['departmentidbasis']=$departmentid;
        }elseif($_SESSION['departmentid'] && $_SESSION['departmentid']!=0){
            $departmentid = $_SESSION['departmentid'];
            $_SESSION['departmentidbasis']=$departmentid;
        } else {
            $departmentid = $initialcollegedepartment;
            $_SESSION['departmentidbasis']=$departmentid;
        }

        $departmentinfo = $department->getdepartmentinfo($departmentid);
    ?>
    <main>
        <div class="container mb-1">
            <div class="row d-flex align-items-center">
                <div class="col-6">
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

                <div class="col-1 d-flex align-items-center justify-content-end">
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
                            <tr class="hover-row" onclick="submitcurriculumform('<?php echo htmlspecialchars($calendars['year']); ?>', '<?php echo htmlspecialchars($calendars['sem']); ?>', '<?php echo htmlspecialchars($calendars['id']); ?>', event)">
                                <th scope="row"><?php echo htmlspecialchars($displayyear); ?></th>
                                <td><?php echo htmlspecialchars($calendars['sem'] == 1 ? '1st Semester' : ($calendars['sem'] == 2 ? '2nd Semester' : ($calendars['sem'] == 3 ? '3rd Semester' : $calendars['sem'] . 'th'))); ?></td>
                                <td>
                                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#formeditcalendar<?php echo $calendars['id']; ?>" onclick="event.stopPropagation();" style="background: none; border: none; padding: 0;">
                                        <i class="fas fa-edit"></i> <!-- Edit icon -->
                                    </button>

                                    <form action="../processing/curriculumprocessing.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this curriculum?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $calendars['id']; ?>">
                                        <button type="submit" class="btn" onclick="event.stopPropagation();" style="background: none; border: none; padding: 0;">
                                            <i class="fas fa-trash-alt"></i> <!-- Delete icon -->
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!--Modal edit year-->
                            <div class="modal fade" id="formeditcalendar<?php echo $calendars['id']; ?>" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg mt-6" role="document">
                                    <div class="modal-content border-0">
                                        <div class="modal-header border-0">
                                            <h4 class="modal-title" id="formModalLabel">Edit Curriculum Year</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body px-5">
                                            <form action="../processing/curriculumprocessing.php" method="POST">
                                                <input type="text" value="editcalendar" name="action" hidden>
                                                <input type="hidden" value="0" name="schedule">
                                                <input type="hidden" value="<?php echo $calendars['id'];?>" name="calendarid">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="startyear">Enter Year</label>
                                                        <div class="input-group mt-2">
                                                            <input type="number" name="academicyear" id="startyear1<?php echo $calendars['id']; ?>" class="form-control form-control-sm" style="width: 120px;" value="<?php echo $calendars['year']; ?>">
                                                            <span class="input-group-text">-</span>
                                                            <input type="number" name="endyear" id="endyear1<?php echo $calendars['id']; ?>" class="form-control form-control-sm" style="width: 120px;"  value="<?php echo ($calendars['year']+1); ?>">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="select-semester">Select Semester</label>
                                                        <select name="semester" class="form-select form-select-sm mt-2" id="select-semester">
                                                            <option value="1" <?php if ($calendars['sem'] == 1){ echo 'selected';}?>>First Semester</option>
                                                            <option value="2" <?php if ($calendars['sem'] == 2){ echo 'selected';}?>>Second Semester</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer d-flex justify-content-between">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Done</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                <label for="startyear">Enter Academic Year</label>
                                <div class="input-group mt-2">
                                    <input type="number" name="academicyear" id="startyear" class="form-control form-control-sm" placeholder="Start Year">
                                    <span class="input-group-text">-</span>
                                    <input type="number" name="endyear" id="endyear" class="form-control form-control-sm" placeholder="End Year">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="select-semester">Select Semester</label>
                                <select name="semester" class="form-select form-select-sm mt-2" id="select-semester">
                                    <option value="1">First Semester</option>
                                    <option value="2">Second Semester</option>
                                </select>
                            </div>
                        </div>


                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Done</button>
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
        const startYearInput = document.getElementById('startyear');
        const endYearInput = document.getElementById('endyear');
        const startYear1Input = document.getElementById('startyear1');
        const endYear1Input = document.getElementById('endyear1');

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

        startYear1Input.addEventListener('input', function() {
            const startYear1 = parseInt(startYear1Input.value);
            if (!isNaN(startYear1)) {
                endYear1Input.value = startYear1 + 1;
            }
        });
        endYear1Input.addEventListener('input', function() {
            const endYear1 = parseInt(endYear1Input.value);
            if (!isNaN(endYear1)) {
                startYear1Input.value = endYear1 - 1;
            }
        });
    </script>
    <script>

        function updateYearFields(calendarId) {
            const startYearInput = document.getElementById('startyear1' + calendarId);
            const endYearInput = document.getElementById('endyear1' + calendarId);

            if (startYearInput && endYearInput) {
                startYearInput.addEventListener('input', function() {
                    const startYear = parseInt(startYearInput.value);
                    if (!isNaN(startYear)) {
                        endYearInput.value = startYear + 1;
                    }
                });

                // Listener for the end year input field
                endYearInput.addEventListener('input', function() {
                    const endYear = parseInt(endYearInput.value);
                    if (!isNaN(endYear)) {
                        startYearInput.value = endYear - 1;
                    }
                });
            }
        }

        function setupModalListener(calendarId) {
            const modalElement = document.getElementById('formeditcalendar' + calendarId);

            if (modalElement) {
                modalElement.addEventListener('show.bs.modal', function() {
                    updateYearFields(calendarId);
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            <?php foreach ($calendar as $cal) { ?>
                setupModalListener('<?php echo $cal['id']; ?>');
            <?php } ?>
        });
    </script>


    <script>
        function submitcurriculumform(year, sem, calendarid, event) {
            if (event) {
            event.stopPropagation();
            }
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
