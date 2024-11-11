<!DOCTYPE html>
<html lang="en">
    <?php  require_once('../include/head.php');
    $_SESSION['currentpage']='schedule';
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
        require_once('../classes/room.php');
        require_once('../classes/faculty.php');

        $db = new Database();
        $pdo = $db->connect();

        $curriculum = new Curriculum($pdo);
        $faculty = new Faculty($pdo);
        $schedule = new Schedule($pdo);
        $college = new College($pdo);
        $department = new Department($pdo);
        $room = new Room($pdo);

        $collegelatestyear=$schedule->findcollegelatestyear($_SESSION['collegeid']);
        $facultyinfo=$faculty->getfacultyinfo($_SESSION['id']);
        $filteredschedules=$schedule->filteredschedulesfaculty($_SESSION['id'], $collegelatestyear);
        $calendarinfo=$curriculum->calendarinfo($collegelatestyear);

        $collegeroom=$room->getcollegerooms($_SESSION['collegeid']);
    ?>

<main >
    <div class="container p-3">
        <div class="row py-2 ">
            <span class="text-head">Schedule</span>
        </div>
        <div class="row d-flex justify-content-end align-items-center">
            <div class="col-12 col-md-6 head-label">
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
                            <option value="<?php echo $collegerooms['roomname']; ?>"><?php echo $collegerooms['roomname']; ?></option>
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


        <div class="sched-container my-4 p-3">
            <div class="d-flex ">
                <a href="dashboard-toggle.php" id="viewToggleButton" class="btn">
                    Toggle View
                </a>
            </div>
            <div class="sched-table mt-3">
                <div id="tabularViews" class="mt-2 table-responsive">
                    <table id="subjectTable" class="table tablefaculty">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Unit</th>
                                <th>YrSec</th>
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
                                    echo date("g:i A", strtotime($subjectschedules['starttime'])) . "-" . date("g:i A", strtotime($subjectschedules['endtime']));
                                }
                                ?>
                                </td>
                                <td><?php echo $subjectschedules['day'];?></td>
                                <td><?php echo $subjectschedules['roomname'];?></td>
                            </tr>
                            <?php $number+=1; } ?>
                        </tbody>
                    </table>
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

    </main>

</body>

<script>
    $(document).ready(function() {
        // Initialize the DataTable and store the instance in a variable
        var table = $('#subjectTable').DataTable({
            "paging": false,          // Enable pagination
            "searching": true,       // Enable search filter
            "ordering": true,        // Enable column sorting
            "info": true,
            "lengthChange": false,
            "pageLength": 100
        });

        // Event listener for the select filter
        $('#select-classtype').on('change', function() {
            var selectedType = $(this).val();  // Get selected value from the dropdown

            if (selectedType === "All") {
                table.column(3).search('').draw();  // If 'All' is selected, show all rows
            } else {
                table.column(3).search(selectedType).draw();  // Filter by the selected value (Lec or Lab)
            }
        });
        $('#select-roomtype').on('change', function() {
            var selectedType = $(this).val();  // Get selected value from the dropdown

            if (selectedType === "All") {
                table.column(8).search('').draw();  // If 'All' is selected, show all rows
            } else {
                table.column(8).search(selectedType).draw();  // Filter by the selected value (Lec or Lab)
            }
        });
        $('#customSearch').on('keyup', function() {
            table.search(this.value).draw();  // Perform search on DataTable when user types
        });
    });
</script>
<style>
    .dataTables_filter {
    display: none; /* Hide the default search input */
    }
</style>

    <link rel="stylesheet" href="../css/faculty-css/dashboard.css">
    <script src="../js/facultyloading.js"></script>
    <?php
        require_once('../include/js.php')
    ?>

