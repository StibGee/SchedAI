<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body>

    <?php

        require_once('../include/user-mainnav.php');
        require_once('../database/datafetch.php');
    ?>
<main>
    <div class="container ">
        <div class="row">
            <div class="text d-flex align-items-center" >
                <h2> Hola !!! </h2> <span> Role</span>
            </div>
        </div>
        <div class="row mt-4">
                <div class="header-table">
                    <h5>
                        Semester <span>Department</span> <span>SY-</span> <span>Year</span>
                    </h5>
                </div>
            </div>
            <div class="row d-flex justify-content-end align-items-center">
                <div class="col-1">
                    <select class="form-select  form-select-sm " id="select-year&sec">
                        <option>all</option>
                        <option>CS4A</option>
                        <option>CS4B</option>
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
                                    <th>No.</th>
                                    <th>Deparment</th>
                                    <th>Subject Code</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Unit</th>
                                    <th>Room</th>
                                    <th>Time</th>
                                    <th>Day</th>
                                    <th>Year & Sec</th>
                                    <th>Lecturer</th>
                                </tr>
                            </thead>
                            <tbody id="tabularTableBody">
                                <tr>
                                    <td>1</td>
                                    <td>Computer Science</td>
                                    <td>CS101</td>
                                    <td>Intro to Computer Science</td>
                                    <td>Lecture</td>
                                    <td>3</td>
                                    <td>R101</td>
                                    <td>8:00 AM - 9:00 AM</td>
                                    <td>Monday</td>
                                    <td>1A</td>
                                    <td>Dr. Smith</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Mathematics</td>
                                    <td>MATH101</td>
                                    <td>Calculus I</td>
                                    <td>Lecture</td>
                                    <td>4</td>
                                    <td>R102</td>
                                    <td>9:00 AM - 10:00 AM</td>
                                    <td>Tuesday</td>
                                    <td>1B</td>
                                    <td>Prof. Johnson</td>
                                </tr>
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
    <?php
        require_once('../include/js.php')
    ?>
</html>
