<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>
<body >

    <?php
        require_once('../include/nav.php');
        require_once('../database/datafetch.php');
        require_once('../classes/db.php');
        require_once('../classes/curriculum.php');
        require_once('../classes/department.php');
        require_once('../classes/college.php');
        require_once('../classes/schedule.php');

        $collegeid=$_SESSION['collegeid'];
        
        $db = new Database();
        $pdo = $db->connect();

        $curriculum = new Curriculum($pdo);
        $college = new College($pdo);
        $department = new Department($pdo);
        $schedule = new Schedule($pdo);
        $collegedepartment = $department->getcollegedepartment($collegeid);
        $initialcollegedepartment = $department->getinitialcollegedepartment($collegeid);
        
        $calendar = $curriculum->getallcurriculumsschedule();
        $calendardistinct = $curriculum->getdistinctcurriculumsschedule();
        $calendardistinctall = $curriculum->getdistinctcurriculumsscheduleall();
        $collegeinfo=$college->getcollegeinfo($collegeid);

    
        
        if(isset($_POST['departmentid'])){
            $_SESSION['departmentid'] = $_POST['departmentid'];
        }elseif(isset($_SESSION['departmentid'])){
            $_SESSION['departmentid']=$_SESSION['departmentid'];
        }else {
            $_SESSION['departmentid'] = $initialcollegedepartment;
        }
        
        $collegemaxyrlvl= $college->getcollegemaxyearlvl($collegeid);
        if($_SESSION['departmentid']!=0){
            $minoryearlvl= $schedule->getdepartmentminoryearlvl($_SESSION['departmentid']);
            $getminorsubjects=$schedule->getminorsubjectsdepartment($_SESSION['departmentid'], $_SESSION['calendarid']);
            $departmentinfo=$department->getdepartmentinfo($_SESSION['departmentid']);
        }else{
            $minoryearlvl= $schedule->getcollegeminoryearlvl($collegeid);
            $getminorsubjects=$schedule->getminorsubjectscollege($collegeid, $_SESSION['calendarid']);
           
        }
       
        $_SESSION['departmentid'];
        
    ?>
    <main>
        <div class="container mb-1">
            <div class="row d-flex align-items-center">
                <div  class="col-4">
                <h5>General Subjects for <?php if ($_SESSION['departmentid'] !=0){ echo $departmentinfo['abbreviation']; }else{ echo $collegeinfo['abbreviation'];}?></h5>
                </div>
            </div>

            <div class="container py-3">
                <div class="row">
                <div class="col-md-3 steps sticky-sidebar r">

                    <div class=" g-3 row year-level d-flex">
                        <div class=" col-6">
                        <label class="form-label " id="year-level-label">First Year</label>
                        </div>

                    </div>
                    <div class="step-indicator mt-3">
                        <?php $last = count($minoryearlvl) - 1;?>
                        <?php foreach ($minoryearlvl AS $collegeminoryearlvls){ ?>
                            <div class="step active">
                                <?php echo $collegeminoryearlvls['minoryearlvl']; ?>
                                <span class="step-label">Year Level <?php echo $collegeminoryearlvls['minoryearlvl']; ?></span>
                            </div>
                        
                        <?php } ?>   
                    </div>
                </div>
                    <div class="col-md-9 scrollable-content">
                        <?php if($_SESSION['departmentid']==0){ ?>
                           
                            <form action="../processing/scheduleprocessing.php" method="POST" id="wizardForm">
                                <input type="text" name="action" value='updateminorcollege' hidden>
                              
                                <input type="number" name="calendarid" value='<?php echo $_SESSION['calendarid'];?>' hidden>
                                <?php foreach ($minoryearlvl AS $index => $collegeminoryearlvls){ ?>
                                    <div class="step-content <?php if ($collegeminoryearlvls['minoryearlvl']==1){echo 'active';}?>" id="step<?php echo $collegeminoryearlvls['minoryearlvl'];?>">
                                        <div class="row mt-3 d-flex justify-content-end my-2 p-3">
                                            <label for="">Set up Schedule for General Subjects</label>
                                            <div class="table-load my-3 p-3">
                                            <?php foreach($collegedepartment AS $collegedepartments){
                                                if(!$schedule->countminorsubjectdepartment($collegedepartments['id'], $_SESSION['calendarid'], $collegeminoryearlvls['minoryearlvl'])){
                                                    continue;}?>
                                                <table id="" class="table table-sm fs-9 mb-0 p-3 text-center" style="table-layout: fixed; width: 100%;">
                                                
                                                    
                                                    <p class="generalsubjects fw-bold mb-0 fs-5"><?php echo $collegedepartments['name']; ?></p>
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 12%;">Sections</th>
                                                            <th style="width: 25%;">Description</th>
                                                            <th style="width: 10%;">Unit</th>
                                                            <th style="width: 23%;">Day</th>
                                                            <th style="width: 15%;">Time Start</th>
                                                            <th style="width: 15%;">Time End</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="loadedSubjects<?php echo $collegeminoryearlvls['minoryearlvl'];?>" class="list">
                                                        <?php foreach($getminorsubjects AS $getminorsubjectscolleges){
                                                            if ($collegedepartments['id']!=$getminorsubjectscolleges['departmentid'] || $getminorsubjectscolleges['yearlvl']!=$collegeminoryearlvls['minoryearlvl']){
                                                                continue;
                                                            }else{?>
                                                                <tr>
                                                                    
                                                                    <td ><?php echo $collegedepartments['abbreviation'].$getminorsubjectscolleges['yearlvl'].$getminorsubjectscolleges['section'];?></td>
                                                                    <td class="text-start"><input type="text" name="subjectscheduleid[]" id="" value="<?php echo $getminorsubjectscolleges['subjectscheduleid'];?>" hidden><?php echo $getminorsubjectscolleges['name'];?></td>
                                                                    <td><?php echo $getminorsubjectscolleges['unit'];?></td>
                                                                    <td class="text-center">
                                                                        
                                                                            <select name="day[]" class="form-select form-select-sm" id="select-classtype">
                                                                                <?php if($getminorsubjectscolleges['unit']==3){?>
                                                                                    <option value="MTh">Monday and Thursday</option>
                                                                                    <option value="TF">Thursday and Friday</option>
                                                                                    <option value="WS">Wednesday and Saturday</option>
                                                                                    <option value="" selected disabled>Please select a day</option>
                                                                                <?php }else{?>
                                                                                    <option value="M">Monday</option>
                                                                                    <option value="T">Tuesday</option>
                                                                                    <option value="W">Wednesday</option>
                                                                                    <option value="Th">Thursday</option>
                                                                                    <option value="F">Friday</option>
                                                                                    <option value="S">Saturday</option>
                                                                                    <option value="" selected disabled>Please select a day</option>
                                                                                <?php } ?>
                                                                            </select>
                                                                       
                                                                    
                                                                       
                                                                    </td>
                                                                    <td><input type="time" name="timestart[]" class="form-control"></td>
                                                                    <td><input type="time" name="timeend[]" class="form-control"></td>
                                                                </tr>
                                                            <?php } ?>
                                                            
                                                            
                                                    
                                                        <?php } ?>
                                                    </tbody>
                                                                                    
                                                </table>
                                                <br>
                                            <?php } ?>
                                        </div>
                                        </div>
                                        <button type="button" class="btn btn-secondary prev-step">Previous</button>
                                        <?php if ($index!=$last){?>
                                            <button type="button" class="btn next-step">Next</button>
                                        <?php }else{ ?>
                                            <button type="submit" class="btn next-step">Submit</button>
                                        <?php } ?>
                                        
                                    </div>
                                <?php } ?>
                                
                            </form>
                    <?php }else{?>
                        <form action="../processing/scheduleprocessing.php" method="POST" id="wizardForm">
                                <input type="text" name="action" value='updateminorcollege' hidden>
                              
                                <input type="number" name="calendarid" value='<?php echo $_SESSION['calendarid'];?>' hidden>
                                <?php foreach ($minoryearlvl AS $index => $collegeminoryearlvls){ ?>
                                    <div class="step-content <?php if ($collegeminoryearlvls['minoryearlvl']==1){echo 'active';}?>" id="step<?php echo $collegeminoryearlvls['minoryearlvl'];?>">
                                        <div class="row mt-3 d-flex justify-content-end my-2 p-3">
                                            <label for="">Set up Schedule for General Subjects</label>
                                            <div class="table-load my-3 p-3">
                                           
                                                <table id="" class="table table-sm fs-9 mb-0 p-3 text-center">
                                                
                                                    
                                                    <p class="generalsubjects fw-bold"><?php echo $departmentinfo['name']; ?></p>
                                                    <thead>
                                                        <tr>
                                                            <th>Sections</th>
                                                            <th>Description</th>
                                                            <th>Unit</th>
                                                            <th>Day</th>
                                                            <th>Time Start</th>
                                                            <th>Time End</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="loadedSubjects<?php echo $collegeminoryearlvls['minoryearlvl'];?>" class="list">
                                                        <?php foreach($getminorsubjects AS $getminorsubjectscolleges){
                                                             echo 'ffff';
                                                            if ($_SESSION['departmentid']!=$getminorsubjectscolleges['departmentid'] || $getminorsubjectscolleges['yearlvl']!=$collegeminoryearlvls['minoryearlvl']){
                                                            
                                                                continue;
                                                            }else{?>
                                                                <tr>
                                                                    
                                                                    <td ><?php echo $departmentinfo['abbreviation'].$getminorsubjectscolleges['yearlvl'].$getminorsubjectscolleges['section'];?></td>
                                                                    <td class="text-start"><input type="text" name="subjectscheduleid[]" id="" value="<?php echo $getminorsubjectscolleges['subjectscheduleid'];?>" hidden><?php echo $getminorsubjectscolleges['name'];?></td>
                                                                    <td><?php echo $getminorsubjectscolleges['unit'];?></td>
                                                                    <td class="text-center">
                                                                        
                                                                            <select name="day[]" class="form-select form-select-sm" id="select-classtype">
                                                                                <?php if($getminorsubjectscolleges['unit']==3){?>
                                                                                    <option value="MTh">Monday and Thursday</option>
                                                                                    <option value="TF">Thursday and Friday</option>
                                                                                    <option value="WS">Wednesday and Saturday</option>
                                                                                    <option value="" selected disabled>Please select a day</option>
                                                                                <?php }else{?>
                                                                                    <option value="M">Monday</option>
                                                                                    <option value="T">Tuesday</option>
                                                                                    <option value="W">Wednesday</option>
                                                                                    <option value="Th">Thursday</option>
                                                                                    <option value="F">Friday</option>
                                                                                    <option value="S">Saturday</option>
                                                                                    <option value="" selected disabled>Please select a day</option>
                                                                                <?php } ?>
                                                                            </select>
                                                                       
                                                                    
                                                                       
                                                                    </td>
                                                                    <td><input type="time" name="timestart[]" class="form-control"></td>
                                                                    <td><input type="time" name="timeend[]" class="form-control"></td>
                                                                </tr>
                                                            <?php } ?>
                                                            
                                                            
                                                    
                                                        <?php } ?>
                                                    </tbody>
                                                    <br>                                
                                                </table>
                                                                                    
                                        </div>
                                        </div>
                                        <button type="button" class="btn btn-secondary prev-step">Previous</button>
                                        <?php if ($index!=$last){?>
                                            <button type="button" class="btn next-step">Next</button>
                                        <?php }else{ ?>
                                            <button type="submit" class="btn next-step">Submit</button>
                                        <?php } ?>
                                        
                                    </div>
                                <?php } ?>
                                
                            </form>
                    <?php } ?>    
                    </div>
                    
                </div>
            </div>

        </div>
    </main>
