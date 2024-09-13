<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if the necessary POST variables are set
            if (isset($_POST['year']) && isset($_POST['sem']) && isset($_POST['calendarid'])) {
                // Sanitize the input data
                $year = htmlspecialchars($_POST['year']);
                $_SESSION['year']=$year;
                $sem = htmlspecialchars($_POST['sem']);
                $_SESSION['sem']=$sem;

                $calendarid = htmlspecialchars($_POST['calendarid']);
                $_SESSION['calendarid']=$calendarid;
                // Print the calendar ID
                
            } else {
                $calendarid=$_SESSION['calendarid'];
                $sem=$_SESSION['sem'];
                $year=$_SESSION['year'];
                
            }
            if (isset($_SESSION['departmentid'])){
                $departmentid = htmlspecialchars($_SESSION['departmentid']);
            }else{
                $departmentid =1;
                $_SESSION['departmentid']=$departmentid;
            }
        } else {
            echo "Form not submitted.";
        }


    ?>

<body >
    <?php
        require_once('../include/nav.php');
        require_once('../database/datafetch.php');
    ?>
    <main>
        <div class="container mb-5">
            <div class="row mt-4">
                <div class="header-table">
                    <h5>
                        <button onclick="window.location.href='schedule.php'">
                            <i class="fa-solid fa-circle-arrow-left"></i>
                        </button>
                        <?php if(($_SESSION['sem'])==1){echo "1st Semester";}else{echo "2nd Semester";}?> <span><?php if(($_SESSION['departmentid'])==1){echo "BSCS";}else{echo "BSIT";}?></span> <span>SY-</span> <span><?php echo $_SESSION['year'];?></span>
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

                                foreach ($subjectschedule as $subjectschedules) {
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
                                    <td><?php echo $subjectschedules['yearlvl'].$subjectschedules['section'];?></td>
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
                        <!-- Calendar View -->
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
                                <!-- Time slots will be added here -->
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
    <script>
        function handleOptionChange() {
            var selectElement = document.getElementById('filter');
            var selectedValue = selectElement.value;

            // Redirect based on selected value
            if (selectedValue) {
                window.location.href = selectedValue;
            }
        }
    </script>
    <?php
        require_once('../include/js.php')
    ?>
</html>
