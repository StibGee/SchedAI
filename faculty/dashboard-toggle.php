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


            $subjectLabel = "$subjectname $yearlvl$section ($roomname)";
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
<main class="col-sm-10 pb-5" id="main">
    <!-- NavBar -->
    <nav class="navbar sticky-top navbar-expand-lg border-bottom bg-body d-flex">
    <div class="container-fluid ">
        <div class="button col-4 col-sm-4">
            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseWidthExample" aria-expanded="true" aria-controls="collapseWidthExample"
                style="margin-right: 10px; padding: 0px 5px 0px 5px;" id="sidebartoggle" onclick="changeclass()">
                <i class="bi bi-arrows-expand-vertical"></i>
            </button>
            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"
                style="margin-right: 10px; padding: 2px 6px 2px 6px;" id="sidebarshow">
                <i class="bi bi-arrow-bar-right"></i>
            </button>
        </div>

    <!-- Cambair Tema -->
        <div class="user col-8 col-sm-6 d-flex justify-content-end">

            <!-- Mobile Image -->
            <div class="mobile-image-container col-5">
                <img src="../img/logo/Sched-logo1.png" alt="Mobile Image" class="mobile-image">
            </div>
            <div class="dropdown col-6 d-flex justify-content-end">
                <div class="header-text ">
                    <h5><?php echo $_SESSION['fname'];?></h5>
                </div>
                <img src="../img/icons/user.png" width="30" height="30" alt="" class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                    <li class="ms-3">
                        <form action="../processing/facultyprocessing.php" method="POST" style="display: inline;">
                            <input type="text" name="action" value="logout" hidden>
                            <button type="submit" name="logout" class="dropdown-item" style="background: none; border: none; padding: 0; margin: 0;">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    </nav>
    <div class="container containersched p-4">
    <div class="row py-2 ">
            <span class="text-head">Schedule</span>
        </div>
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

    <div class="sched-table mt-3">
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
</main>
</body>
<link rel="stylesheet" href="../css/generated-sched-room.css">
<link rel="stylesheet" href="../css/faulty-css/dashboard.css">

    <script src="../js/facultyloading.js"></script>
    <?php
        require_once('../include/js.php')
    ?>
</html>
<script>
    function changeclass() {
      $("#main").toggleClass('col-sm-10 col-sm-12');
    }
  </script>
<script src="color-modes.js"></script>