</body>

    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/allocate.css">
    <script src="../js/main.js"></script>
    <script src="../js/allocate.js"></script>
    <?php
        require_once('../include/js.php');
    ?>

    <script>
        $(document).ready(function() {
            $('#subjects1').DataTable({

                searching: true,
                ordering: true,
                paging: true,
                pageLength: 5,
                lengthChange: false
            });
        });
        $(document).ready(function() {
            $('#subjects2').DataTable({

                searching: true,
                ordering: true,
                paging: true,
                pageLength: 5,
                lengthChange: false
            });
        });
        $(document).ready(function() {
            $('#subjects3').DataTable({

                searching: true,
                ordering: true,
                paging: true,
                pageLength: 5,
                lengthChange: false
            });
        });
        $(document).ready(function() {
            $('#subjects4').DataTable({

                searching: true,
                ordering: true,
                paging: true,
                pageLength: 5,
                lengthChange: false
            });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function handleCheckboxChange(e, tableId, checkboxClass, subjectPrefix) {
            const checkbox = e.target;
            const subjectCode = checkbox.getAttribute(`data-subjectcode${subjectPrefix}`);
            const subjectId = checkbox.getAttribute(`data-subjectid${subjectPrefix}`);
            const isChecked = checkbox.checked;
            const loadedSubjectsTable = document.getElementById(tableId);

            if (isChecked) {
                const subjectName = checkbox.getAttribute(`data-subjectname${subjectPrefix}`);
                const type = checkbox.getAttribute(`data-type${subjectPrefix}`);
                const unit = checkbox.getAttribute(`data-unit${subjectPrefix}`);
                const focus = checkbox.getAttribute(`data-focus${subjectPrefix}`);

                if (!loadedSubjectsTable.querySelector(`tr[data-subjectid${subjectPrefix}="${subjectId}"]`)) {
                    const row = `
                        <tr data-subjectid${subjectPrefix}="${subjectId}" data-subjectcode${subjectPrefix}="${subjectCode}">
                            <td hidden><input type="text" name="subjectid${subjectPrefix}[]" value="${subjectId}" class="form-control"></td>
                            <td>${subjectCode}</td>
                            <td>${subjectName}</td>
                            <td>${type}</td>
                            <td>${unit}</td>
                            <td>${focus}</td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-subject${subjectPrefix}">Remove</button></td>
                        </tr>
                    `;
                    loadedSubjectsTable.insertAdjacentHTML('beforeend', row);
                }

                document.querySelectorAll(`.${checkboxClass}`).forEach(cb => {
                    if (cb.getAttribute(`data-subjectcode${subjectPrefix}`) === subjectCode && cb !== checkbox) {
                        cb.checked = true;
                        handleCheckboxChange({ target: cb }, tableId, checkboxClass, subjectPrefix);
                    }
                });
            } else {

                document.querySelectorAll(`tr[data-subjectcode${subjectPrefix}="${subjectCode}"]`).forEach(row => row.remove());


                document.querySelectorAll(`.${checkboxClass}`).forEach(cb => {
                    if (cb.getAttribute(`data-subjectcode${subjectPrefix}`) === subjectCode) {
                        cb.checked = false;
                    }
                });
            }
        }


        function handleRemoveSubject(e, tableId, checkboxClass, subjectPrefix) {
            const row = e.target.closest('tr');
            const subjectCode = row.getAttribute(`data-subjectcode${subjectPrefix}`);
            const subjectId = row.getAttribute(`data-subjectid${subjectPrefix}`);

            document.querySelectorAll(`#${tableId} tr[data-subjectcode${subjectPrefix}="${subjectCode}"]`).forEach(row => row.remove());

            document.querySelectorAll(`.${checkboxClass}`).forEach(cb => {
                if (cb.getAttribute(`data-subjectcode${subjectPrefix}`) === subjectCode) {
                    cb.checked = false;
                }
            });
        }
        function attachEventListeners(tableSelector, checkboxClass, subjectPrefix, tableId) {
            document.querySelector(tableSelector).addEventListener('change', function(e) {
                if (e.target.classList.contains(checkboxClass)) {
                    handleCheckboxChange(e, tableId, checkboxClass, subjectPrefix);
                }
            });

            document.getElementById(tableId).addEventListener('click', function(e) {
                if (e.target.classList.contains(`remove-subject${subjectPrefix}`)) {
                    handleRemoveSubject(e, tableId, checkboxClass, subjectPrefix);
                }
            });
        }

        attachEventListeners('.table-sub1 tbody', 'load-subject-checkbox1', '1', 'loadedSubjects1');
        attachEventListeners('.table-sub2 tbody', 'load-subject-checkbox2', '2', 'loadedSubjects2');
        attachEventListeners('.table-sub3 tbody', 'load-subject-checkbox3', '3', 'loadedSubjects3');
        attachEventListeners('.table-sub4 tbody', 'load-subject-checkbox4', '4', 'loadedSubjects4');
    });
</script>







</html>