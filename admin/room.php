<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
        $_SESSION['currentpage']='room';
        if (!isset($_GET['room'])){
            $_SESSION['loading']=1;
        }

    ?>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/room.css">
    <script src="../js/main.js"></script>
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
        if ($_SESSION['scheduling']!='college'){
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
            <div class="row mt-2 d-flex align-items-around">
                <div class="col-9">
                    <h3>Rooms</h3>
                </div>

                <div class="col-1 ms-10">
                <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                </div>
            </div>
            <div class =" container mt-3">
                <div class="row d-flex justify-content-around">
                    <div class=" room-table p-4  d-flex justify-content-center">
                        <div class="table-contents">
                        <table id="example" class="table mb-0">
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
                                    <td><?php echo $rooms['abbreviation'];?></td>

                                    <td>

                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editroom<?php echo $rooms['roomid']; ?>" onclick="event.stopPropagation();">
                                            <i class="fas fa-edit"></i>
                                        </button>


                                        <form action="../processing/roomprocessing.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this room?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $rooms['roomid']; ?>">
                                            <button type="submit" class="btn btn-danger" onclick="event.stopPropagation();">
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
                                                                                <option disabled>Choose...</option>
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
                                                                            <input type="text" class="form-control" name="name" required value="<?php echo $rooms['roomname'];?>" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-6">
                                                                        <label class="form-label" for="firstname">Room Type</label>
                                                                        <div class="col-md-12">
                                                                            <select class="form-select" id="room-type" name="type" required>
                                                                                <option disabled>Choose...</option>
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
                                    <form id="facultyForm" class="needs-validation" novalidation method="POST"  action="../processing/roomprocessing.php" class="row g-3 mt-4">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="collegeid" value="<?php echo $collegeid;?>">
                                    <h5>Room Details</h5>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                                <label class="form-label" for="firstname">Department</label>

                                                    <div class="col-md-12">
                                                        <select class="form-select" id="room-type" required name="departmentid" required>
                                                            <option disabled>Choose...</option>
                                                            <?php foreach($collegedepartment as $collegedepartments){ ?>
                                                            <option value="<?php echo $collegedepartments['id'];?>"><?php echo $collegedepartments['name'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please select a department.
                                                        </div>
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



                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <label class="form-label" for="firstname">Room Name</label>

                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" name="name" required>
                                                        <div class="invalid-feedback">
                                                            Please enter a room name.
                                                        </div>
                                                    </div>

                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="firstname">Room Type</label>

                                                    <div class="col-md-12">
                                                        <select class="form-select" id="room-type" id="type" required name="type" >
                                                            <option disabled>Choose...</option>
                                                            <option value="Lec">Lecture</option>
                                                            <option value="Lab">Laboratory</option>
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please select a room type.
                                                        </div>
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


    <?php
        require_once('../include/js.php')
    ?>

</html>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "lengthChange": false
        });
    });
</script>
<script>
    (() => {
      'use strict';

      const forms = document.querySelectorAll('.needs-validation');
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    })();
  </script>

