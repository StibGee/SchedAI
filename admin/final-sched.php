<!DOCTYPE html>
<html lang="en">


<?php
        require_once('../include/js.php');
    ?>
<script src="../js/facultyloading.js"></script>
<?php
    ini_set('max_execution_time', 100000000000);

    require_once('../include/nav.php');
    require_once('../classes/db.php');
    require_once('../classes/curriculum.php');
    require_once('../classes/department.php');
    require_once('../classes/college.php');
    require_once('../classes/schedule.php');
    require_once('../classes/faculty.php');
    require_once('../classes/email.php');
    require_once('../classes/room.php');
    require_once('../classes/subject.php');

    $collegeid=$_SESSION['collegeid'];
    $scheduling=False;
    $db = new Database();
    $pdo = $db->connect();


    $curriculum = new Curriculum($pdo);
    $subject = new Subject($pdo);
    $room = new Room($pdo);
    $email = new Email($pdo);
    $faculty = new Faculty($pdo);
    $schedule = new Schedule($pdo);
    $college = new College($pdo);
    $department = new Department($pdo);
    $collegedepartment = $department->getcollegedepartment($collegeid);
    $initialcollegedepartment = $department->getinitialcollegedepartment($collegeid);

    $calendar = $curriculum->getallcurriculumsschedule();


    $_SESSION['year'] = $_POST['year'] ?? $_SESSION['year'];
    $_SESSION['calendarid'] = $_POST['calendarid'] ?? $_SESSION['calendarid'];
    $_SESSION['sem'] = $_POST['sem'] ?? $_SESSION['sem'];

    if(isset($_POST['departmentid'])){
        $departmentid = $_POST['departmentid'];
        
    }elseif(isset($_SESSION['departmentidbasis'])){
        $departmentid=$_SESSION['departmentidbasis'];
        $_SESSION['departmentidbasis']=$_SESSION['departmentidbasis'];
    }else {
        $departmentid=$_SESSION['departmentid'];


    }

    if ($departmentid!=0){
        $collegeid=0;
        $nofaculty=$faculty->facultynoprofiledepartment($departmentid);
        $calendardistinct = $curriculum->getdistinctcurriculumsschedulecollege($_SESSION['collegeid']);
        $departmentinfo=$department->getdepartmentinfo($departmentid);
        $filteredschedules=$schedule->filteredschedule($_SESSION['calendarid'], $departmentid);
        $minornofacultycount=$schedule->minorfacultycountdepartment($departmentid, $_SESSION['calendarid']);
        $minorsubjectsnofaculty=$schedule->minornofacultydepartment($departmentid, $_SESSION['calendarid']);
        $faculties=$faculty->departmentfaculty($departmentid);
        $facultywemail=$faculty->facultywemaildepartment($departmentid);
        $roomcount=$room->countroomsdepartment($departmentid);
        $countsubjecthours=$schedule->countsubjecthoursdepartment($departmentid, $_SESSION['calendarid']);
        $countfacultyworkinghours=$faculty->countfacultyteachinghoursdepartment($departmentid);
    }else{
        $countsubjecthours=$schedule->countsubjecthourscollege($_SESSION['collegeid'], $_SESSION['calendarid']);
        $nofaculty=$faculty->facultynoprofilecollege($_SESSION['collegeid']);
        $roomcount=$room->countroomscollege($_SESSION['collegeid']);
        $calendardistinct = $curriculum->getdistinctcurriculumsschedulecollege($_SESSION['collegeid']);
        $minorsubjectsnofaculty=$schedule->minornofacultycollege($collegeid, $_SESSION['calendarid']);
        $minornofacultycount=$schedule->minorfacultycountcollege($collegeid, $_SESSION['calendarid']);
        $collegeinfo=$college->getcollegeinfo($collegeid);
        $countfacultyworkinghours=$faculty->countfacultyteachinghourscollege($collegeid);
        $facultywemail=$faculty->facultywemailcollege($_SESSION['collegeid']);
        $filteredschedules=$schedule->filteredschedulecollege($_SESSION['calendarid'], $_SESSION['collegeid']);
        $faculties=$faculty->collegefaculty($_SESSION['collegeid']);
    }
    if (isset($_GET['subject']) && $_GET['subject'] == 'nofaculty') {
        echo '<script>
                var myModal = new bootstrap.Modal(document.getElementById("nofacultysubject"));
                myModal.show();
              </script>';
    }

