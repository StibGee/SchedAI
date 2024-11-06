<!DOCTYPE html>
<html lang="en">
    <?php  require_once('../include/head.php');?>

<body>

    <?php
        require_once('../include/user-mainnav.php');
       

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

<main class="col-sm-10 pb-5" id="main">
    <!-- NavBar -->
    <nav class="navbar sticky-top navbar-expand-lg border-bottom bg-body d-flex">
    <div class="container-fluid ">
        <div class="button col-4 col-sm-4">
        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseWidthExample" aria-expanded="true" aria-controls="collapseWidthExample"
                style="margin-right: 10px; padding: 0px 5px 0px 5px;" id="sidebartoggle" onclick="changeclass()">
                <i class="bi bi-list"></i>
        </button>
        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"
                style="margin-right: 10px; padding: 2px 6px 2px 6px;" id="sidebarshow">
                <i class="bi bi-list"></i>
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
                <div id="tabularViews" class="mt-2 table-responsive scroll-container">
                    <table id="subjectTable" class="table tablefaculty">
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
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <a href="#" class="nav_logo d-flex justify-content-start ">
                    <img src="../img/logo/Sched-logo1.png" width="60">
                </a>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">

                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="../faculty/dashboard.php" class="nav-link link-body-emphasis" aria-current="page">
                    <p class="bi bi-house-door"> Assigned Schedule</p><br>
                    </a>
                </li>
                <li>
                    <a href="../faculty/profile.php"  class="nav-link link-body-emphasis">
                    <p class="bi bi-speedometer2"> My Profile</p><br>
                    </a>
                </li>
                <li>
                    <a href="../faculty/user-account.php" class="nav-link link-body-emphasis">
                    <p class="bi bi-table"> Account Settings</p><br>
                    </a>
                </li>
                </ul>
            </div>
        </div>
    </main>

</body>

<script>
    function changeclass() {
      $("#main").toggleClass('col-sm-10 col-sm-12');
    }
</script>
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

  <script>
    function changeclass() {
      $("#main").toggleClass('col-sm-10 col-sm-12');
    }
  </script>
<script src="color-modes.js"></script>
