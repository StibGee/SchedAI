<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body>

    <?php

        require_once('../include/user-mainnav.php');
        require_once('../classes/subject.php');
        require_once('../classes/db.php');
        require_once('../classes/faculty.php');
        require_once('../classes/department.php');
        $db = new Database();
        $pdo = $db->connect();

        $subject = new Subject($pdo);
        $faculty = new Faculty($pdo);
        $department = new Department($pdo);
        $facultyinfo=$faculty->getfacultyinfo($_SESSION['id']);
        $facultysubjects=$faculty->getfacultysubjects($_SESSION['id']);
        $facultypreference=$faculty->getfacultydaytime($_SESSION['id']);
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
  <div class="profile-table p-3 ">
            <div class="table-header">
                <div class="row m-">
                    <div class="col-12 col-md-6 first">
                        <div class="row">
                            <div class="col-12 col-md-2 img-upload">
                                <div class="image-container">
                                    <img src="http://www.clker.com/cliparts/M/o/W/d/C/j/about-icon-md.png" class="circle-image">
                                    <label for="file-upload" class="custom-file-upload">
                                        <i class="fa-solid fa-camera"></i>
                                    </label>
                                    <input id="file-upload" type="file" name="image" class="img">
                                </div>
                                <form action="student-profile.php" method="post"></form>
                            </div>
                            <div class="name  col-12 col-md-6">
                                <h3><?php echo $facultyinfo['fname'].' '.$facultyinfo['lname'];?></h3>
                                <div class="row">
                                    <label for=""><?php $departmmentinfo=$department->getdepartmentinfo($facultyinfo['departmentid']); echo $departmmentinfo['name']?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 btm col-md-6 second d-flex flex-column justify-content-center ">
                        <a href="facultyprofiling.php" class="save text-center mb-2 custom-link">Edit Profile</a>
                    </div>
                </div>
            </div>
            <div class="row exp">
        <div class="profile-info col-12 col-md-3">
            <div class="info p-3">
                <div class="form-group">
                    <label for="teachingHour">Teaching Hour</label>
                    <input readonly type="text" class="form-control" id="teachingHour" value="<?php echo $facultyinfo['teachinghours'];?>">
                </div>
                <div class="form-group">
                    <label for="genderSelect">Gender</label>
                    <input readonly type="text" class="form-control" name="" id="" value="<?php echo $facultyinfo['gender'];?>">
                </div>
                <div class="form-group">
                    <label for="contactNo">Contact No.</label>
                    <input readonly type="number" class="form-control" id="contactNo" value="<?php echo $facultyinfo['contactno'];?>">
                </div>
            </div>
        </div>
        <div class="col-12 col-md-9 px-3">
            <div class="faculty-info p-3">
                <label for="">Faculty Information</label>
                <div class="row p-3">
                    <div class="col-12 col-md-6">
                        <label for="">Position/Rank</label>
                        <div class="input-group">
                            <input readonly type="text" class="form-control" value="<?php echo $facultyinfo['rank'];?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="">Start Of Service</label>
                        <div class="input-group">
                            <input readonly type="text" class="form-control" value="<?php echo $facultyinfo['startdate'];?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row p-3">
                <div class="col-12">
                    <div class="row p-3">
                        <div class="table-load col-12 col-md-6">
                            <label class="form-label" for="degree">Employment Type</label>
                            <input readonly type="text" class="form-control" value="<?php echo $facultyinfo['type'];?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row p-4 m-2">
                <label for="">Expertise</label>
                <?php
                    $totalSubjects = count($facultysubjects);
                    $half = ceil($totalSubjects / 2);
                    $leftColumn = array_slice($facultysubjects, 0, $half);
                    $rightColumn = array_slice($facultysubjects, $half);
                ?>
                <div class="table-load col-12 col-md-6">
                    <?php foreach ($leftColumn as $subject) { ?>
                        <li><?php echo $subject['subjectname']; ?></li>
                    <?php } ?>
                </div>
                <div class="table-load col-12 col-md-6">
                    <?php foreach ($rightColumn as $subject) { ?>
                        <li><?php echo $subject['subjectname']; ?></li>
                    <?php } ?>
                </div>
            </div>
            <div class="row ">
                <div class="preference-table p-3">
                    <label for="">Preferences</label>
                    <div class="pref p-3 m-4">
                        <table id="editableTable" class="table table-sm fs-9 mb-0">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php  $daysofweek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                foreach ($facultypreference AS $facultypreferences){
                                    $dayindex = $facultypreferences['day'];
                                    $day = isset($daysofweek[$dayindex]) ? $daysofweek[$dayindex] : 'Unknown Day';?>
                                    <tr>
                                        <td><?php echo $day;?></td>
                                        <td><?php echo date("g:i A", strtotime($facultypreferences['starttime']));?></td>
                                        <td><?php echo date("g:i A", strtotime($facultypreferences['endtime']));?></td>
                                    </tr>
                                <?php } ?>
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
    <link rel="stylesheet" href="../css/faculty-css/profile.css">
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

