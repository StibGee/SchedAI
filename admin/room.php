<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body >

    <?php
        require_once('../include/nav.php');
        require_once('../database/datafetch.php');
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
                    
                    <div class=" room-table p-4">
                        <div class="table-contents">
                        <table class="table  mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Room Name</th>
                                    <th>Type</th>
                                    <th>Department</th>
                                    <th>Time Start</th>
                                    <th>Time End</th>
                                </tr>
                            </thead>
                                <tr onclick="window.location.href='/resources/admin/final-sched.php'">
                                    <?php foreach($room as $rooms){ ?>
                                    <td><?php echo $rooms['id'];?></td>
                                    <td><?php echo $rooms['name'];?></td>
                                    <td><?php echo $rooms['type'];?></td>
                                    <td><?php echo $rooms['departmentname'];?></td>
                                    <td><?php echo $rooms['timestart'];?></td>
                                    <td><?php echo $rooms['timeend'];?></td>
                                    <td></td>
                                    <?php } ?>
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
                                    <form id="facultyForm" method="POST"  action="../database/addroom.php" class="row g-3 mt-4 needs-validation" novalidate="">
                                    <h5>Room Details</h5>
                                        <div class="row mt-2">
                                            <div class="col-md-4">
                                                <label class="form-label" for="firstname">Room Name</label>
                                                
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control" name="name" required>
                                                    </div>
                                               
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label" for="firstname">Room Type</label>
                                                
                                                    <div class="col-md-8">
                                                        <select class="form-select" id="room-type" id="type" required name="type">
                                                            <option selected="" disabled="">Choose...</option>
                                                            <option value="lab">Lecture</option>
                                                            <option value="lec">Laboratory</option>
                                                        </select>
                                                    </div>
                                               
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label" for="firstname">Department</label>
                                               
                                                    <div class="col-md-8">
                                                        <select class="form-select" id="room-type" required name="departmentid">
                                                            <option selected="" disabled="">Choose...</option>
                                                            <?php foreach($department as $departments){ ?>
                                                            <option value="<?php echo $departments['id'];?>"><?php echo $departments['name'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                              
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <h5>Time Setup</h5>
                                            <div class="col-md-4">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Start Time</th>
                                                            <th class="text-center">End Time</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                        <tr>
                                                            <td>
                                                                <div class="form-row d-flex">
                                                                    <div class="col-12">
                                                                        <input type="time" class="form-control" name="timestart" value="07:00">
                                                                    </div>
                                                                    
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-row d-flex">
                                                                    <div class="col-12">
                                                                        <input type="time" class="form-control" name="timeend" value="07:00">
                                                                    </div>
                                                                   
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-between">

                                <button type="button" class="cancel" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                <button type="submit" class="confirm">Done</button>
                                </form>
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
