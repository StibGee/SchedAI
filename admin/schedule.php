<!DOCTYPE html>
<html lang="en">
<?php
    require_once('../include/head.php');
?>

<body>
    <?php
        require_once('../include/nav.php');
        require_once('../classes/db.php');
        require_once('../classes/curriculum.php');
        
        if(isset($_POST['departmentid'])){
            $_SESSION['departmentid'] = $_POST['departmentid'];
        } else {
            $_SESSION['departmentid'] = 1;
        }
        
        $db = new Database();
        $pdo = $db->connect();

        $curriculum = new Curriculum($pdo);
        $calendar = $curriculum->getallcurriculumsschedule();
        $calendardistinct = $curriculum->getdistinctcurriculumsschedule();
    ?>
    <main>
        <div class="container mb-1">
            <div class="row d-flex align-items-center">
                <div class="col-5">
                    <h3><?php echo ($_SESSION['departmentid'] == 1) ? "BSCS" : "BSIT"; ?> Academic Schedules</h3>
                </div>
                <div class="col-3">
                    <form class="mb-0" action="schedule.php" method="POST">
                        <select class="form-select form-select-sm" id="select-classtype" name="departmentid" onchange="this.form.submit()">
                            <option value="1">BSCS</option>
                            <option value="2">IT</option>
                            <option value="" selected>Choose a department</option>
                        </select>
                    </form>
                </div>
                <div class="col-1">
                    <select class="form-select form-select-sm" id="select-classtype">
                        <option>all</option>
                        <option>lec</option>
                        <option>lab</option>
                    </select>
                </div>
                <div class="col-2 d-flex justify-content-end">
                    <button class="button-modal" data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                </div>
            </div>
            <div class="curriculum-sched mt-4">
                <form id="schedule-form" class="mb-0" action="final-sched.php" method="POST">
                    <input type="hidden" name="year" id="year-field">
                    <input type="hidden" name="sem" id="sem-field">
                    <input type="hidden" name="calendarid" id="calendarid-field">
                </form>

                <table class="mb-0 table table-hover">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Semester</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $seenyear = [];
                        foreach ($calendar as $calendars) {
                            if (!in_array($calendars['name'], $seenyear)) {
                                $seenyear[] = $calendars['name'];
                                $displayyear = $calendars['name'];
                            } else {
                                $displayyear = '';
                            }
                        ?>
                            <tr onclick="submitForm('<?php echo htmlspecialchars($calendars['year']); ?>', '<?php echo htmlspecialchars($calendars['sem']); ?>', '<?php echo htmlspecialchars($calendars['id']); ?>')">
                                <th scope="row"><?php echo htmlspecialchars($displayyear); ?></th>
                                <td><?php echo htmlspecialchars($calendars['sem']); ?></td>
                                <td>
                                    <div class="actions">
                                        <i class="fas fa-edit"></i>
                                        <i class="fas fa-trash"></i>
                                        <i class="fas fa-eye"></i>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Form -->
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mt-6" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <form id="formModalForm" action="../processing/scheduleprocessing.php"  method="post">
                            <input type="hidden" name="action" value="add">
                            <div class="rounded-top-3 bg-body-tertiary p-2">
                                <h2 class="head-label">Generate New Schedule</h2>
                                <div class="container mt-4">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group academic-year">
                                                <h5>Select Academic Year</h5>
                                                <input type="text" name="academicyear" class="form-control form-control-sm" style="width: 120px;" value="<?php echo date('Y'); ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group department ">
                                                <h5>Select Department</h5>
                                                <select name="departmentid" class="form-select form-select-sm" id="select-department">
                                                    <option value="1">Computer Science</option>
                                                    <option value="2">Information Technology</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group semester">
                                                <h5>Select Semester</h5>
                                                <select class="form-select form-select-sm" name="semester" id="select-department">
                                                    <option value="1">First Semester</option>
                                                    <option value="2">Second Semester</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6 mt-4">
                                            <label for="">General Subjects Schedule</label>
                                            <div class="load-sub d-flex justify-content-between align-items-center p-1 mt-2">
                                                <label for="" class="mx-3">Set up the provided schedule </label>
                                                <button onclick="window.location.href='general-sub.php'">add</button>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <label for="">*important</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group num-of-section">
                                        <div class="row">
                                            <h5>Student Sections</h5>
                                            <table class="table mx-2">
                                                <thead>
                                                    <tr>
                                                        <th style="border: none;">Year</th>
                                                        <th style="border: none;">Number of Sections</th>
                                                        <th style="border: none;">Select Curriculum</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="border: none;">First Year</td>
                                                        <td style="border: none;">
                                                            <input placeholder="Input No. of Sections" type="number" name="section1" class="form-control form-control-sm" style="width: 200px;">
                                                        </td>
                                                        <td style="border: none;">
                                                            <select class="form-select form-select-sm m-0" name="curriculum1">
                                                                <?php 
                                                                foreach ($calendardistinct as $calendardistincts) {?>
                                                                <option value="<?php echo $calendardistincts['year'];?>"><?php echo $calendardistincts['name'];?></option>
                                                                    
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border: none;">Second Year</td>
                                                        <td style="border: none;">
                                                            <input placeholder="Input No. of Sections" type="number" name="section2" class="form-control form-control-sm" style="width: 200px;">
                                                        </td>
                                                        <td style="border: none;">
                                                        <select class="form-select form-select-sm m-0" name="curriculum2">
                                                                <?php 
                                                                foreach ($calendardistinct as $calendardistincts) {?>
                                                                <option value="<?php echo $calendardistincts['year'];?>"><?php echo $calendardistincts['name'];?></option>
                                                                    
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border: none;">Third Year</td>
                                                        <td style="border: none;">
                                                            <input placeholder="Input No. of Sections" type="number" name="section3" class="form-control form-control-sm" style="width: 200px;">
                                                        </td>
                                                        <td style="border: none;">
                                                        <select class="form-select form-select-sm m-0" name="curriculum3">
                                                                <?php 
                                                                foreach ($calendardistinct as $calendardistincts) {?>
                                                                <option value="<?php echo $calendardistincts['year'];?>"><?php echo $calendardistincts['name'];?></option>
                                                                    
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border: none;">Fourth Year</td>
                                                        <td style="border: none;">
                                                            <input placeholder="Input No. of Sections" type="number" name="section4" class="form-control form-control-sm" style="width: 200px;">
                                                        </td>
                                                        <td style="border: none;">
                                                        <select class="form-select form-select-sm m-0" name="curriculum4">
                                                                <?php 
                                                                foreach ($calendardistinct as $calendardistincts) {?>
                                                                <option value="<?php echo $calendardistincts['year'];?>"><?php echo $calendardistincts['name'];?></option>
                                                                    
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="cancel" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="confirm">Done</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Form for general sub-->
        <div class="modal fade" id="generalsub" tabindex="-1" aria-labelledby="generalsubModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mt-6" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header mb-0">
                        <h4>Set Up General Subject Schedule</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <form >
                            <div class="rounded-top-3 bg-body-tertiary p-2">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group academic-year">
                                            <h5>Select Academic Year</h5>
                                            <input type="text" name="academicyear" class="form-control form-control-sm" style="width: 120px;" value="<?php echo date('Y'); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group department ">
                                            <h5>Select Department</h5>
                                            <select name="departmentid" class="form-select form-select-sm" id="select-department">
                                                <option value="1">Computer Science</option>
                                                <option value="2">Information Technology</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group semester">
                                            <h5>Select Semester</h5>
                                            <select class="form-select form-select-sm" name="semester" id="select-department">
                                                <option value="1">First Semester</option>
                                                <option value="2">Second Semester</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6 mt-4">
                                        <label for="">Genral Subjects Schedule</label>
                                        <div class="load-sub d-flex justify-content-between align-items-center p-1 mt-2">
                                            <label for="" class="mx-3">Set up the provided schedule </label>
                                            <button type="button" class="button-modal" data-bs-toggle="modal" data-bs-target="#generalsub">add</button>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <label for="">*important</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="cancel" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="confirm">Done</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

<link rel="stylesheet" href="../css/main.css">
<link rel="stylesheet" href="../css/sched.css">
<script src="../js/schedule.js"></script>
<script>
    function submitForm(year, sem, calendarid) {
        document.getElementById('year-field').value = year;
        document.getElementById('sem-field').value = sem;
        document.getElementById('calendarid-field').value = calendarid;
        document.getElementById('schedule-form').submit();
    }
    document.querySelector('.btn-close').addEventListener('click', function() {
    console.log('Modal is being closed');
});
</script>
<?php
    require_once('../include/js.php');
?>

</html>

