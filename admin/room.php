<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
        $_SESSION['currentpage']='room';
        if (!isset($_GET['room'])){
            $_SESSION['loading']=1;
        }

    ?>

<body >

    <?php
        require_once('../include/nav.php');
        //require_once('../database/datafetch.php');
        require_once('../classes/room.php');
        require_once('../classes/department.php');
        require_once('../classes/college.php');
        require_once('../classes/db.php');
        $db = new Database();
        $pdo = $db->connect();
        $collegeid=$_SESSION['collegeid'];
        // Initialize Room class
        $room = new Room($pdo);
        $college = new College($pdo);
        if ($_SESSION['departmentid']!=0){
            $roomsall = $room->getdepartmentrooms($_SESSION['departmentid']);
        }else{
            $roomsall = $room->getcollegerooms($collegeid);
        }


        $department = new Department($pdo);
        $collegedepartment = $department->getcollegedepartment($collegeid);
        $collegemaxyearlvl = $college->getcollegemaxyearlvl($collegeid)

    ?>
    <main>
        <div class="container mb-1">
            <div class="row mt-2 d-flex align-items-center">
                <div class="col-4">
                    <h3>Rooms</h3>
                </div>
                <div class="col-2">
                    <select class="form-select form-select-sm" id="select-classtype">
                        <option>all</option>
                        <option>lecture</option>
                        <option>laboratory</option>
                    </select>
                </div>
                <div class="searchbar col-3 ">
                        <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                    </div>
                <div class="col-1 ">
                <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                </div>
            </div>
            <div class =" container mt-3">
                <div class="row d-flex justify-content-around">
                    <div class=" room-table p-4  d-flex justify-content-center">
                        <div class="table-contents">
                        <table class="table  mb-0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Room Name</th>
                                    <th>Type</th>
                                    <th>Department</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <?php $i=1; foreach($roomsall as $rooms){ ?>
                                <tr>

                                    <td><?php echo $i;?></td>
                                    <td><?php echo $rooms['roomname'];?></td>
                                    <td><?php echo $rooms['type'];?></td>
                                    <td><?php echo $rooms['departmentname'];?></td>

                                    <td>

                                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editroom<?php echo $rooms['roomid']; ?>" onclick="event.stopPropagation();" style="background: none; border: none; padding: 0;">
                                            <i class="fas fa-edit"></i>
                                        </button>


                                        <form action="../processing/roomprocessing.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this room?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $rooms['roomid']; ?>">
                                            <button type="submit" class="btn" onclick="event.stopPropagation();" style="background: none; border: none; padding: 0;">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>

                                    </td>

                                </tr>
                                <!-- edit room Modal Form -->
                                <div class="modal fade" id="editroom<?php echo $rooms['roomid'];?>" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg mt-6" role="document">
                                            <div class="modal-content border-0">
                                                <div class="modal-body p-3">
                                                    <div class="position-absolute top-0 end-0 mt-3 me-3 z-1">
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="rounded-top-3 form p-4">
                                                        <h2 class="head-label">Add Rooms</h2>
                                                        <div class="container form ">
                                                            <form id="facultyForm" method="POST"  action="../processing/roomprocessing.php" class="row g-3 mt-4 needs-validation " novalidate="">
                                                            <input type="hidden" name="action" value="editroom">
                                                            <input type="number" name="roomid" value="<?php echo $rooms['roomid'];?>" hidden>
                                                            <h5>Room Details</h5>
                                                            <div class="row mt-2">
                                                                    <div class="col-6">
                                                                        <label class="form-label" for="firstname">Department</label>
                                                                        <div class="col-md-12">
                                                                            <select class="form-select" id="room-type" name="departmentid" required>
                                                                                <option selected disabled>Choose...</option>
                                                                                <?php foreach($collegedepartment as $collegedepartments) { ?>
                                                                                    <option value="<?php echo $collegedepartments['id']; ?>"
                                                                                        <?php echo $rooms['departmentid'] == $collegedepartments['id'] ? 'selected' : ''; ?>>
                                                                                        <?php echo $collegedepartments['name']; ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-6">
                                                                        <label class="form-label" for="firstname">Room Name</label>
                                                                        <div class="col-md-12">
                                                                            <input type="text" class="form-control" name="name" required value="<?php echo $rooms['roomname'];?>">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-6">
                                                                        <label class="form-label" for="firstname">Room Type</label>
                                                                        <div class="col-md-12">
                                                                            <select class="form-select" id="room-type" name="type" required>
                                                                                <option selected disabled>Choose...</option>
                                                                                <option value="Lec" <?php echo $rooms['type'] == 'Lec' ? 'selected' : ''; ?>>Lecture</option>
                                                                                <option value="Lab" <?php echo $rooms['type'] == 'Lab' ? 'selected' : ''; ?>>Laboratory</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

    </div>
                                                                <div class="row mt-2">

                                                                </div>
                                                                <!--<div class="row mt-3">
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
                                                                </div>-->

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
                                <?php $i+=1;} ?>
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
                                    <form id="facultyForm" method="POST"  action="../processing/roomprocessing.php" class="row g-3 mt-4 needs-validation" novalidate="">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="collegeid" value="<?php echo $collegeid;?>">
                                    <h5>Room Details</h5>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                                <label class="form-label" for="firstname">Department</label>

                                                    <div class="col-md-12">
                                                        <select class="form-select" id="room-type" required name="departmentid">
                                                            <option selected="" disabled="">Choose...</option>
                                                            <?php foreach($collegedepartment as $collegedepartments){ ?>
                                                            <option value="<?php echo $collegedepartments['id'];?>"><?php echo $collegedepartments['name'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                            </div>
                                            <!--<div class="col-md-4">
                                                <label class="form-label" for="firstname">Year Level</label>
                                                <select name="yearlvl" id="">
                                                    <?php for($i=1; $i<=$collegemaxyearlvl; $i++){?>
                                                        <option value="<?php echo $i;?> ">Year level <?php echo $i;?></option>
                                                    <?php } ?>
                                                    <option value="0">All Year level</option>
                                                </select>
                                            </div>-->

                                            <div class="col-md-6 d-flex justify-content-between align-items-start">

                                                <label class="form-label" for="isexclusive">Room Exclusive to Department</label>
                                                <input type="checkbox" name="isexclusive" id="isexclusive" class="custom-checkbox">

                                            </div>

                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <label class="form-label" for="firstname">Room Name</label>

                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" name="name" required>
                                                    </div>

                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="firstname">Room Type</label>

                                                    <div class="col-md-12">
                                                        <select class="form-select" id="room-type" id="type" required name="type">
                                                            <option selected="" disabled="">Choose...</option>
                                                            <option value="Lec">Lecture</option>
                                                            <option value="Lab">Laboratory</option>
                                                        </select>
                                                    </div>

                                            </div>

                                        </div>

                                        <!--<div class="row mt-3">
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
                                        </div>-->

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
