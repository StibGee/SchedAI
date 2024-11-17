<!DOCTYPE html>
<html lang="en">
<?php
    require_once('../include/head.php');
    $_SESSION['currentpage']='schedule';
    if (!isset($_GET['curriculum'])){
        $_SESSION['loading']=1;
    }
?>

<body>
    <?php
        require_once('../include/nav.php');
        require_once('../classes/db.php');
        require_once('../classes/curriculum.php');
        require_once('../classes/department.php');
        require_once('../classes/college.php');


        $collegeid=$_SESSION['collegeid'];

        $db = new Database();
        $pdo = $db->connect();

        $curriculum = new Curriculum($pdo);
        $college = new College($pdo);
        $department = new Department($pdo);

        if ($_SESSION['scheduling']=='college'){
            $_SESSION['departmentid']=0;
        }else{
            $_SESSION['departmentid']=$_SESSION['departmentid'];
        }

        /*$calendardistinct = $curriculum->getdistinctcurriculumsschedule();
        $calendardistinctall = $curriculum->getdistinctcurriculumsscheduleall();*/


        if(isset($_POST['departmentid'])){
            $departmentid = $_POST['departmentid'];
            $_SESSION['departmentidbasis']=$departmentid;
        }elseif(isset($_SESSION['departmentid']) && $_SESSION['departmentid']!=0){
            $departmentid=$_SESSION['departmentid'];
            $_SESSION['departmentidbasis']=$_SESSION['departmentid'];
        }else{
            $departmentid=0;
            $_SESSION['departmentidbasis']=0;
        }

        if($_SESSION['departmentid']!=0){
            $collegedepartment = $department->getcollegedepartment($collegeid);
            $calendar = $curriculum->getcollegecalendar($_SESSION['collegeid']);
            $departmentinfo=$department->getdepartmentinfo($departmentid);
        }else{
            $calendar = $curriculum->getcollegecalendar($_SESSION['collegeid']);
            $collegedepartment = $department->getcollegedepartment($collegeid);
            $departmentinfo=$department->getdepartmentinfo($departmentid);
            $collegeinfo=$college->getcollegeinfo($collegeid);
            $initialcollegedepartment = $department->getinitialcollegedepartment($collegeid);
        }

    ?>
    <main>
        <div class="container mb-1">
            <div class="row d-flex align-items-center">
                <div class="col-6">
                    <h3><?php if ($departmentid!=0){echo $departmentinfo['abbreviation']; }else{ echo $collegeinfo['abbreviation'];}?> Academic Schedules</h3>
                </div>
                <div class="col-3 ">
                    <form class="mb-0" action="schedule.php" method="POST" >
                        <select class="form-select form-select-sm" id="select-classtype" name="departmentid" onchange="this.form.submit()" <?php if ($_POST['departmentidbasis']!=0 && $_SESSION['scheduling']=='college'){echo 'disabled';} ?>>
                            <?php foreach ($collegedepartment as $collegedepartments){?>
                                <option value="<?php echo $collegedepartments['id'];?>" <?php if ($departmentid==$collegedepartments['id']){echo 'selected';} ?>><?php echo $collegedepartments['name'];?></option>
                            <?php } ?>
                            <option value="0" <?php if ($departmentid==0){echo 'selected';} ?>>All Departments</option>
                            <option value="" >Choose a department</option>
                        </select>
                    </form>
                </div>

                <div class="col-2 d-flex align-items-center justify-content-start">
                        <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formaddcalendar"><img src="../img/icons/add-icon.png" alt=""></button>
                        </div>
                </div>
                <!--<div class="col-2 d-flex justify-content-end">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#formModal">Generate</button>

                </div>-->
            </div>
            <div class="curriculum-sched mt-4">
                <form id="schedule-form" class="mb-0" action="final-sched.php" method="POST">
                    <input type="hidden" name="year" id="year-field">
                    <input type="hidden" name="sem" id="sem-field">
                    <input type="hidden" name="calendarid" id="calendarid-field">
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
                            <tr class="hover-row" onclick="submitForm('<?php echo htmlspecialchars($calendars['year']); ?>', '<?php echo htmlspecialchars($calendars['sem']); ?>', '<?php echo htmlspecialchars($calendars['calendarid']); ?>', event)">
                                <th scope="row"><?php echo htmlspecialchars($displayyear); ?></th>
                                <td><?php echo htmlspecialchars($calendars['sem'] == 1 ? '1st Semester' : ($calendars['sem'] == 2 ? '2nd Semester' : ($calendars['sem'] == 3 ? '3rd Semester' : $calendars['sem'] . 'th'))); ?></td>

                                <td>

                                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#formeditcalendar<?php echo $calendars['id']; ?>" onclick="event.stopPropagation();" style="background: none; border: none; padding: 0;">
                                        <i class="fas fa-edit"></i>
                                    </button>


                                    <form action="../processing/curriculumprocessing.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this curriculum?');">
                                        <input type="hidden" name="action" value="deletecalendar">
                                        <input type="hidden" name="id" value="<?php echo $calendars['id']; ?>">
                                        <button type="submit" class="btn" onclick="event.stopPropagation();" style="background: none; border: none; padding: 0;">
                                            <i class="fas fa-trash-alt"></i> 
                                        </button>
                                    </form>

                                </td>
                            </tr>

                             <!--Modal edit year-->
                            <div class="modal fade" id="formeditcalendar<?php echo $calendars['id']; ?>" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg mt-6" role="document">
                                    <div class="modal-content border-0">
                                        <div class="modal-header border-0">
                                            <h4 class="modal-title" id="formModalLabel">Edit School Year</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body px-5">
                                            <form action="../processing/curriculumprocessing.php" method="POST">
                                                <input type="text" value="editcalendar" name="action" hidden>
                                                <input type="hidden" value="1" name="schedule">
                                                <input type="hidden" value="<?php echo $calendars['id'];?>" name="calendarid">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="startyear">Enter Year</label>
                                                        <div class="input-group mt-2">
                                                            <input type="number" name="academicyear" id="startyear1<?php echo $calendars['id']; ?>" class="form-control form-control-sm" style="width: 120px;"  value="<?php echo $calendars['year']; ?>">
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
        <!-- Modal Form add calendar -->
        <div class="modal fade" id="formaddcalendar" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mt-6" role="document">
            <div class="modal-content border-0">
                <div class="modal-header border-0">
                    <h4 class="modal-title" id="formModalLabel">Add New School Year</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5">
                    <form action="../processing/curriculumprocessing.php" method="POST">
                        <input type="text" value="addcalendar" name="action" hidden>
                        <input type="hidden" value="<?php echo $collegeid;?>" name="collegeid" >
                        <input type="hidden" value="0" name="curriculumplan" >
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="startyear">Enter Year</label>
                                <div class="input-group mt-2">
                                    <input type="number" name="academicyear" id="startyear" class="form-control form-control-sm" style="width: 120px;">
                                    <span class="input-group-text">-</span>
                                    <input type="number" name="endyear" id="endyear" class="form-control form-control-sm" style="width: 120px;">
                                </div>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="select-semester">Select Semester</label>
                                <div class="input-group mt-2">

                                    <select name="semester" class="form-select form-select-sm mt-2" id="select-semester">
                                        <option value="1">First Semester</option>
                                        <option value="2">Second Semester</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">

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

        <div class="modal fade" id="formeditcalendar" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mt-6" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header border-0">
                        <h4 class="modal-title" id="formModalLabel">Edit School Year</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-5">
                        <form action="../processing/curriculumprocessing.php" method="POST">
                            <input type="text" value="editcalendar" name="action" hidden>
                            <input type="hidden" value="<?php echo $collegeid;?>" name="collegeid">
                            <input type="hidden" value="0" name="curriculumplan">
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
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">Done</button>
                            </div>
                        </form>
                    </div>
                </div>
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
                        <form id="formModalForm" action="../processing/scheduleprocessing.php"  method="post">

                            <div class="rounded-top-3 bg-body-tertiary p-2">
                                <h2 class="head-label">Generate New Schedule</h2>
                                <div class="container mt-4">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group academic-year">
                                                <h5>Select Academic Year</h5>
                                                <select name="academicyear" id="">
                                                    <?php
                                                        foreach ($calendardistinctall as $calendardistinctsall) {?>
                                                            <option value="<?php echo $calendardistinctsall['year'];?>"><?php echo $calendardistinctsall['name'];?></option>

                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group department ">
                                                <h5>Department</h5>
                                                <select class="form-select form-select-sm" id="select-classtype" name="departmentid" disabled>
                                                    <?php foreach ($collegedepartment as $collegedepartments){?>
                                                        <option <?php if ($_SESSION['departmentid']==$collegedepartments['id']){echo 'selected';}?> value="<?php echo $collegedepartments['id'];?>" ><?php echo $collegedepartments['name'];?></option>
                                                    <?php } ?>

                                                    <option value="" >Choose a department</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group semester">
                                                <h5>Select Semester</h5>
                                                <select class="form-select form-select-sm" name="semester" id="select-department">
                                                    <option value="1">First Semester</option>
                                                    <option value="2">Second Semester</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6 mt-4">
                                            <label for="">General Subjects Schedule</label>
                                            <div class="load-sub d-flex justify-content-between align-items-center p-1 mt-2">
                                                <label for="" class="mx-3">Set up the provided schedule </label>
                                                <button onclick="window.location.href='general-sub.php'">add</button>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <label for="">*important</label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($_SESSION['departmentid']!=0){ ?>
                                        <input type="hidden" name="action" value="adddepartment">
                                        <div class="form-group num-of-section">
                                            <div class="row">
                                                <h5>Student Sections</h5>

                                                <table class="table mx-2">
                                                    <thead>
                                                        <tr>
                                                            <th style="border: none;">Year</th>
                                                            <th style="border: none;">Number of Sections</th>
                                                            <th style="border: none;">Select Curriculum</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                                <?php for ($i=1; $i<=$departmentinfo['yearlvl']; $i++){ ?>
                                                                    <tr>
                                                                        <td style="border: none;">Year Level <?php echo $i;?></td>
                                                                        <td style="border: none;">
                                                                            <input placeholder="Input No. of Sections" type="number" name="section<?php echo $i;?>" class="form-control form-control-sm" style="width: 200px;">
                                                                        </td>
                                                                        <td style="border: none;">
                                                                            <select class="form-select form-select-sm m-0" name="curriculum1">
                                                                                <?php
                                                                                foreach ($calendardistinct as $calendardistincts) {?>
                                                                                <option value="<?php echo $calendardistincts['year'];?>"><?php echo $calendardistincts['name'];?></option>

                                                                                <?php
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if($_SESSION['departmentid']==0){ ?>
                                        <input type="hidden" name="action" value="addcollege">
                                        <div class="form-group num-of-section">
                                            <div class="row">
                                                <h5>Student Sections</h5>
                                                <?php foreach($collegedepartment AS $collegedepartments){
                                                echo $collegedepartments['abbreviation'];?>
                                                <input type="number" name="departmentid[]" id="" value="<?php echo $collegedepartments['id'];?>">

                                                    <table class="table mx-2">
                                                        <thead>
                                                            <tr>
                                                                <th style="border: none;">Year</th>
                                                                <th style="border: none;">Number of Sections</th>
                                                                <th style="border: none;">Select Curriculum</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $departmentinfo2=$department->getdepartmentinfo($collegedepartments['id']);
                                                            for ($i=1; $i<=$departmentinfo2['yearlvl']; $i++){ ?>
                                                                <tr>
                                                                    <td style="border: none;">Year Level <?php echo $i;?></td>
                                                                    <td style="border: none;">
                                                                        <input placeholder="Input No. of Sections" type="number" name="section<?php echo $i;?>[]" class="form-control form-control-sm" style="width: 200px;">
                                                                    </td>
                                                                    <td style="border: none;">
                                                                        <select class="form-select form-select-sm m-0" name="curriculum<?php echo $i;?>[]">
                                                                            <?php
                                                                            foreach ($calendardistinct as $calendardistincts) {?>
                                                                            <option value="<?php echo $calendardistincts['year'];?>"><?php echo $calendardistincts['name'];?></option>

                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>




                                                        </tbody>
                                                    </table>
                                               <?php } ?>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="cancel" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="confirm">Done</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Form for general sub-->
        <div class="modal fade" id="generalsub" tabindex="-1" aria-labelledby="generalsubModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mt-6" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header mb-0">
                        <h4>Set Up General Subject Schedule</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <form >
                            <div class="rounded-top-3 bg-body-tertiary p-2">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group academic-year">
                                            <h5>Select Academic Year</h5>
                                            <input type="text" name="academicyear" class="form-control form-control-sm" style="width: 120px;" value="<?php echo date('Y'); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group department ">
                                            <h5>Select Department</h5>
                                            <select name="departmentid" class="form-select form-select-sm" id="select-department">
                                                <option value="1">Computer Science</option>
                                                <option value="2">Information Technology</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group semester">
                                            <h5>Select Semester</h5>
                                            <select class="form-select form-select-sm" name="semester" id="select-department">
                                                <option value="1">First Semester</option>
                                                <option value="2">Second Semester</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6 mt-4">
                                        <label for="">Genral Subjects Schedule</label>
                                        <div class="load-sub d-flex justify-content-between align-items-center p-1 mt-2">
                                            <label for="" class="mx-3">Set up the provided schedule </label>
                                            <button type="button" class="button-modal" data-bs-toggle="modal" data-bs-target="#generalsub">add</button>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <label for="">*important</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="cancel" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="confirm">Done</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

<link rel="stylesheet" href="../css/main.css">
<link rel="stylesheet" href="../css/sched.css">
<script src="../js/schedule.js"></script>
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
    function submitForm(year, sem, calendarid, event) {
        if (event) {
            event.stopPropagation();
        }
        document.getElementById('year-field').value = year;
        document.getElementById('sem-field').value = sem;
        document.getElementById('calendarid-field').value = calendarid;
        document.getElementById('schedule-form').submit();
    }
    document.querySelector('.btn-close').addEventListener('click', function() {
});
</script>
<?php
    require_once('../include/js.php');
?>

</html>

