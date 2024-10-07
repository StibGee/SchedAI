
<?php
    require_once('../include/head.php');
   
    require_once('../include/nav.php');

    require_once('../classes/room.php');
    $db = new Database();
    $pdo = $db->connect();

    $room = new Room($pdo);

    $collegeid=$_SESSION['collegeid'];
    $inititialcollegeroom = $room->getinitialcollegeroom($collegeid);
    $collegeroom=$room->getcollegerooms($collegeid);
    
    

    if (isset($_POST['roomid'])) {
        $roomids = $_POST['roomid'];
        foreach ($collegeroom as $collegerooms){
            if ($collegerooms['id']== $roomids){
                $roomname=$collegerooms['name'];
               
            }
        }
    } else {
        $roomids = $inititialcollegeroom;
        foreach ($collegeroom as $collegerooms){
            if ($collegerooms['id']== $roomids){
                $roomname=$collegerooms['name'];
            }
        }
        
    }

        // Define days and intervals
    $days = ['M', 'T', 'W', 'Th', 'F', 'S'];
    $intervals = [];
    for ($i = 7; $i <= 18; $i++) {
        $intervals[] = sprintf("%02d:00-%02d:30", $i, $i);
        $intervals[] = sprintf("%02d:30-%02d:00", $i, $i + 1);
    }

    // Function to generate a color based on subject ID
    function generateColor($id) {
        $hue = ($id * 137.508) % 360; // Generate a hue value based on subject ID
        return "hsl($hue, 70%, 80%)"; // Return a color in HSL format
    }

    // Fetch timetable data from the database
    $sql = "SELECT 
                day, 
                TIME_FORMAT(timestart, '%H:%i') AS timestart, 
                TIME_FORMAT(timeend, '%H:%i') AS timeend, 
                subjectschedule.id as subjectidno,
                subject.subjectcode as subjectname,
                subjectschedule.yearlvl as yearlvl,
                section,
                faculty.lname as facultyname,
                subject.type as subjecttype,
                department.abbreviation as departmentname
            FROM 
                subjectschedule 
                JOIN subject ON subject.id = subjectschedule.subjectid
                JOIN faculty ON faculty.id = subjectschedule.facultyid
                JOIN department ON subjectschedule.departmentid = department.id
            WHERE roomid = $roomids";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Initialize an empty array to store schedule data
    $schedule = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $daysArray = preg_split('/(?<=[a-zA-Z])(?=[A-Z])/', $row['day']); // Split multi-day entries
        $starttime = $row['timestart'];
        $endtime = $row['timeend'];

        $subjectid = (int)$row['subjectidno']; 
        $subjectname = htmlspecialchars($row['subjectname']);
        $departmentname = htmlspecialchars($row['departmentname']);
        $subjecttype = htmlspecialchars($row['subjecttype']);
        $yearlvl = htmlspecialchars($row['yearlvl']);
        $section = htmlspecialchars($row['section']);
        $facultyname = htmlspecialchars($row['facultyname']);
        
        // Include year, section, and place faculty name below the subject name
        $subjectLabel = "$subjectname $subjecttype $departmentname $yearlvl$section ($facultyname)"; 
        $color = generateColor($subjectid); // Generate a color for the subject ID
        
        // Convert start and end times to minutes since midnight
        $startMinutes = (int)date('H', strtotime($starttime)) * 60 + (int)date('i', strtotime($starttime));
        $endMinutes = (int)date('H', strtotime($endtime)) * 60 + (int)date('i', strtotime($endtime));
        
        // Calculate the number of 30-minute intervals needed
        $intervalCount = ($endMinutes - $startMinutes) / 30;

        // Determine the middle index for centering the subject
        $middleIndex = (int)floor($intervalCount / 2);
        $startIndex = $intervalCount % 2 === 0 ? $middleIndex - 1 : $middleIndex; // Adjust starting index for even intervals

        // Populate schedule array with subject names and colors based on day and interval
        for ($i = 0; $i < $intervalCount; $i++) {
            $currentStart = date('H:i', strtotime($starttime) + ($i * 30 * 60));
            $currentEnd = date('H:i', strtotime($currentStart) + (30 * 60));
            $interval = sprintf("%s-%s", $currentStart, $currentEnd);
        
            foreach ($daysArray as $day) {
                if (!isset($schedule[$day][$interval])) {
                    $schedule[$day][$interval] = [];
                }
                
              // Determine if this is the top, bottom, or middle cell for the subject
                $isTop = ($i == 0);
                $isBottom = ($i == $intervalCount - 1);
                $isMiddle = ($i != 0 && $i != $intervalCount - 1);

        
                // Center subject name in the middle cell(s)
                if ($i >= $startIndex && $i <= $startIndex + ($intervalCount % 2 ? 0 : 1)) {
                    $schedule[$day][$interval][] = [
                        'color' => $color,
                        'subjectname' => $subjectLabel,
                        'is_center' => true, // Mark this as the center cell for the subject
                        'is_top' => $isTop,   // Assign is_top based on condition
                        'is_middle' => $isMiddle,
                        'is_bottom' => $isBottom // Assign is_bottom based on condition
                    ];
                } else {
                    $schedule[$day][$interval][] = [
                        'color' => $color,
                        'subjectname' => '',
                        'is_center' => false,
                        'is_top' => $isTop,     // Not the top for empty cells
                        'is_middle' => $isMiddle,
                        'is_bottom' => $isBottom   // Not the bottom for empty cells
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
                        <?php if(($_SESSION['sem'])==1){echo "1st Semester";}else{echo "2nd Semester";}?> <span><?php if(($_SESSION['departmentid'])==1){echo "BSCS";}else{echo "BSIT";}?></span> <span>SY-</span> <span><?php echo $_SESSION['year'];?></span>
                    </h5>
                </div>
            </div>
            <div class="row d-flex justify-content-end align-items-center">
                <div class="col-1">
                        <select class="form-select  form-select-sm " id="filter" onchange="handleOptionChange()">
                            <option value="">Select an option</option>
                            <option value="final-sched-room.php">By Rooms</option>
                            <option value="final-sched-faculty.php">By Faculty</option>
                            <option value="final-sched-subject.php">By Subject</option>
                        </select>
                </div>
                <div class="col-1">
                    <form class="mb-0" action="final-sched-room.php" method="POST">
                        <select class="form-select  form-select-sm " id="select-classtype" name="roomid" onchange="this.form.submit()">
                            <?php foreach ($collegeroom as $collegerooms): 
                                //if ($collegerooms['departmentid']==$_SESSION['departmentid']){?>
                                
                                <option value="<?php echo $collegerooms['id']; ?>" 
                                    <?php 
                                        if (isset($roomids) && $roomids == $collegerooms['id']) {
                                           
                                            $roomname = isset($collegerooms['name']) ? $collegerooms['name'] : ''; 
                                            echo 'selected'; 
                                        }
                                    ?>>
                                    <?php echo isset($collegerooms['name']) ? htmlspecialchars($collegerooms['name']) : ''; ?>
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
                    <button id="viewToggleButton">
                        Toggle View
                    </button>
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
                                                // Apply background color and subject name based on the schedule
                                                if (isset($schedule[$day][$interval])) {
                                                    $subjectData = $schedule[$day][$interval];
                                                    $colors = array_column($subjectData, 'color');
                                                    //$subjectNames = array_column($subjectData, 'subjectname');
                                                    //$isCenters = array_column($subjectData, 'is_center');
                                                    $color = $colors[0]; // Use the first color if multiple subjects
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
                                                // Display subject name only in the center cell
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
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/generated-sched-room.css">
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
