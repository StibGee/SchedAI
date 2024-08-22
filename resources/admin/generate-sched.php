<!DOCTYPE html>
<html lang="en">
<?php
        require_once('./include/head.php');
    ?>

<body >

    <?php
        require_once('./include/nav.php');
    ?>
    <main>
        <div class="container mb-5">
            <div class="row">
                <div class="text d-flex align-items-center">
                    <h2> Hola !!! </h2> <span> Role</span>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <h5>Semester <span>Department</span></h5>
                    <p>SY- <span>Year</span></p>
                </div>
                <div class="col-6 generate d-flex justify-content-end align-items-center">
                    <button id="assign" data-toggle="modal" data-target="#formModal">Generate</button>
                </div>
            </div>
            <div class="sched-container my-3">
                <div class="d-flex ">
                    <button id="viewToggleButton">
                        Toggle View
                    </button>
                </div>
                <div class="sched-table mt-3">
                    <div id="scheduleView">
                        <!-- Tabular View -->
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
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mt-6" role="document">
                <div class="modal-content border-0">
                    <div class="modal-body p-4">
                        <div class="position-absolute top-0 end-0 mt-3 me-3 z-1">
                            <button type="button" class="btn-close" onclick="window.location.href='generate-sched.php'" aria-label="Close"></button>
                        </div>
                        <div class="rounded-top-3 bg-body-tertiary ">
                            <h2 class="head-label">Select Priority List</h2>
                            <div class="centered-form p-3">
                                <form>
                                    <div class="row my-3">
                                        <select class="form-select  form-select-sm " id="select-classtype">
                                            <option>all</option>
                                            <option>lec</option>
                                            <option>lab</option>
                                        </select>
                                        <div class="searchbar col-6 d-flex justify-content-end">
                                            <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                                        </div>
                                    </div>
                                    <div class="card shadow-none p-3">
                                        <div class="table-responsive scrollbar" style="max-height: 300px;">
                                            <table class="table mb-0">
                                                <thead class="bg-200">
                                                    <tr>
                                                        <th class="text-black dark__text-white align-middle">#</th>
                                                        <th class="text-black dark__text-white align-middle">Department</th>
                                                        <th class="text-black dark__text-white align-middle">Name</th>
                                                        <th class="text-black dark__text-white align-middle">Position</th>
                                                        <th class="text-black dark__text-white align-middle">Expertise</th>
                                                        <th class="align-middle last-column">
                                                            <div class="form-check mb-0"><input class="form-check-input" type="checkbox"  /> Select All</div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="faculty-select-body">
                                                    <tr>
                                                        <td class="align-middle">#</td>
                                                        <td class="align-middle">Computer Science</td>
                                                        <td class="align-middle">Rovic Quilantang</td>
                                                        <td class="align-middle">Dean</td>
                                                        <td class="align-middle">Cloutchase</td>
                                                        <td class="align-middle last-column">
                                                            <div class="form-check mb-0"><input class="form-check-input" type="checkbox" id="checkbox-1" data-bulk-select-row="data-bulk-select-row" /></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">#</td>
                                                        <td class="align-middle">Computer Science</td>
                                                        <td class="align-middle">Rovic Quilantang</td>
                                                        <td class="align-middle">Dean</td>
                                                        <td class="align-middle">Cloutchase</td>
                                                        <td class="align-middle last-column">
                                                            <div class="form-check mb-0"><input class="form-check-input" type="checkbox" id="checkbox-1" data-bulk-select-row="data-bulk-select-row" /></div>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer d-flex justify-content-between ">
                                <button type="button" class="cancel" onclick="window.location.href='generate-sched.php'">Cancel</button>
                                <button type="button" class="confirm" onclick="window.location.href='generated-sched.php'">Done</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</body>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/generate-sched.css">
    <script src="/assets/js/generate-sched.js"></script>
    <?php
        require_once('./include/js.php')
    ?>

</html>
