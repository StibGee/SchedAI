
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


    $departmentidpost = isset($_POST['departmentidpost']) ? $_POST['departmentidpost'] : 1;
    $yearlvlpost = isset($_POST['yearlvlpost']) ? $_POST['yearlvlpost'] : 1;
    $sectionpost = isset($_POST['sectionpost']) ? $_POST['sectionpost'] : 'A';

    echo ($departmentidpost.' '. $yearlvlpost);
    if ($_SESSION['departmentid']==0){
        $collegeroom=$room->getcollegerooms($collegeid);
        $collegesections=$schedule->getcollegesection($collegeid);
    }else{
        $collegeroom=$room->getdepartmentrooms($_SESSION['departmentid']);
        $collegesections=$schedule->getdepartmentsection($_SESSION['departmentid']);
    }
    $calendarid=$_SESSION['calendarid'];
    $collegeinfo=$college->getcollegeinfo($collegeid);


    $departmentinfo1=$department->getdepartmentinfo($departmentidpost);
    if ($_SESSION['departmentid']!=0){
        $departmentinfo=$department->getdepartmentinfo($_SESSION['departmentid']);
        $filteredschedules=$schedule->filteredschedule($_SESSION['calendarid'], $_SESSION['departmentid']);
        $minornofacultycount=$schedule->minorfacultycountdepartment($_SESSION['departmentid'], $_SESSION['calendarid']);
        $minorsubjectsnofaculty=$schedule->minornofacultydepartment($_SESSION['departmentid'], $_SESSION['calendarid']);
    }else{
        $minorsubjectsnofaculty=$schedule->minornofacultycollege($collegeid, $_SESSION['calendarid']);
        $minornofacultycount=$schedule->minorfacultycountcollege($collegeid, $_SESSION['calendarid']);
        $collegeinfo=$college->getcollegeinfo($collegeid);

        $filteredschedules=$schedule->filteredschedule($_SESSION['calendarid'], $_SESSION['collegeid']);

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
                TIME_FORMAT(subjectschedule.timestart, '%H:%i') AS timestart,
                TIME_FORMAT(subjectschedule.timeend, '%H:%i') AS timeend,
                subjectschedule.id AS subjectidno,
                subject.subjectcode AS subjectname,
                subjectschedule.yearlvl AS yearlvl,
                section,
                faculty.fname AS facultyname,
                subject.type AS subjecttype,
                department.abbreviation AS departmentname,
                subject.hours AS subjecthours,
                subject.unit AS subjectunit,
                subject.type AS subjecttype,
                room.name as roomname
            FROM
                subjectschedule
                JOIN subject ON subject.id = subjectschedule.subjectid
                LEFT JOIN faculty ON faculty.id = subjectschedule.facultyid
                LEFT JOIN room ON room.id = subjectschedule.roomid
                JOIN department ON subjectschedule.departmentid = department.id
            WHERE
                department.collegeid = $collegeid
                AND subjectschedule.calendarid = $calendarid
                AND subjectschedule.departmentid = $departmentidpost
                AND subjectschedule.yearlvl = $yearlvlpost
                AND subjectschedule.section = '$sectionpost'
            ";
    }else{
        $departmentid=$_SESSION['departmentid'];
        $sql = "SELECT
                day,
                TIME_FORMAT(subjectschedule.timestart, '%H:%i') AS timestart,
                TIME_FORMAT(subjectschedule.timeend, '%H:%i') AS timeend,
                subjectschedule.id as subjectidno,
                subject.subjectcode as subjectname,
                subjectschedule.yearlvl as yearlvl,
                section,
                faculty.fname as facultyname,
                subject.type as subjecttype,
                department.abbreviation as departmentname,
                subject.hours as subjecthours,
                subject.unit as subjectunit,
                subject.type as subjecttype,
                room.name as roomname
            FROM
                subjectschedule
                JOIN subject ON subject.id = subjectschedule.subjectid
                JOIN faculty ON faculty.id = subjectschedule.facultyid
                JOIN room ON room.id = subjectschedule.roomid
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
        $subjectunit = (int)$row['subjectunit'];
        $subjectid = (int)$row['subjectidno'];
        $subjecthour = $row['subjecthours'];
        $subjectname = htmlspecialchars($row['subjectname']);
        $departmentname = htmlspecialchars($row['departmentname']);
        $subjecttype = htmlspecialchars($row['subjecttype']);
        $yearlvl = htmlspecialchars($row['yearlvl']);
        $section = htmlspecialchars($row['section']);
        $roomname = !empty($row['roomname']) ? htmlspecialchars($row['roomname']) : 'General';
        $facultyname = !empty($row['facultyname']) ? htmlspecialchars($row['facultyname']) : 'TBA';



        $subjectLabel = "$subjectname $subjecttype ($roomname)($facultyname)";
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
                        'subjectid' => $subjectid,
                        'subjecthour' => $subjecthour,
                        'subjectunit' => $subjectunit,
                        'subjecttype' => $subjecttype,
                        'is_center' => true,
                        'is_top' => $isTop,
                        'is_middle' => $isMiddle,
                        'is_bottom' => $isBottom
                    ];
                } else {
                    $schedule[$day][$interval][] = [
                        'color' => $color,
                        'subjectname' => $subjectLabel,
                        'subjectid' => $subjectid,
                        'subjecthour' => $subjecthour,
                        'subjectunit' => $subjectunit,
                        'subjecttype' => $subjecttype,
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
            <div class="row mt-4">
                <div class="header-table">
                    <h5>
                        <button class="back" href="javascript:history.back()">
                            <i class="fa-solid fa-circle-arrow-left"></i>
                        </button>
                        <?php if(($_SESSION['sem'])==1){echo "1st Semester";}else{echo "2nd Semester";}?> <span><?php if ($_SESSION['departmentid']!=0){echo $departmentinfo['abbreviation']; }else{ echo $collegeinfo['abbreviation'];}?></span> <span>SY-</span> <span><?php echo $_SESSION['year'];?></span>
                    </h5>
                </div>
            </div>
            <div class="row d-flex justify-content-end align-items-center">
                <div class="col-2">
                        <select class="form-select  form-select-sm " id="filter" onchange="handleOptionChange()">
                            <option value="" disabled selected>Select </option>
                            <option value="final-sched-room.php">Faculty Schedule</option>
                            <option value="final-sched-section.php">Student Schedule</option>
                        </select>
                </div>
                <div class="col-2">
                <form class="mb-0" id="roomForm" action="final-sched-section.php" method="POST">
    <select class="form-select form-select-sm" id="select-classtype" name="roomid" onchange="updateAndSubmit()">
        <option value="" selected>Select a Room</option>
        <?php foreach ($collegesections as $collegesection) { 
            // Create the concatenated value
            $roomValue = htmlspecialchars($collegesection['abbreviation']) . ' ' . htmlspecialchars($collegesection['yearlvl']) . htmlspecialchars($collegesection['section']);
        ?>
            <option value="<?php echo $roomValue; ?>" 
                <?php 
                    // Check if the current room matches the selected department, yearlvl, and section
                    if (($departmentidpost.$yearlvlpost.$sectionpost) == ($collegesection['departmentid'].$collegesection['yearlvl'].$collegesection['section'])) {
                        echo 'selected'; 
                    } 
                ?>>
                <?php echo $roomValue; ?>
            </option>
        <?php } ?>
    </select>

    <!-- Hidden fields for department, year level, and section -->
    <input type="hidden" id="departmentidpost" name="departmentidpost" value="">
    <input type="hidden" id="yearlvlpost" name="yearlvlpost" value="">
    <input type="hidden" id="sectionpost" name="sectionpost" value="">
    <!-- Add an input to directly send the room value -->
    <input type="hidden" id="roomValue" name="roomValue" value="">
</form>




                </div>

                <div class="searchbar col-3 ">
                    <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                </div>


            </div>
            <div class="sched-container my-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h3><?php echo $departmentinfo1['abbreviation'].' '.$yearlvl.$section;?></h3>
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
                                                        $subjectIdClass = htmlspecialchars(str_replace(' ', '', $data['subjectid'])); 
                                                        if ($data['is_middle']) {
                                                            echo ' class="occupiedmiddle ' . 'subject ' . $subjectIdClass . '"
                                                                data-subject="' . htmlspecialchars($data['subjectid']) . '"
                                                                data-subjecthour="' . htmlspecialchars($data['subjecthour']) . '"
                                                                data-subjectunit="' . htmlspecialchars($data['subjectunit']) . '"
                                                                data-subjecttype="' . htmlspecialchars($data['subjecttype']) . '"
                                                                draggable="true" ondragstart="handleDragStart(event)" ';
                                                        } elseif ($data['is_top']) {
                                                            echo ' class="occupiedmiddle ' . 'subject ' . $subjectIdClass . '"
                                                                data-subject="' . htmlspecialchars($data['subjectid']) . '"
                                                                data-subjecthour="' . htmlspecialchars($data['subjecthour']) . '"
                                                                data-subjectunit="' . htmlspecialchars($data['subjectunit']) . '"
                                                                 data-subjecttype="' . htmlspecialchars($data['subjecttype']) . '"
                                                                draggable="true" ondragstart="handleDragStart(event)" ';
                                                        } elseif ($data['is_bottom']) {
                                                            echo ' class="occupiedlast ' . 'subject ' . $subjectIdClass . '"
                                                                data-subject="' . htmlspecialchars($data['subjectid']) . '"
                                                                data-subjecthour="' . htmlspecialchars($data['subjecthour']) . '"
                                                                data-subjectunit="' . htmlspecialchars($data['subjectunit']) . '"
                                                                data-subjecttype="' . htmlspecialchars($data['subjecttype']) . '"
                                                                draggable="true" ondragstart="handleDragStart(event)" ';
                                                        }
                                                    }
                                                    //echo ' data-subject="' . htmlspecialchars(implode(' and ', array_unique($subjectNames))) . '"';
                                                }else{
                                                    echo ' class=""
                                                                data-day="' . htmlspecialchars($day) . '"
                                                                data-time="' . htmlspecialchars($start) . '"
                                                                draggable="true" ondragstart="handleDragStart(event)" ';

                                                }
                                                ?>
                                            >
                                                <?php
                                                if (isset($schedule[$day][$interval])) {
                                                    foreach ($schedule[$day][$interval] as $data) {
                                                        if ($data['is_center']) {
                                                            echo '<div class="subject ' . htmlspecialchars($data['subjectid']) . '" draggable="true"
                                                                    ondragstart="handleDragStart(event)"
                                                                    data-subject="' . htmlspecialchars($data['subjectid']) . '" data-subjecthour="' . htmlspecialchars($data['subjecthour']) . '"
                                                                    data-subjecttype="' . htmlspecialchars($data['subjecttype']) . '"
                                                                    data-subjectunit="' . htmlspecialchars($data['subjectunit']) . '"
                                                                    style="background-color: ' . htmlspecialchars($data['color']) . ';">';
                                                            echo htmlspecialchars($data['subjectname']);
                                                            echo '</div>';
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
<style>.timetable-cell {
    border: 1px solid #ddd;
    padding: 10px;
    width: 100px;
    height: 50px;
    position: relative;
}

.timetable-cell:hover {
    background-color: #f0f0f0;
}

.occupiedmiddle, .occupiedlast {
    cursor: move;
}

.occupiedmiddle.dragging, .occupiedlast.dragging {
    opacity: 0.5;
}
</style>
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

            if (selectedValue) {
                window.location.href = selectedValue;
            }
        }
    </script>


</html>

<script>

    let draggedElements = [];

    function handleDragStart(event) {
    const subjectId = event.target.getAttribute('data-subject');

    draggedElements = Array.from(document.querySelectorAll(`[data-subject="${subjectId}"]`));

    draggedElements.forEach(elem => {
        elem.style.opacity = '0.5';
    });

    event.dataTransfer.setData("text", event.target.id);
    }

    function handleDragOver(event) {
    event.preventDefault();
    }

    function handleDrop(event) {
    event.preventDefault();

    const target = event.target;

    if (target.tagName.toLowerCase() === "td" || target.tagName.toLowerCase() === "div") {

        draggedElements.forEach(elem => {
        elem.style.opacity = '1';
        });

        const draggedSubjectId = draggedElements[0].getAttribute('data-subject');
        const draggedSubjecthours = draggedElements[0].getAttribute('data-subjecthour');
        const draggedSubjecttype = draggedElements[0].getAttribute('data-subjecttype');
        const draggedSubjectunit = draggedElements[0].getAttribute('data-subjectunit');

        const droppedSubjectId = target.getAttribute('data-subject');
        const droppedSubjecthours = target.getAttribute('data-subjecthour');
        const droppedSubjecttype = target.getAttribute('data-subjecttype');
        const droppedSubjectunit = target.getAttribute('data-subjectunit');
        const droppedday = target.getAttribute('data-day');
        const droppedtime = target.getAttribute('data-time');

        if (draggedSubjecttype===droppedSubjecttype && draggedSubjectunit===droppedSubjectunit && draggedSubjecthours===droppedSubjecthours && draggedSubjectId!=droppedSubjectId){
            const confirmSwap = confirm("Are you sure you want to swap these subjects?");
            if (confirmSwap) {
                swapSubjects(draggedSubjectId, droppedSubjectId);
            }

        }else {


            alert(`Cannot swap. Please note the differences:\n
                Dragged Subject:\nType: ${droppedday}\nHours: ${droppedtime}`);
        }

    }
    }

    function swapSubjects(draggedSubjectId, droppedSubjectId) {
        console.log(`Swapping subject ${draggedSubjectId} with subject ${droppedSubjectId}`);
        const draggedSubject = document.querySelector(`[data-subject="${draggedSubjectId}"]`);
        const droppedSubject = document.querySelector(`[data-subject="${droppedSubjectId}"]`);

        if (draggedSubject && droppedSubject) {
            const tempText = draggedSubject.innerHTML;
            draggedSubject.innerHTML = droppedSubject.innerHTML;
            droppedSubject.innerHTML = tempText;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../processing/scheduleprocessing.php';

            const draggedInput = document.createElement('input');
            draggedInput.type = 'hidden';
            draggedInput.name = 'draggedsubjectid';
            draggedInput.value = draggedSubjectId;

            const droppedInput = document.createElement('input');
            droppedInput.type = 'hidden';
            droppedInput.name = 'droppedsubjectid';
            droppedInput.value = droppedSubjectId;

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'swap';

            form.appendChild(draggedInput);
            form.appendChild(droppedInput);
            form.appendChild(actionInput);

            document.body.appendChild(form);

            console.log('Submitting form');

            setTimeout(() => {
            form.submit();
            }, 500);
        }
    }

    document.querySelector('table').addEventListener('dragover', handleDragOver);
    document.querySelector('table').addEventListener('drop', handleDrop);

    document.querySelectorAll('[draggable="true"]').forEach(item => {
    item.addEventListener('dragstart', handleDragStart);
    });


</script>
<script>
window.addEventListener("beforeunload", () => {
    sessionStorage.setItem("scrollPosition", window.scrollY);
});

window.addEventListener("load", () => {
    const scrollPosition = sessionStorage.getItem("scrollPosition");
    if (scrollPosition) {
        window.scrollTo(0, parseInt(scrollPosition, 10));
    }
});

</script>
<script>
function updateAndSubmit() {
    // Get the selected room value
    const selectedRoom = document.getElementById('select-classtype').value;

    // Find the selected section data
    const collegesections = <?php echo json_encode($collegesections); ?>;
    const selectedSection = collegesections.find(function(section) {
        return section.abbreviation + ' ' + section.yearlvl + section.section === selectedRoom;
    });

    // If a section is found, set the hidden fields
    if (selectedSection) {
        document.getElementById('departmentidpost').value = selectedSection.departmentid;
        document.getElementById('yearlvlpost').value = selectedSection.yearlvl;
        document.getElementById('sectionpost').value = selectedSection.section;
    }

    // Set the roomValue field to the selected room's value
    document.getElementById('roomValue').value = selectedRoom;

    // Submit the form after updating the hidden fields
    document.getElementById('roomForm').submit();
}
</script>