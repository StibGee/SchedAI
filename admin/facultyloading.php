<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
        $_SESSION['currentpage']='landing';
        $_SESSION['loading']=1;
    ?>



    <?php

        require_once('../include/nav.php');
        require_once('../classes/db.php');
        require_once('../classes/curriculum.php');
        require_once('../classes/department.php');
        require_once('../classes/college.php');
        require_once('../classes/schedule.php');
        require_once('../classes/faculty.php');

        $collegeid=$_SESSION['collegeid'];
        $scheduling=False;
        $db = new Database();
        $pdo = $db->connect();


        $curriculum = new Curriculum($pdo);
        $faculty = new Faculty($pdo);
        $schedule = new Schedule($pdo);
        $college = new College($pdo);
        $department = new Department($pdo);
        $collegelatestyear=$schedule->findcollegelatestyear($_SESSION['collegeid']);
        $facultyinfo=$faculty->getfacultyinfo($_SESSION['id']);
        $filteredschedules=$schedule->filteredschedulesfaculty($_SESSION['id'], $collegelatestyear);
    ?>
<main>

                <div class="row">
                    <div class="col-8 text d-flex align-items-center" >
                        <h2>Faculty Loading</span>
                    </div>
                </div>
            <div class="row  mb-4 mx-2">

                <div class="searchbar d-flex justify-content-center">
                    <input type="search" class="form-control" placeholder="Search Faculty Schedule" aria-label="Search" data-last-active-input="">
                </div>
            </div>
            <div class="row">
                <div class=" col-3 my-2">
                    <div class="col-9 subload-title">
                        <h5>Faculty Information</h5>
                    </div>
                    <div class="faculty-info ">

                        <li>Name : <?php echo $facultyinfo['fname']." ".$facultyinfo['mname']." ".$facultyinfo['lname'];?></li>
                        <li>Rank : <?php echo $facultyinfo['type'];?></li>
                        <li>Contact : <?php echo $facultyinfo['contactno'];?></li>
                        <li>Email :</li>

                    </div>

                <audio id="audio-element" preload="auto" src="../audio/schedai.wav" muted></audio>

                <script>
                    const audio = document.getElementById('audio-element');

                    audio.addEventListener('canplaythrough', () => {
                        audio.play().then(() => {
                            audio.muted = false;
                        }).catch(error => {
                            console.log('Audio playback failed:', error);
                        });
                    });
                    audio.load();
                </script>
                <div class="row mt-4">
                    <div class=" subload-title">
                        <h5>Specialization</h5>
                    </div>
                </div>
                    <div id="Subject-load" data-list="{&quot;valueNames&quot;:[&quot;yr&sec&quot;,&quot;email&quot;,&quot;desc&quot;,&quot;subtype&quot;,&quot;Units&quot;}">
                        <div class="table-responsive">
                        <table class="table table-sm fs-9 mb-0">
                            <thead>
                            <tr>
                                <th data-sort="subcode">Subject</th>
                            </tr>
                            </thead>
                            <tbody class="list">
                            <tr>
                                <td class="align-middle desc">Web Development</td>
                            </tr>
                            </tbody>
                            <tbody class="list">
                            <tr>
                                <td class="align-middle desc">Web Development</td>
                            </tr>
                            </tbody>
                            <tbody class="list">
                            <tr>
                                <td class="align-middle desc">Web Development</td>
                            </tr>
                            </tbody>
                        </table>
                        </div>

                    </div>
                    </div>
                <div class="col-9">
                <div class="sched-container">
                    <div class="row mt-2 justify-content-between">
                        <div class="col-5">
                            <h5>Faculty Schedule <span>(role)</span></h5>
                            <p>Total No. Of Units Loaded : <span> #</span></p>
                        </div>
                        <div class="col-2">
                            <button id="viewToggleButton">
                                Toggle View
                            </button>
                        </div>
                    </div>
                    <div class="sched-table">
                        <div id="scheduleView">
                            <!-- Tabular View -->
                            <div id="tabularView" class="mt-2">
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
                            <!-- Calendar View -->
                            <table id="calendarView" class="table mt-2" style="display: none;">
                                <thead>

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

<script src="../js/facultyloading.js"></script>
<link rel="stylesheet" href="../css/main.css">
<link rel="stylesheet" href="../css/facultyloading.css">

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</html>
