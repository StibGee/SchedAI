<!DOCTYPE html>
<html lang="en">

<?php
    require_once('../include/nav.php');
    require_once('../classes/db.php');
    require_once('../classes/curriculum.php');
    require_once('../classes/department.php');
    require_once('../classes/college.php');
    require_once('../classes/schedule.php');
    require_once('../classes/faculty.php');

    $collegeid=$_SESSION['collegeid'];
    
    $db = new Database();
    $pdo = $db->connect();

    $curriculum = new Curriculum($pdo);
    $faculty = new Faculty($pdo);
    $schedule = new Schedule($pdo);
    $college = new College($pdo);
    $department = new Department($pdo);
    $collegedepartment = $department->getcollegedepartment($collegeid);
    $initialcollegedepartment = $department->getinitialcollegedepartment($collegeid);
    
    $calendar = $curriculum->getallcurriculumsschedule();
    $calendardistinct = $curriculum->getdistinctcurriculumsschedule();
    $calendardistinctall = $curriculum->getdistinctcurriculumsscheduleall();
    
    $_SESSION['year'] = $_POST['year'] ?? $_SESSION['year'];
    $_SESSION['calendarid'] = $_POST['calendarid'] ?? $_SESSION['calendarid'];
    $_SESSION['sem'] = $_POST['sem'] ?? $_SESSION['sem'];
    
    if(isset($_POST['departmentid'])){
        $_SESSION['departmentid'] = $_POST['departmentid'];
        
        
    }elseif(isset($_SESSION['departmentid'])){
        $_SESSION['departmentid']=$_SESSION['departmentid'];
    }else {
        $_SESSION['departmentid'] = $initialcollegedepartment;
    }
    if ($_SESSION['departmentid']!=0){
        $departmentinfo=$department->getdepartmentinfo($_SESSION['departmentid']);
        $filteredschedules=$schedule->filteredschedule($_SESSION['calendarid'], $_SESSION['departmentid']);
    }else{
        $minornofacultycount=$schedule->minorfacultycountcollege($collegeid, $_SESSION['calendarid']);
        $collegeinfo=$college->getcollegeinfo($collegeid);
        
        $filteredschedules=$schedule->filteredschedulecollege($_SESSION['calendarid'], $_SESSION['collegeid']);
        $faculties=$faculty->collegefaculty($_SESSION['collegeid']);
    }
    if (isset($_GET['subject']) && $_GET['subject'] == 'nofaculty') {
        echo '<script>
                var myModal = new bootstrap.Modal(document.getElementById("nofacultysubject"));
                myModal.show();
              </script>';
    }
    $collegeminorsubjectsnofaculty=$schedule->minornofacultycollege($collegeid, $_SESSION['calendarid']);
