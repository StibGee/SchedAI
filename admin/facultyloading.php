<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body>

    <?php

        require_once('../include/nav.php');
        require_once('../database/datafetch.php');
    ?>
            <main>
            <div class="container">
                <div class="row">
                    <div class="text d-flex align-items-center" >
                        <h2> Hola !!! </h2> <span> Role</span>
                    </div>
                </div>
            <div class="row  mb-4 mx-2">
                <div class="faculty-type col-3">
                    <select class="form-select form-select-sm" id="select-faculty-type">
                        <option>Visiting Lecturer</option>
                        <option>Dean</option>
                        <option>Regular Faculty</option>
                    </select>
                </div>
                <div class="department col-3">
                    <select class="form-select form-select-sm" id="select-department">
                        <option>Information Technology</option>
                        <option>Computer Science</option>
                    </select>
                </div>
                <div class="searchbar col-6 d-flex justify-content-end">
                    <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                </div>
            </div>
            <div class="row">
                <div class=" col-5 mt-2">
                <div class="faculty-info">
                    <?php foreach($facultyinfo as $facultyinfos){ ?>
                    <li>Name : <?php echo $facultyinfos['fname']." ".$facultyinfos['mname']." ".$facultyinfos['lname'];?></li>
                    <li>Rank : <?php echo $facultyinfos['type'];?></li>
                    <li>Contact : <?php echo $facultyinfos['contactno'];?></li>
                    <li>Specialization :</li>
                <?php } ?>
                </div>
                <div class="row mt-4">
                    <div class="col-9 subload-title">
                        <h5>Subject Loaded</h5>
                    </div>
                    <div class="col-3">
                        <select class="form-select  form-select-sm " id="select-classtype">
                            <option>all</option>
                            <option>lec</option>
                            <option>lab</option>
                        </select>
                    </div>
                </div>
                <div class="p-4">
                    <div id="Subject-load" data-list="{&quot;valueNames&quot;:[&quot;yr&sec&quot;,&quot;email&quot;,&quot;desc&quot;,&quot;subtype&quot;,&quot;Units&quot;}">
                        <div class="table-responsive">
                        <table class="table table-sm fs-9 mb-0">
                            <thead>
                            <tr>
                                <th data-sort="subcode">Code</th>
                                <th  data-sort="desc">Description</th>
                                <th  data-sort="desc">Type</th>
                                <th  data-sort="desc">Units</th>

                            </tr>
                            </thead>
                            <tbody class="list">
                            <tr>
                                <td class="align-middle subcode">CS139</td>
                                <td class="align-middle desc">Web Development</td>
                                <td class="align-middle subtype">Lec</td>
                                <td class="align-middle unit">3.00</td>
                            </tr>
                            </tbody>
                            <tbody class="list">
                            <tr>
                                <td class="align-middle subcode">CS139</td>
                                <td class="align-middle desc">Web Development</td>
                                <td class="align-middle subtype">Lec</td>
                                <td class="align-middle unit">3.00</td>
                            </tr>
                            </tbody>
                            <tbody class="list">
                            <tr>
                                <td class="align-middle subcode">CS139</td>
                                <td class="align-middle desc">Web Development</td>
                                <td class="align-middle subtype">Lec</td>
                                <td class="align-middle unit">3.00</td>
                            </tr>
                            </tbody>

                        </table>
                        </div>

                    </div>
                    </div>
                </div>
                <div class="col-7">
                <div class="sched-container">
                    <div class="row mt-2 justify-content-between">
                        <div class="col-5">
                            <h5>Faculty Schedule <span>(role)</span></h5>
                            <p>Total No. Of Units Loaded : <span> #</span></p>
                        </div>
                        <div class="col-2">
                            <button id="viewToggleButton">
                                Toggle View
                            </button>
                        </div>
                    </div>
                    <div class="sched-table">
                        <div id="scheduleView">
                            <!-- Tabular View -->
                            <div id="tabularView" class="mt-2">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Subject Code</th>
                                            <th>Description</th>
                                            <th>Type</th>
                                            <th>Room</th>
                                            <th>Time</th>
                                            <th>Day</th>
                                            <th>Year & Sec</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabularTableBody">
                                        <!-- Data rows will be added here -->
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
            </div>
        </main>
</body>
<script src="../js/facultyloading.js"></script>
<link rel="stylesheet" href="../css/main.css">
<link rel="stylesheet" href="../css/facultyloading.css">

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</html>
