<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body>

    <?php

        require_once('../include/user-mainnav.php');
        require_once('../database/datafetch.php');
       
        require_once('../classes/db.php');
        require_once('../classes/curriculum.php');
        require_once('../classes/department.php');
        require_once('../classes/college.php');
        require_once('../classes/schedule.php');
        require_once('../classes/faculty.php');

        
        

        $db = new Database();
        $pdo = $db->connect();

        $curriculum = new Curriculum($pdo);
        $faculty = new Faculty($pdo);
        $schedule = new Schedule($pdo);
        $college = new College($pdo);
        $department = new Department($pdo);   

        $collegelatestyear=$schedule->findcollegelatestyear($_SESSION['collegeid'], $_SESSION['id']);
     
        $filteredschedules=$schedule->filteredschedulesfaculty($_SESSION['id'], $collegelatestyear);
        $calendarinfo=$curriculum->calendarinfo($collegelatestyear);
    ?>
<main>
    <div class="container ">
        <div class="row">
            <div class="text d-flex align-items-center" >
                <h2> Hola !!! </h2> <span> Role</span>
            </div>
        </div>
        <div class="row mt-4">
                <div class="header-table">
                    <h4>
                        
                        Your Schedule for<span> S.Y: </span><span><?php echo $calendarinfo['name'].' ';?></span><?php echo ($calendarinfo['sem'] == 1) ? "1st sem" : (($calendarinfo['sem'] == 2) ? "2nd sem" : "Unknown semester");?><span></span>
                    </h4>
                </div>
            </div>
            <div class="row d-flex justify-content-end align-items-center">
                <div class="col-1">
                    <select class="form-select  form-select-sm " id="select-year&sec">
                        <option>all</option>
                        <option>CS4A</option>
                        <option>CS4B</option>
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


            </div>
            <div class="sched-container my-4">
                <div class="d-flex ">
                    <a href="dashboard-toggle.php" id="viewToggleButton" class="btn">
                        Toggle View
                    </a>
                </div>
                <div class="sched-table mt-3">
                    <div id="tabularViews" class="mt-2">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Subject Code</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Unit</th>
                                    <th>Year & Sec</th>
                                    <th>Time</th>
                                    <th>Day</th>
                                    <th>Room</th>
                                    
                                </tr>
                            </thead>
                            <tbody id="tabularTableBody">
                            <?php $seenSubjectCodes = [];
                                $number=1;
                                foreach ($filteredschedules as $subjectschedules) {
                                    if (!in_array($subjectschedules['subjectcode'], $seenSubjectCodes)) {
                                    
                                        $seenSubjectCodes[] = $subjectschedules['subjectcode'];
                                        $displaySubjectCode = $subjectschedules['subjectcode'];
                                    } else {
                                        $displaySubjectCode = '';
                                    }
                                ?>
                                <tr>
                                    <td><?php echo $number;?></td>
                                    <td><?php echo $displaySubjectCode;?></td>
                                    <td><?php echo $subjectschedules['subjectname'];?></td>
                                    <td><?php echo $subjectschedules['subjecttype'];?></td>
                                    <td><?php echo $subjectschedules['subjectunit'];?></td>
                                    <td><?php echo $subjectschedules['abbreviation'].' '.$subjectschedules['yearlvl'].$subjectschedules['section'];?></td>
                                    <td><?php
                                    if (!empty($subjectschedules['starttime']) && !empty($subjectschedules['endtime'])) {
                                        echo date("g:i A", strtotime($subjectschedules['starttime'])) . " - " . date("g:i A", strtotime($subjectschedules['endtime']));
                                    }
                                    ?>
                                    </td>
                                    <td><?php echo $subjectschedules['day'];?></td>
                                    <td><?php echo $subjectschedules['roomname'];?></td>
                                
                                </tr>
                                
                               <?php $number+=1; } ?>
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
</main>
</body>
<link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/generated-sched.css">
    <script src="../js/facultyloading.js"></script>
    <?php
        require_once('../include/js.php')
    ?>
</html>
