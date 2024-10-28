
<?php
    require_once('../include/head.php');
   
    require_once('../include/nav.php');

    require_once('../classes/room.php');
    require_once('../classes/college.php');
    require_once('../classes/department.php');
    require_once('../classes/schedule.php');
    $db = new Database();
    $pdo = $db->connect();
    
    $room = new Room($pdo);
    $college = new College($pdo);
    $department = new Department($pdo);
    $schedule = new Schedule($pdo);
    $collegeid=$_SESSION['collegeid'];
    $inititialcollegeroom = $room->getinitialcollegeroom($collegeid);
    
    if ($_SESSION['departmentid']==0){
        $collegeroom=$room->getcollegerooms($collegeid);
    }else{
        $collegeroom=$room->getdepartmentrooms($_SESSION['departmentid']);
    }
    $calendarid=$_SESSION['calendarid'];
    $collegeinfo=$college->getcollegeinfo($collegeid);
    

    if (isset($_POST['roomid'])) {
        $roomids = $_POST['roomid'];
        foreach ($collegeroom as $collegerooms){
            if ($collegerooms['roomid']== $roomids){
                $roomname=$collegerooms['roomname'];
               
            }
        }
    } else {
        $roomids = $inititialcollegeroom;
        foreach ($collegeroom as $collegerooms){
            if ($collegerooms['roomid']== $roomids){
                $roomname=$collegerooms['roomname'];
            }
        }
        
    }
    if ($_SESSION['departmentid']!=0){
        $departmentinfo=$department->getdepartmentinfo($_SESSION['departmentid']);
        $filteredschedules=$schedule->filteredschedule($_SESSION['calendarid'], $_SESSION['departmentid']);
        $minornofacultycount=$schedule->minorfacultycountdepartment($_SESSION['departmentid'], $_SESSION['calendarid']);
        $minorsubjectsnofaculty=$schedule->minornofacultydepartment($_SESSION['departmentid'], $_SESSION['calendarid']);
    }else{
        $minorsubjectsnofaculty=$schedule->minornofacultycollege($collegeid, $_SESSION['calendarid']);
        $minornofacultycount=$schedule->minorfacultycountcollege($collegeid, $_SESSION['calendarid']);
        $collegeinfo=$college->getcollegeinfo($collegeid);
        
        $filteredschedules=$schedule->filteredschedulecollege($_SESSION['calendarid'], $_SESSION['collegeid']);
      
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
    if ($_SESSION['departmentid']==0){
    $sql = "SELECT 
                day, 
                TIME_FORMAT(timestart, '%H:%i') AS timestart, 
                TIME_FORMAT(timeend, '%H:%i') AS timeend, 
                subjectschedule.id as subjectidno,
                subject.subjectcode as subjectname,
                subjectschedule.yearlvl as yearlvl,
                section,
                faculty.fname as facultyname,
                subject.type as subjecttype,
                department.abbreviation as departmentname
            FROM 
                subjectschedule 
                JOIN subject ON subject.id = subjectschedule.subjectid
                JOIN faculty ON faculty.id = subjectschedule.facultyid
                JOIN department ON subjectschedule.departmentid = department.id
            WHERE roomid = $roomids AND department.collegeid=$collegeid AND subjectschedule.calendarid=$calendarid";
    }else{
        $departmentid=$_SESSION['departmentid'];
        $sql = "SELECT 
                day, 
                TIME_FORMAT(timestart, '%H:%i') AS timestart, 
                TIME_FORMAT(timeend, '%H:%i') AS timeend, 
                subjectschedule.id as subjectidno,
                subject.subjectcode as subjectname,
                subjectschedule.yearlvl as yearlvl,
                section,
                faculty.fname as facultyname,
                subject.type as subjecttype,
                department.abbreviation as departmentname
            FROM 
                subjectschedule 
                JOIN subject ON subject.id = subjectschedule.subjectid
                JOIN faculty ON faculty.id = subjectschedule.facultyid
                JOIN department ON subjectschedule.departmentid = department.id
            WHERE roomid = $roomids  AND subjectschedule.departmentid=$departmentid AND subjectschedule.calendarid=$calendarid";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $schedule = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $daysArray = preg_split('/(?<=[a-zA-Z])(?=[A-Z])/', $row['day']);
        $starttime = $row['timestart'];
        $endtime = $row['timeend'];

        $subjectid = (int)$row['subjectidno']; 
        $subjectname = htmlspecialchars($row['subjectname']);
        $departmentname = htmlspecialchars($row['departmentname']);
        $subjecttype = htmlspecialchars($row['subjecttype']);
        $yearlvl = htmlspecialchars($row['yearlvl']);
        $section = htmlspecialchars($row['section']);
        $facultyname = htmlspecialchars($row['facultyname']);
        
        
        $subjectLabel = "$subjectname $subjecttype $departmentname $yearlvl$section ($facultyname)";
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
                        'is_bottom' => $isBottom 
                    ];
                } else {
                    $schedule[$day][$interval][] = [
                        'color' => $color,
                        'subjectname' => '',
                        'is_center' => false,
                        'is_top' => $isTop,
                        'is_middle' => $isMiddle,
                        'is_bottom' => $isBottom  
                    ];
                }
            }
        }
        
    }


?>
<!DOCTYPE html>
<body >

    <?php
        require_once('../include/nav.php');
        
    ?>
    <main>
        <div class="container containersched mb-5">
            <div class="row">
                <div class="text d-flex align-items-center ">
                    <h2> Hola !!! </h2> <span> Role</span>
                </div>
            </div>
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
                <!--<div class="col-1">
                        <select class="form-select  form-select-sm " id="filter" onchange="handleOptionChange()">
                            <option value="">Select an option</option>
                            <option value="final-sched-room.php">By Rooms</option>
                            <option value="final-sched-faculty.php">By Faculty</option>
                            <option value="final-sched-subject.php">By Subject</option>
                        </select>
                </div>-->
                <div class="col-1">
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
                            <option value="" selected>Choose a room</option>
                        </select>
                    </form>
                </div>
                
                <div class="searchbar col-3 ">
                    <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                </div>


            </div>
            <div class="sched-container my-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h3><?php echo $roomname;?></h3>
                    <a href="final-sched.php" id="viewToggleButton" class="btn">
                        Toggle View
                    </a>
                </div>

                <div class="sched-table mt-3">
                    <div id="tabularView" class="mt-2">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Interval</th>
                                    <?php foreach ($days as $day): ?>
                                        <th><?php echo htmlspecialchars($day); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody tbody id="tabularTableBody">
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
                                                    //$subjectNames = array_column($subjectData, 'subjectname');
                                                    //$isCenters = array_column($subjectData, 'is_center');
                                                    $color = $colors[0];
                                                    echo 'style="background-color: ' . htmlspecialchars($color) . ';"';
                                                    foreach ($schedule[$day][$interval] as $data) {
                                                        if ($data['is_middle']==1 ){
                                                            echo ' class="occupiedmiddle"';
                                                        }elseif ($data['is_top']==1 ){
                                                            echo ' class="occupiedfirst"';
                                                        }else{
                                                            echo ' class="occupiedlast"';
                                                        }
                                                    }
                                                    
                                                 
                                                    
                                                    //echo ' data-subject="' . htmlspecialchars(implode(' and ', array_unique($subjectNames))) . '"';
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

    </main>
</body>
    <link rel="stylesheet" href="../css/generated-sched-room.css">
    <link rel="stylesheet" href="../css/main.css">
    
    <script src="../js/facultyloading.js"></script>
    <?php
        require_once('../include/js.php')
    ?>
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
</html>