?>
<link href="../css/style.css" rel="stylesheet">
<link rel="stylesheet" href="../css/main.css">
<link rel="stylesheet" href="../css/generated-sched.css">
<body>
<?php if(isset($_GET['scheduling']) && $_GET['scheduling']=='scheduled'){
    foreach($facultywemail as $facultywemails){
        $emailadd=$facultywemails['email'];
        $fullname=$facultywemails['fname'];
        $facultyid=$facultywemails['facultyid'];
        $calendarid=$_SESSION['calendarid'];
        //$emailfacultysched=$email->emailfacultyschedule($emailadd, $fullname, $facultyid, $calendarid);
    }
}
?>

<?php

if ($roomcount == 0) {
    echo "<script>
        alert('No rooms detected. Add a room first.');
        window.location.href = 'room.php';
    </script>";
    exit;
}
?>
<?php if (isset($_GET['facultylacking'])) { ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = new bootstrap.Modal(document.getElementById('facultylacking'));
            modal.show();
        });
    </script>
<?php } ?>

<div class="modal fade" id="facultylacking" tabindex="-1" aria-labelledby="facultylacking" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noFacultyModalLabel">Subjects Lacking Faculty</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignFacultyForm" method="POST" action="../processing/subjectprocessing.php">
                <input type="text" name="action" value="addfacultysubject" hidden>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Subject Name</th>
                                <th>Subject Type</th>
                                <th>Subject Department</th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $subjectidlacking = isset($_GET['facultylacking']) ? $_GET['facultylacking'] : null;
                            $subjectinfo = $subject->subjectinfocomplete($subjectidlacking); 
                            
                            if (!empty($subjectinfo)) {
                             
                                ?>
                                <tr>
                                    <td>
                                        <input type="hidden" name="subjectname[]" value="<?php echo $subjectinfo['commonname']; ?>">
                                        <?php echo $subjectinfo['commonname']; ?>
                                    </td>
                                    <td>
                                        <input type="hidden" name="subjecttype[]" value="<?php echo $subjectinfo['subjecttype']; ?>">
                                        <?php echo $subjectinfo['subjecttype']; ?>
                                    </td>
                                    <td>
                                        <input type="hidden" name="departmentid[]" value="<?php echo $subjectinfo['departmentid']; ?>">
                                        <?php echo $subjectinfo['departmentabbreviation']; ?>
                                    </td>
                                    <td>
                                        <select name="facultyid[]" class="form-select">
                                            <option selected disabled>Select Faculty</option>
                                            <?php
                                            foreach ($faculties as $faculty) { ?>
                                                <option value="<?php echo $faculty['facultyid']; ?>">
                                                    <?php echo $faculty['fname'].' '.$faculty['lname'].' - '.$faculty['abbreviation']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php 
                            } else { 
                                echo "<tr><td colspan='3'>No data found.</td></tr>";
                            } ?>
                        </tbody>

                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Done</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if(isset($_GET['scheduling']) && $_GET['scheduling']=='loading' ){?>
    <div class="progresspopupdiv">


        <div class="progresspopup">

            <div class="progress">
                <div id="progress-bar" class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                    <span id="progress-text">0%</span>
                </div>
            </div>
            <div id="outputstatus" class="outputstatus"></div>

        </div>
    </div>

    <script>
        document.body.classList.add('blur-background');
        window.addEventListener('beforeunload', function (e) {

            e.preventDefault();
            e.returnValue = '';
        });
    </script>

    <?php

    $deptid = ($departmentid);
    $colid = ($collegeid);
    $calid = ($_SESSION['calendarid']);
    $minor = 1;
    while (ob_get_level()) {
        ob_end_flush();
    }
    $command = escapeshellcmd("python .././finalmerge.py") . " " . escapeshellarg($deptid) . " " . escapeshellarg($colid) . " " . escapeshellarg($calid) . " " . escapeshellarg($minor);
    $handle = popen($command, 'r');


    if ($handle) {
        while (!feof($handle)) {
            $line = fgets($handle);
            if ($line !== false) {
                if (preg_match('/(\d+\.\d+%)\s*:\s*(.*)/', $line, $matches)) {
                    $percentage = $matches[1];
                    $message = $matches[2];
                    $percentage = str_replace('%', '', $percentage);

                    ?>
                    <script>


                        document.getElementById('outputstatus').innerText = "<?php echo $message; ?>\n";
                    </script>
                    <script>
                        var percentage = <?php echo json_encode($percentage); ?>;
                        document.getElementById('progress-bar').style.width = '<?php echo $percentage; ?>%';
                        document.getElementById('progress-bar').setAttribute('aria-valuenow', percentage);
                        document.getElementById('progress-bar').setAttribute('aria-valuenow', '<?php echo $percentage; ?>');
                        document.getElementById('progress-text').innerText = '<?php echo $percentage; ?>%';

                        if (percentage>= 100) {

                            window.location.href = './final-sched.php?scheduling=scheduled';

                        }
                    </script>
                    <?php
                } else {
                    ?>
                    <script>
                        //document.getElementById('outputstatus').innerText += <?php echo json_encode($line); ?>;
                    </script>
                    <?php
                }

                flush();
            }
        }
        pclose($handle);
    } else {
        echo "Unable to execute the Python script.";
    }
    ?>
    <?php } else { ?>
    <script>
        document.body.classList.remove('blur-background');
    </script>
    <?php } ?>


    <main>
    <?php
    
    ?>
    <?php if (0==1){ ?>
    <div class="modal" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modal-warnings">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body my-3">
                    <h2 class="modal-title d-flex justify-content-center mb-3" id="warningModalLabel">
                        <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i> Warning
                    </h2>
                    <p class="text-center">The total teaching hours don't match the hours allocated to the faculty. Please adjust this to avoid any issues when scheduling.</p>
                    <div class="row d-flex justify-content-center mt-5">
                        <div class="col-5">
                            Total Subject Hours: <?php if(isset($countsubjecthours)){echo $countsubjecthours;}?>
                        </div>
                        <div class="col-5">
                            Total Faculty Hours: <?php if(isset($countfacultyworkinghours)){echo $countfacultyworkinghours;}?>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-around mt-4">
                        <div class="col-4 ">
                            <button type="button" class="btn-wrng p-2 w-100" id="modifyHoursBtn" data-bs-toggle="modal" data-bs-target="#modifyHoursModal1">Modify Faculty Teaching Hours</button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn-wrng p-2 w-100" id="addFacultyBtn" onclick="window.location.href='faculty.php';">Add new Faculty</button>
                        </div>

                    </div>
                    <div class="modal-footer mt-2">

                    </div>
                </div>
            </div>
        </div>
    </div>
     
    <div class="modal" id="modifyHoursModal1" tabindex="-1" aria-labelledby="modifyHoursModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modal-warnings">
                <div class="modal-header">
                    <h5 class="modal-title" id="modifyHoursModalLabel">Modify Faculty Teaching Hours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
                        <div class="col-6">
                            <div class="col-5">
                                Total Subject Hours: <?php if(isset($countsubjecthours)){echo $countsubjecthours;}?>
                            </div>
                            <div class="col-5" class="facultyhourscount">
                                Total Faculty Hours: <span id="totalFacultyHours">0</span>
                            </div>
                        </div>
                       
                    </div>

                    <table class="table p-3 mt-3 table-bordered text-center align-middle" id="facultyTable">
                        <thead class="table-secondary">
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Department</th>
                                <th>Teaching Hours</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($faculties AS $faculty){ ?>
                            <tr>
                                <td class="lol"><?php echo $faculty['fname'];?></td>
                                <td class="lol"><?php echo $faculty['type'];?></td>
                                <td class="lol"><?php echo $faculty['abbreviation'];?></td>
                                <td class="lol"><input type="number" min="6" max="30" name="workinghours" value="<?php echo $faculty['teachinghours'];?>" oninput="updateTotalFacultyHours()"></td>
                                
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success">Save Changes</button>
                    </div>
            </div>
        </div>
    </div>
        <script>
            var myModal = new bootstrap.Modal(document.getElementById('warningModal'));
            myModal.show(); 
        </script>
    
    <?php } ?>
    <?php if ($nofaculty){?>
        
        <div class="modal fade" id="modifyHoursModal1" tabindex="-1" aria-labelledby="modifyHoursModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content" id="modal-warnings">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modifyHoursModalLabel">Incomplete Faculty Profiles</h5>
                        
                    </div>
                    <div class="modal-body">
        
                        <div class="row">
                            <div class="col-6">
                                <p class="text-start">Please complete profiling of the following faculty.</p>
                            </div>
                            
                        </div>
        
                        <!-- Faculty Table -->
                        <table class="table p-3 mt-3 table-bordered text-center align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($nofaculty as $nofacultys){?>
                            <tr>
                                <td><?php echo $nofacultys['fname'].' '.$nofacultys['lname'];?></td>
                                <td><?php echo $nofacultys['departmentname'];?></td>
                                <td>
                                    <form action="profiling.php" method="POST">
                                        <input type="hidden" name="facultyid" value="<?php echo $nofacultys['facultyid'];?>"> 
                                        <button type="submit" class="btn btn-warning btn-md">Edit Profile</button>
                                    </form>
                                </td>

                            </tr>
                            
                            <?php } ?>
                        </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        
                    </div>
                </div>
            </div>
        </div>
        <script>
            var myModal = new bootstrap.Modal(document.getElementById('modifyHoursModal1'));
            myModal.show(); 

        </script>
        <?php } ?>

        <div class="container mb-5">
            <div class="row mt-4">
                <div class="header-table">
                    <h5>
                        <button onclick="window.location.href='schedule.php'">
                            <i class="fa-solid fa-circle-arrow-left"></i>
                        </button>
                        <?php if(($_SESSION['sem'])==1){echo "1st Semester";}else{echo "2nd Semester";}?> <span><?php if ($_SESSION['departmentidbasis']!=0){echo $departmentinfo['abbreviation']; }else{ echo $collegeinfo['abbreviation'];}?></span> <span>SY-</span> <span><?php echo $_SESSION['year'];?></span>
                    </h5>
                </div>
            </div>
            <div class="row d-flex justify-content-end align-items-center">
                <!--<div class="col-2">
                        <select class="form-select  form-select-sm " id="filter" onchange="handleOptionChange()">
                            <option value="">Faculty Schedule</option>
                            <option >Student Schedule</option>
                        </select>
                </div>
                <div class="col-2">
                    <form class="mb-0" action="final-sched-room.php" method="POST">
                        <select class="form-select  form-select-sm " id="select-classtype" name="roomid" onchange="this.form.submit()">
                            <?php foreach ($collegeroom as $collegerooms):
                                //if ($collegerooms['departmentid']==$_SESSION['departmentid']){?>

                                <option value="<?php echo $collegerooms['roomid']; ?>"
                                    <?php
                                        if (isset($roomids) && $roomids == $collegerooms['roomid']) {

                                            $roomname = isset($collegerooms['roomname']) ? $collegerooms['roomname'] : '';
                                            echo 'selected';
                                        }
                                    ?>>
                                    <?php echo isset($collegerooms['roomname']) ? htmlspecialchars($collegerooms['roomname']) : ''; ?>
                                </option>





                            <?php /*}*/ endforeach; ?>
                            <option value="" selected>Select a Room</option>
                        </select>
                    </form>
                </div>-->


                <div class="col-1 d-flex justify-content-end">
                    <button class="btn btn-success" data-bs-toggle="modal" <?php if($minornofacultycount==0){echo 'data-bs-target="#formModal"';}else{echo 'data-bs-target="#nofacultysubject"';}?>>Generate</button>

                </div>
                <!--<div class="col-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modifyHoursModal1">
                        Open Warning Modal
                    </button>
                </div>-->
                <?php
                // Include the modal HTML
                include '../include/modal/warning-modals.php';
                ?>
            </div>
            <div class="sched-container my-4">
                <div class="d-flex ">
                    <a href="final-sched-room.php" id="viewToggleButton" class="btn">
                        Toggle View
                    </a>
                </div>
                <div class="sched-table mt-3">
                    <div id="tabularView" class="mt-2">
                        <table class="table" id="example">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Subject Code</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Unit</th>
                                    <th>Year and Section</th>
                                    <th>Time</th>
                                    <th>Day</th>
                                    <th>Room</th>
                                    <th>Lecturer</th>
                                </tr>
                            </thead>
                            <tbody id="tabularTableBody">
                            <?php $i=1;

                                foreach ($filteredschedules as $subjectschedules) {

                                ?>
                                <tr>
                                    <td><?php echo $i;?></td>
                                    <td><?php echo $subjectschedules['subjectcode'];?></td>
                                    <td><?php echo $subjectschedules['subjectname'];?></td>
                                    <td><?php echo $subjectschedules['subjecttype'];?></td>
                                    <td><?php echo $subjectschedules['subjectunit'];?></td>
                                    <td><?php echo $subjectschedules['abbreviation'].$subjectschedules['yearlvl'].$subjectschedules['section'];?></td>
                                    <td><?php
                                    if (!empty($subjectschedules['starttime']) && !empty($subjectschedules['endtime'])) {
                                        echo date("g:i A", strtotime($subjectschedules['starttime'])) . " - " . date("g:i A", strtotime($subjectschedules['endtime']));
                                    }
                                    ?>
                                    </td>
                                    <td><?php echo $subjectschedules['day'];?></td>
                                    <td><?php echo (isset($subjectschedules['roomname']) && !empty($subjectschedules['roomname'])) ? $subjectschedules['roomname'] : 'N/A'; ?></td>
                                    <td>
                                        <?php
                                        echo (isset($subjectschedules['facultyfname'], $subjectschedules['facultylname']) 
                                            && !empty($subjectschedules['facultyfname']) 
                                            && !empty($subjectschedules['facultylname'])) 
                                            ? $subjectschedules['facultyfname'] . ' ' . $subjectschedules['facultylname'] 
                                            : 'N/A';
                                        ?>
                                    </td>

                                </tr>
                               <?php $i+=1; } ?>
                            </tbody>
                        </table>
                    </div>

                        <table id="calendarView" class="table mt-2" style="display: none;">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Monday</th>
                                    <th>Tuesday</th>
                                    <th>Wednesday</th>
                                    <th>Thursday</th>
                                    <th>Friday</th>
                                    <th>Saturday</th>
                                </tr>
                            </thead>
                            <tbody id="scheduleTableBody">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Form -->
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mt-6" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header pb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body  ">
                        <form id="formModalForm" class="needs-validation" action="../processing/scheduleprocessing.php"  method="post" novalidate>


                            <input type="number" name='academicyear' value="<?php echo $_SESSION['year'];?>" hidden>
                            <div class="rounded-top-3 bg-body-tertiary p-2">
                                <h4 class="head-label">Generate Schedule for <?php if($_SESSION['departmentidbasis']==0){echo $collegeinfo['abbreviation'];}else{echo $departmentinfo['abbreviation'];}?><?php if($_SESSION['sem']==1){echo ' '.$_SESSION['sem'].'st sem';}else{echo ' '.$_SESSION['sem'].'nd sem';}?><?php echo ' S.Y-'.$_SESSION['year'];?></h4>
                                <div class="form-check d-flex justify-content-end">
                                    <input class="form-check-input" type="checkbox" id="generalSubjects" name="includegensub">
                                    <label class="form-check-label" for="generalSubjects">
                                        Include General Subjects
                                    </label>
                                </div>

                                <div class="container ">
                                    <div class="row" hidden>
                                        <div class="col-6">
                                            <div class="form-group academic-year">
                                                <h5>Select Academic Year</h5>
                                                <select name="academicyear" id="">
                                                    <?php
                                                        foreach ($calendardistinctall as $calendardistinctsall) {?>
                                                            <option <?php if($calendardistinctsall['year']==$_SESSION['year']){echo 'selected';}?> value="<?php echo $calendardistinctsall['year'];?>"><?php echo $calendardistinctsall['name'];?></option>

                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group department ">
                                                <h5>Department</h5>
                                                <select class="form-select form-select-sm" id="select-classtype" disabled>
                                                    <?php foreach ($collegedepartment as $collegedepartments){?>
                                                        <option <?php if ($departmentid==$collegedepartments['id']){echo 'selected';}?> value="<?php echo $collegedepartments['id'];?>" ><?php echo $collegedepartments['name'];?></option>
                                                    <?php } ?>

                                                    <option value="" >Choose a department</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" hidden>
                                        <div class="col-6">
                                            <div class="form-group semester">
                                                <h5>Select Semester</h5>
                                                <select class="form-select form-select-sm" name="semester" id="select-department">
                                                    <option <?php if($_SESSION['sem']==1){ echo 'selected';}?> value="1">First Semester</option>
                                                    <option <?php if($_SESSION['sem']==2){ echo 'selected';}?> value="2">Second Semester</option>
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
                                    <?php if ($departmentid!=0){ ?>
                                        <input type="hidden" name="collegeid" value="<?php echo $_SESSION['collegeid'];?>">
                                        <input type="hidden" name="action" value="adddepartment">
                                        <input type="number" name="departmentid2" id="" value="<?php echo $departmentid;?>" hidden>
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
                                                                            <input id="sectionInput" placeholder="Input No. of Sections" type="number" name="section<?php echo $i;?>[]" class="form-control form-control-sm" style="width: 200px;" min="1" max="26" required>
                                                                            <div class="invalid-feedback">
                                                                                Please enter a valid number of sections (minimum 1).
                                                                            </div>
                                                                        </td>
                                                                        
                                                                        <td style="border: none;">
                                                                            <select class="form-select form-select-sm m-0"  name="curriculum<?php echo $i;?>[]">
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

                                    <?php if($departmentid==0){ ?>
                                        <input type="hidden" name="action" value="addcollege">
                                        <input type="hidden" name="collegeid" value="<?php echo $_SESSION['collegeid'];?>">
                                        <div class="form-group num-of-section">
                                            <div class="row">

                                                <?php foreach($collegedepartment AS $collegedepartments){ ?>
                                                <h4><?php echo $collegedepartments['abbreviation'];?></h4>
                                                <input type="number" name="departmentid1[]" id="" value="<?php echo $collegedepartments['id'];?>" hidden>

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
                                                                        <input placeholder="Input No. of Sections" type="number" name="section<?php echo $i;?>[]" class="form-control form-control-sm" style="width: 200px;" min="1" max="26" required>
                                                                        <div class="invalid-feedback">
                                                                                Please enter a valid number of sections (minimum 1).
                                                                        </div>
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
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                                <button type="submit" class="confirm btn btn-primary">Done</button>
                                <input type="number" name="calendarid" id="" value="<?php echo $_SESSION['calendarid'];?>" hidden>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="nofacultysubject" tabindex="-1" aria-labelledby="nofacultysubject" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="noFacultyModalLabel">Subjects Without Faculty Expertise</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="assignFacultyForm" method="POST" action="../processing/subjectprocessing.php">
                        <input type="text" name="action" value="addfacultysubject" hidden>
                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Subject Name</th>
                                        <th>Subject Type</th>
                                        <th>Subject Department</th>
                                        <th>Select Faculty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($minorsubjectsnofaculty as $subject) { ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="subjectname[]" value="<?php echo $subject['commonname']; ?>">
                                                <?php echo $subject['commonname']; ?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="subjecttype[]" value="<?php echo $subject['subjecttype']; ?>">
                                                <?php echo $subject['subjecttype']; ?>
                                            </td>
                                            <td>
                                                <input type="hidden" name="departmentid[]" value="<?php echo $subject['departmentid']; ?>">
                                                <?php echo $subject['departmentabbreviation'];?>
                                            </td>
                                            <td>
                                                <select name="facultyid[]" class="form-select">
                                                    <option selected disabled>Select Faculty</option>
                                                    <?php
                                                    foreach ($faculties as $faculty) { ?>
                                                        <option value="<?php echo $faculty['facultyid']; ?>">
                                                            <?php echo $faculty['fname'].' '.$faculty['lname'].' - '.$faculty['abbreviation']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main>

</body>

    <script>
        function handleOptionChange() {
            var selectElement = document.getElementById('filter');
            var selectedValue = selectElement.value;

            if (selectedValue) {
                window.location.href = selectedValue;
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const subject = urlParams.get('subject');

            if (subject === 'nofaculty') {
                var myModal = new bootstrap.Modal(document.getElementById('nofacultysubject'));
                myModal.show();
            }
        });
    </script>

</html>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "lengthChange": false,
            "pageLength": -1     
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#facultyTable').DataTable({
            "lengthChange": false,
            "pageLength": -1     
        });
    });
</script>
<script>
let totalFacultyHours = 0;
let facultyHoursData = [];

function collectAllFacultyHours() {
    const workingHoursInputs = document.querySelectorAll('input[name="workinghours"]');
   
    facultyHoursData = [];
    totalFacultyHours = 0;

    workingHoursInputs.forEach(input => {
        let hours = parseFloat(input.value) || 0;

        
        if (hours < 1) {
            input.value = ""; 
            hours = 0; 
        } else if (hours >30) {
            input.value = ""; 
            hours = 0; 
        }

        facultyHoursData.push(hours);
        totalFacultyHours += hours; 
    });

    document.getElementById('totalFacultyHours').textContent = totalFacultyHours;
}

function updateTotalFacultyHours() {
    totalFacultyHours = facultyHoursData.reduce((sum, hours) => sum + hours, 0);
    document.getElementById('totalFacultyHours').textContent = totalFacultyHours;
}

document.addEventListener("DOMContentLoaded", function() {
    collectAllFacultyHours();
});

$(document).on('input', 'input[name="workinghours"]', function() {
    collectAllFacultyHours();
});


</script>
<script>
    (() => {
      'use strict';

      const forms = document.querySelectorAll('.needs-validation');
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    })();
  </script>
