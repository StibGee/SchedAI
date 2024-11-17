<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>


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
        $curriculum = new Curriculum($pdo);

        $collegelatestyear=$schedule->findcollegelatestyear($_SESSION['collegeid']);

        $filteredschedules=$schedule->filteredschedulesfaculty($_SESSION['id'], $collegelatestyear);
        $calendarinfo=$curriculum->calendarinfo($collegelatestyear);
        $facultyinfo=$faculty->getfacultyinfo($_SESSION['id']);

        require_once('../database/datafetch.php');

        if (isset($_POST['facultyid'])) {
            $facultyid = $_POST['facultyid'];
            foreach ($faculty as $facultys){
                if ($facultys['facultyid']==$facultyid ){
                    $_SESSION['facultyname']=$facultys['facultylname'];
                }
            }
        } else {
            $facultyid = $_SESSION['id'];
            $_SESSION['facultyname']='Default';
        }

        $days = ['M', 'T', 'W', 'Th', 'F', 'S'];
        $intervals = [];
        for ($i = 7; $i <= 18; $i++) {
            $intervals[] = sprintf("%02d:00-%02d:30", $i, $i);
            $intervals[] = sprintf("%02d:30-%02d:00", $i, $i + 1);
        }


        function generateColor($id) {
            $hue = ($id * 137.508) % 360;
            return "hsl($hue, 70%, 80%)";
        }

        $sql = "SELECT
                    day,
                    TIME_FORMAT(subjectschedule.timestart, '%H:%i') AS timestart,
                    TIME_FORMAT(subjectschedule.timeend, '%H:%i') AS timeend,
                    subjectschedule.id as subjectidno,
                    subject.subjectcode as subjectname,
                    subjectschedule.yearlvl as yearlvl,
                    section,
                    faculty.lname as facultyname,
                    room.name as roomname,
                    department.abbreviation as departmentname
                FROM
                    subjectschedule

                    JOIN subject ON subject.id = subjectschedule.subjectid
                    JOIN department ON subjectschedule.departmentid = department.id
                    JOIN faculty ON faculty.id = subjectschedule.facultyid
                    JOIN room ON room.id = subjectschedule.roomid
                WHERE .subjectschedule.facultyid = $facultyid AND subjectschedule.calendarid=$collegelatestyear";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $schedule = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $daysArray = preg_split('/(?<=[a-zA-Z])(?=[A-Z])/', $row['day']);
            $starttime = $row['timestart'];
            $endtime = $row['timeend'];
            $subjectid = (int)$row['subjectidno'];
            $subjectname = htmlspecialchars($row['subjectname']);
            $yearlvl = htmlspecialchars($row['yearlvl']);
            $section = htmlspecialchars($row['section']);
            $roomname = htmlspecialchars($row['roomname']);
            $departmentname = htmlspecialchars($row['departmentname']);


            $subjectLabel = "$subjectname $departmentname $yearlvl$section ($roomname)";
            $color = generateColor($subjectid);


            $startMinutes = (int)date('H', strtotime($starttime)) * 60 + (int)date('i', strtotime($starttime));
            $endMinutes = (int)date('H', strtotime($endtime)) * 60 + (int)date('i', strtotime($endtime));


            $intervalCount = ($endMinutes - $startMinutes) / 30;


            $middleIndex = (int)floor($intervalCount / 2);
            $startIndex = $intervalCount % 2 === 0 ? $middleIndex - 1 : $middleIndex;

            for ($i = 0; $i < $intervalCount; $i++) {
                $currentStart = date('H:i', strtotime($starttime) + ($i * 30 * 60));
                $currentEnd = date('H:i', strtotime($currentStart) + (30 * 60));
                $interval = sprintf("%s-%s", $currentStart, $currentEnd);

                foreach ($daysArray as $day) {
                    if (!isset($schedule[$day][$interval])) {
                        $schedule[$day][$interval] = [];
                    }
                    $isTop = ($i == 0);
                    $isBottom = ($i == $intervalCount - 1);
                    $isMiddle = ($i != 0 && $i != $intervalCount - 1);

                    if ($i >= $startIndex && $i <= $startIndex + ($intervalCount % 2 ? 0 : 0)) {
                        $schedule[$day][$interval][] = [
                            'color' => $color,
                            'subjectname' => $subjectLabel,
                            'is_center' => true,
                            'is_top' => $isTop,
                            'is_middle' => $isMiddle,
                            'is_bottom' => $isBottom,
                        ];
                    } else {
                        $schedule[$day][$interval][] = [
                            'color' => $color,
                            'subjectname' => '',
                            'is_center' => false,
                            'is_top' => $isTop,
                            'is_middle' => $isMiddle,
                            'is_bottom' => $isBottom,
                        ];
                    }
                }
            }
        }



    ?>