?>
<body >
    <?php
        require_once('../include/nav.php');
        
    ?>
    <main>
        <div class="container mb-5">
            <div class="row mt-4">
                <div class="header-table">
                    <h5>
                        <button onclick="window.location.href='schedule.php'">
                            <i class="fa-solid fa-circle-arrow-left"></i>
                        </button>
                        <?php if(($_SESSION['sem'])==1){echo "1st Semester";}else{echo "2nd Semester";}?> <span><?php if ($_SESSION['departmentid']!=0){echo $departmentinfo['abbreviation']; }else{ echo $collegeinfo['abbreviation'];}?></span> <span>SY-</span> <span><?php echo $_SESSION['year'];?></span>
                    </h5>
                </div>
            </div>
            <div class="row d-flex justify-content-end align-items-center">
                <!--<div class="col-3">
                    <form class="mb-0" action="final-sched.php" method="POST">
                            <select class="form-select  form-select-sm " id="select-classtype" name="departmentid" onchange="this.form.submit()">
                            
                                    
                                    <option value="1">BSCS</option>
                                    <option value="2">IT</option>
                                    
                            
                                <option value="" selected>Choose a department</option>
                            </select>
                    </form>
                </div>-->
                
                <div class="col-1">
                    <select class="form-select  form-select-sm " id="filter" onchange="handleOptionChange()">
                        <option value="">Select an option</option>
                        <option value="final-sched-room.php">By Rooms</option>
                        <option value="final-sched-faculty.php">By Faculty</option>
                        <option value="final-sched-subject.php">By Subject</option>
                    </select>
                </div>
                <div class="col-1">
                    <select class="form-select  form-select-sm " id="select-classtype">
                        <option>all</option>
                        <option>lec</option>
                        <option>lab</option>
                    </select>
                </div>
                <div class="searchbar col-3 ">
                    <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                </div>
                <div class="col-2 d-flex justify-content-end">
                    <button class="btn btn-success" data-bs-toggle="modal" <?php if($minornofacultycount==0){echo 'data-bs-target="#formModal"';}else{echo 'data-bs-target="#nofacultysubject"';}?>>Generate</button>

                </div>

            </div>
            <div class="sched-container my-4">
                <div class="d-flex ">
                    <button id="viewToggleButton">
                        Toggle View
                    </button>
                </div>
                <div class="sched-table mt-3">
                    <div id="tabularView" class="mt-2">
                        <table class="table">
                            <thead>
                                <tr>  
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
                            <?php $seenSubjectCodes = [];

                                foreach ($filteredschedules as $subjectschedules) {
                                    if (!in_array($subjectschedules['subjectcode'], $seenSubjectCodes)) {
                                    
                                        $seenSubjectCodes[] = $subjectschedules['subjectcode'];
                                        $displaySubjectCode = $subjectschedules['subjectcode'];
                                    } else {
                                        $displaySubjectCode = '';
                                    }
                                ?>
                                <tr>
                                    <td><?php echo $displaySubjectCode;?></td>
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
                                    <td><?php echo $subjectschedules['roomname'];?></td>
                                    <td><?php echo $subjectschedules['facultylname'];?></td>
                                </tr>
                               <?php } ?>
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
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <form id="formModalForm" action="../processing/scheduleprocessing.php"  method="post">
                            
                            <div class="rounded-top-3 bg-body-tertiary p-2">
                                <h2 class="head-label">Generate Schedule for <?php if($_SESSION['departmentid']==0){echo $collegeinfo['abbreviation'];}else{echo $departmentinfo['abbreviation'];}?><?php if($_SESSION['sem']==1){echo ' '.$_SESSION['sem'].'st sem';}else{echo ' '.$_SESSION['sem'].'nd sem';}?><?php echo ' S.Y-'.$_SESSION['year'];?></h2>
                                <div class="container mt-4">
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
                                                <select class="form-select form-select-sm" id="select-classtype" name="departmentid" disabled>
                                                    <?php foreach ($collegedepartment as $collegedepartments){?>
                                                        <option <?php if ($_SESSION['departmentid']==$collegedepartments['id']){echo 'selected';}?> value="<?php echo $collegedepartments['id'];?>" ><?php echo $collegedepartments['name'];?></option>
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
                                                
                                                <?php foreach($collegedepartment AS $collegedepartments){ ?> 
                                                <h4><?php echo $collegedepartments['abbreviation'];?></h4> 
                                                <input type="number" name="departmentid[]" id="" value="<?php echo $collegedepartments['id'];?>" hidden>    
                                                
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
        <div class="modal fade" id="nofacultysubject" tabindex="-1" aria-labelledby="nofacultysubject" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="noFacultyModalLabel">Subjects Without Faculty</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="assignFacultyForm" method="POST" action="../processing/subjectprocessing.php">
                        <input type="text" name="action" value="addfacultysubject">
                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Subject Name</th>
                                        <th>Select Faculty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($collegeminorsubjectsnofaculty as $subject) { ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="subjectname[]" value="<?php echo $subject['commonname']; ?>">
                                                <?php echo $subject['commonname'].' '.$subject['departmentabbreviation']; ?>
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
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/generated-sched.css">
    <script src="../js/facultyloading.js"></script>
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

    <?php
        require_once('../include/js.php')
    ?>
</html>
