<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body >

    <?php
        require_once('../include/nav.php');
    ?>
    <main>
        <div class="container mb-1">
            <div class="row">
                <div class="text d-flex align-items-center">
                    <h2> Hola !!! </h2> <span> Role</span>
                </div>
            </div>
            <div class="row mt-2 d-flex align-items-center">
                <div class="col-5">
                    <h3>Rooms</h3>
                </div>
                <div class="col-2">
                    <select class="form-select form-select-sm" id="select-classtype">
                        <option>all</option>
                        <option>lecture</option>
                        <option>laboratory</option>
                    </select>
                </div>
                <div class="searchbar col-4 ">
                        <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                    </div>
                <div class="col-1 ">
                <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                </div>
            </div>
            <div class =" container mt-3">
                <div class="row d-flex justify-content-around">
                    <div class="col-6  p-4">
                        <div class="table-content">
                        <table class="table  mb-0 ">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Room Code</th>
                                        <th>Room Type</th>
                                        <th>Time Range</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr onclick="window.location.href='/resources/admin/final-sched.php'">
                                        <th scope="row">1</th>
                                        <td>LR1</td>
                                        <td>lecture</td>
                                        <td>1 <span>hr</span> 30 <span>mins</span></td>

                                    </tr>
                                    <tr onclick="window.location.href='/resources/admin/final-sched.php'">
                                        <th scope="row">1</th>
                                        <td>LR1</td>
                                        <td>lecture</td>
                                        <td>1 <span>hr</span> 30 <span>mins</span></td>

                                    </tr>
                                    <tr onclick="window.location.href='/resources/admin/final-sched.php'">
                                        <th scope="row">1</th>
                                        <td>LR1</td>
                                        <td>lecture</td>
                                        <td>1 <span>hr</span> 30 <span>mins</span></td>

                                    </tr>
                                    <tr onclick="window.location.href='/resources/admin/final-sched.php'">
                                        <th scope="row">1</th>
                                        <td>LR1</td>
                                        <td>lecture</td>
                                        <td>1 <span>hr</span> 30 <span>mins</span></td>

                                    </tr>
                                    <tr onclick="window.location.href='/resources/admin/final-sched.php'">
                                        <th scope="row">1</th>
                                        <td>LR1</td>
                                        <td>lecture</td>
                                        <td>1 <span>hr</span> 30 <span>mins</span></td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="col-5 room-table p-4">
                        <div class="table-contents">
                        <label for="">Room code</label>
                        <table class="table  mb-0">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Room Hours</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr onclick="window.location.href='/resources/admin/final-sched.php'">
                                    <td>Monday</td>
                                    <td>7:00 am - 7:00 pm</td>
                                    <td></td>
                                </tr>
                                <tr onclick="window.location.href='/resources/admin/final-sched.php'">
                                    <td>Monday</td>
                                    <td>7:00 am - 7:00 pm</td>
                                    <td></td>
                                </tr>
                                <tr onclick="window.location.href='/resources/admin/final-sched.php'">
                                    <td>Monday</td>
                                    <td>7:00 am - 7:00 pm</td>
                                    <td></td>
                                </tr>
                                <tr onclick="window.location.href='/resources/admin/final-sched.php'">
                                    <td>Monday</td>
                                    <td>7:00 am - 7:00 pm</td>
                                    <td></td>
                                </tr>
                                <tr onclick="window.location.href='/resources/admin/final-sched.php'">
                                    <td>Monday</td>
                                    <td>7:00 am - 7:00 pm</td>
                                    <td></td>
                                </tr>
                                <tr onclick="window.location.href='/resources/admin/final-sched.php'">
                                    <td>Monday</td>
                                    <td>7:00 am - 7:00 pm</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        </div>

                    </div>
                </div>


            </div>

            </div>
        </div>

        <!-- Modal Form -->
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg mt-6" role="document">
                    <div class="modal-content border-0">
                        <div class="modal-body p-3">
                            <div class="position-absolute top-0 end-0 mt-3 me-3 z-1">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="rounded-top-3 form p-4">
                                <h2 class="head-label">Add Rooms</h2>
                                <div class="container form ">
                                    <form id="facultyForm" class="row g-3 mt-4 needs-validation" novalidate="">
                                        <h5>Room Type</h5>
                                        <div class="row ">
                                            <div class="col-md-6">
                                                <select class="form-select" id="room-type" required="">
                                                    <option selected="" disabled="" value="">Choose...</option>
                                                    <option>Lecture</option>
                                                    <option>Laboratory</option>
                                                </select>
                                            </div>
                                        </div>
                                        <h5>Setup Room Time Slot</h5>
                                        <div class="container">
                                        <div class="row ">
                                            <div class="col-md-6">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Day</th>
                                                            <th>Hour Range</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th>Monday</th>
                                                            <td>
                                                                <div class="form-row d-flex">
                                                                    <div class="col-6">
                                                                        <input type="time" class="form-control" name="startTimeMonday" value="07:00">
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <input type="time" class="form-control" name="endTimeMonday" value="19:00">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Tuesday</th>
                                                            <td>
                                                                <div class="form-row d-flex">
                                                                    <div class="col-6">
                                                                        <input type="time" class="form-control" name="startTimeTuesday" value="07:00">
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <input type="time" class="form-control" name="endTimeTuesday" value="19:00">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Wednesday</th>
                                                            <td>
                                                                <div class="form-row d-flex">
                                                                    <div class="col-6">
                                                                        <input type="time" class="form-control" name="startTimeWednesday" value="07:00">
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <input type="time" class="form-control" name="endTimeWednesday" value="19:00">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Day</th>
                                                            <th>Hour Range</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th>Thursday</th>
                                                            <td>
                                                                <div class="form-row d-flex">
                                                                    <div class="col-6">
                                                                        <input type="time" class="form-control" name="startTimeThursday" value="07:00">
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <input type="time" class="form-control" name="endTimeThursday" value="19:00">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Friday</th>
                                                            <td>
                                                                <div class="form-row d-flex">
                                                                    <div class="col-6">
                                                                        <input type="time" class="form-control" name="startTimeFriday" value="07:00">
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <input type="time" class="form-control" name="endTimeFriday" value="19:00">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Saturday</th>
                                                            <td>
                                                                <div class="form-row d-flex">
                                                                    <div class="col-6">
                                                                        <input type="time" class="form-control" name="startTimeSaturday" value="07:00">
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <input type="time" class="form-control" name="endTimeSaturday" value="19:00">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-between">

                                <button type="button" class="cancel" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                <button type="button" class="confirm" onclick="window.location.href='room.php'">Done</button>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
</main>
</body>

    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/room.css">
    <script src="../js/main.js"></script>
    <?php
        require_once('../include/js.php')
    ?>

</html>