<main>

    <div class="container containersched p-4">

        <div class="row d-flex justify-content-end align-items-center">
            <div class="col-12 col-md-6">
                <h5>
                    Your Schedule for<span> S.Y: </span><span><?php echo $calendarinfo['name'].' ';?></span><?php echo ($calendarinfo['sem'] == 1) ? "1st sem" : (($calendarinfo['sem'] == 2) ? "2nd sem" : "Unknown semester");?><span></span>
                </h5>
            </div>
            <div class="col-12 col-md-3">
            <div class="row">
                <div class="col-6">
                    <select class="form-select form-select-sm" id="select-roomtype">
                        <option value="">All Room</option>
                        <?php foreach($collegeroom as $collegerooms) { ?>
                            <option value="<?php echo $collegerooms['name']; ?>"><?php echo $collegerooms['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-6">
                    <select class="form-select form-select-sm" id="select-classtype">
                        <option value="">All Type</option>
                        <option value="Lec">Lec</option>
                        <option value="Lab">Lab</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <input type="search" class="form-control" id="customSearch" placeholder="Search..." aria-label="Search" data-last-active-input="">
        </div>
    </div>
    <div class="sched-container my-4">
    <div class="d-flex justify-content-between align-items-center">
        <a href="dashboard.php" id="viewToggleButton" class="btn">
            Toggle View
        </a>
    </div>

    <div class="sched-table mt-3" id="pageContent">
        <div id="tabularView" class="mt-2">
            <div class="table-responsive">
                <table class="table tablesched">
                    <thead>
                        <tr>
                            <th>Interval</th>
                            <?php foreach ($days as $day): ?>
                                <th><?php echo htmlspecialchars($day); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody id="tabularTableBody">
                        <?php foreach ($intervals as $interval): ?>
                            <?php
                                list($start, $end) = explode('-', $interval);
                                $start = date('g:i A', strtotime($start));
                                $end = date('g:i A', strtotime($end));
                                $printinterval = "$start-$end";
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($printinterval); ?></td>
                                <?php foreach ($days as $day): ?>
                                    <td
                                        <?php
                                            if (isset($schedule[$day][$interval])) {
                                                $subjectData = $schedule[$day][$interval];
                                                $colors = array_column($subjectData, 'color');
                                                $subjectNames = array_column($subjectData, 'subjectname');
                                                $isCenters = array_column($subjectData, 'is_center');
                                                $color = $colors[0];
                                                echo 'style="background-color: ' . htmlspecialchars($color) . ';"';
                                                foreach ($schedule[$day][$interval] as $data) {
                                                    if ($data['is_middle'] == 1) {
                                                        echo ' class="occupiedmiddle"';
                                                    } elseif ($data['is_top'] == 1) {
                                                        echo ' class="occupiedfirst"';
                                                    } else {
                                                        echo ' class="occupiedlast"';
                                                    }
                                                }
                                            }
                                        ?>
                                    >
                                        <?php
                                            if (isset($schedule[$day][$interval])) {
                                                foreach ($schedule[$day][$interval] as $data) {
                                                    if ($data['is_center']) {
                                                        echo htmlspecialchars($data['subjectname']);
                                                    }
                                                }
                                            }
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
    </div>
    <div id="rotateMessage" style="display: none;">
    Please rotate your device to view full schedule.
</div>

</main>
<style>
    #pageContent {
    transition: filter 0.3s ease;
}

#pageContent.blurred {
    filter: blur(5px);
}

#rotateMessage {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 20px;
    border-radius: 5px;
    text-align: center;
    z-index: 1000;
    font-size: 1.2em;
    max-width: 80%;
}
.sched-table {
    transition: all 0.3s ease;
}

.fullscreen-table {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: white;
    overflow-y: auto;
    z-index: 999;
    margin-top: 0 !important;
}

.tablesched {
    width: 100%;
    height: auto;
}

@media (max-width: 768px) {
    .tablesched {
        font-size: 0.7rem;
    }
}



</style>
<link rel="stylesheet" href="../css/generated-sched-room.css">
<link rel="stylesheet" href="../css/faculty-css/dashboard.css">


    <script src="../js/facultyloading.js"></script>
    <?php
        require_once('../include/js.php')
    ?>
    <script>
    function checkOrientation() {
    const rotateMessage = document.getElementById('rotateMessage');
    const pageContent = document.getElementById('pageContent');

    if (window.innerHeight > window.innerWidth) {

        rotateMessage.style.display = 'block';
        pageContent.classList.add('blurred');
    } else {

        rotateMessage.style.display = 'none';
        pageContent.classList.remove('blurred');
    }
}

checkOrientation();

window.addEventListener('resize', checkOrientation);
</script>
<script>
    function toggleFullScreenTable() {
    const tableContainer = document.getElementById('pageContent');

    if (window.innerWidth <= 768) {
        if (window.innerWidth > window.innerHeight) {
            tableContainer.classList.add('fullscreen-table');
        } else {
            tableContainer.classList.remove('fullscreen-table');
        }
    } else {
        tableContainer.classList.remove('fullscreen-table');
    }
}

toggleFullScreenTable();

window.addEventListener('resize', toggleFullScreenTable);



</script>
</html>


