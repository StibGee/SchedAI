<!DOCTYPE html>
<html lang="en">
<body>

    <?php
        require_once('../include/nav.php');
        require_once('../classes/subject.php');
        require_once('../classes/db.php');
        require_once('../classes/department.php');
        $db = new Database();
        $pdo = $db->connect();

        $subject = new Subject($pdo);
        $department = new Department($pdo);


        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_POST['academicplanyearlvl'])){
            $yearlvl= htmlspecialchars($_POST['academicplanyearlvl']);
            $_SESSION['yearlvl']=$yearlvl;
        }elseif(isset($_SESSION['yearlvl'])){
            $yearlvl= $_SESSION['yearlvl'];
        }else{
            $yearlvl=1;
            $_SESSION['yearlvl']=1;

        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['academicplanyear']) && isset($_POST['academicplansem']) && isset($_POST['academicplancalendarid'])) {
                $year = htmlspecialchars($_POST['academicplanyear']);
                $_SESSION['year']=$year;
                $sem = htmlspecialchars($_POST['academicplansem']);
                $_SESSION['sem']=$sem;

                $calendarid = htmlspecialchars($_POST['academicplancalendarid']);
                $_SESSION['calendarid']=$calendarid;


            }else {
                $calendarid=$_SESSION['calendarid'];
                $sem=$_SESSION['sem'];
                $year=$_SESSION['year'];

            }

        } else {
            $year=$_SESSION['year'];
            $sem=$_SESSION['sem'];
            $calendarid = $_SESSION['calendarid'];
            $departmentid = $_SESSION['departmentidbasis'];
            $yearlvl=$_SESSION['yearlvl'];
        }

        if ((isset($_SESSION['departmentid']) && $_SESSION['departmentid']!=0)){
            $departmentid = htmlspecialchars($_SESSION['departmentid']);
        }elseif(isset($_SESSION['departmentidbasis']) && $_SESSION['departmentidbasis']!=0){
            $departmentid=$_SESSION['departmentidbasis'];
        }



        $filteredsubject = $subject->filteredsubjects($calendarid, $departmentid, $yearlvl);
        $departmentinfo = $department->getdepartmentinfo($departmentid);

        $message = '';

        if (isset($_GET['subject'])) {
            if ($_GET['subject'] == 'added') {
                $message = 'Subject added successfully!';
            } elseif ($_GET['subject'] == 'edited') {
                $message = 'Subject edited successfully!';
            }else{
                $message = 'Subject deleted successfully!';
            }
        }
    ?>

<main>
    <div class="mb-1">
        <div class="container py-3">
            <div class="row d-flex justify-content-end align-items-center">
                <div class="col-6">
                    <div class="toast-container position-fixed top-0.5 start-50 translate-middle-x p-3">
                        <div id="subjectToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true"
                            <?php echo $message ? 'style="display: block; background-color: #28a745; color: white; padding: 0.3rem 0.5rem; font-size: 1.1rem; width: 400px; border-radius: 10px;"' : 'style="display: none;"'; ?>>
                            <div class="toast-body" id="toastBody">
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        </div>
                    </div>
                    <h5>
                        <button class="button" onclick="window.location.href='academic-plan.php'">
                            <i class="fa-solid fa-circle-arrow-left"></i>
                        </button>
                        Academic Plan for <span><?php echo $departmentinfo['name'];?> <?php if ($sem==1){echo $sem.'st Sem S.Y '.$year;}else{echo $sem.'nd Sem S.Y '.$year;};?></span>
                    </h5>
                </div>
                <div class="col-1">
                    <select class="form-select  form-select-sm " id="select-position">
                        <option>all</option>
                        <option>Lec</option>
                        <option>Lab</option>
                    </select>
                </div>

                <div class="searchbar col-4 ">
                    <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                </div>
                <div class="col-1 d-flex align-items-center justify-content-start">
                    <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                </div>
            </div>
            <div class="row">
                <div class="col-3 col-md-3 steps fixed-sidebar">
                    <h5>Year Level</h5>
                    <div class="navs d-flex align-items-center mt-3 text-center">
                        <?php for ($i = 1; $i <= $departmentinfo['yearlvl']; $i++) { ?>
                            <form action="academicplan-view.php" method="POST">
                                <input type="hidden" name="academicplanyearlvl" value="<?php echo $i;?>">
                                <button type="submit" class="<?php if ($i==$yearlvl){echo 'currentyearlvl';}?>">Year Level <?php echo $i;?></button>
                            </form>
                        <?php } ?>


                    </div>
                </div>
                <div class="col-md-9 scrollable-content">
                        <div class="row">
                            <label for="">First Year Subjects Loaded</label>
                            <div class="table-load my-3 p-3">
                                <table id="" class="table table-sm fs-9 mb-0 p-3">
                                    <thead>
                                        <tr>
                                            <th data-sort="subcode">Code</th>
                                            <th data-sort="desc">Description</th>
                                            <th data-sort="desc">Type</th>
                                            <th data-sort="desc">Unit</th>
                                            <th data-sort="desc">Time</th>
                                            <th data-sort="desc">Focus</th>
                                            <th data-sort="desc">Action</th>

                                        </tr>
                                    </thead>
                                    <tbody id="loadedSubjects1" class="list">
                                    <?php if (!empty($filteredsubject)) { ?>
                                        <?php foreach ($filteredsubject as $filteredsubjects) { ?>
                                            <tr>
                                                <td><?= htmlspecialchars($filteredsubjects['subjectcode']) ?></td>
                                                <td><?= htmlspecialchars($filteredsubjects['subjectname']) ?></td>
                                                <td><?= htmlspecialchars($filteredsubjects['type']) ?></td>
                                                <td><?= htmlspecialchars($filteredsubjects['unit']) ?></td>
                                                <td><?= htmlspecialchars($filteredsubjects['hours']) ?></td>
                                                <td><?= htmlspecialchars($filteredsubjects['focus']) ?></td>
                                                <td>
                                                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#editModal<?= htmlspecialchars($filteredsubjects['id']) ?>" onclick="event.stopPropagation();" style="background: none; border: none; padding: 0;">
                                                        <i class="fas fa-edit"></i> <!-- Edit icon -->
                                                    </button>

                                                    <form action="../processing/subjectprocessing.php" method="post" style="display:inline;">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?= htmlspecialchars($filteredsubjects['id']) ?>">
                                                        <button type="submit" class="btn" onclick="return confirm('Are you sure you want to delete this subject?');" style="background: none; border: none; padding: 0;">
                                                            <i class="fas fa-trash-alt"></i> <!-- Delete icon -->
                                                        </button>
                                                    </form>

                                                </td>
                                            </tr>
                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editModal<?= htmlspecialchars($filteredsubjects['id']) ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg mt-6">
                                                    <div class="modal-content border-0">
                                                        <div class="modal-body p-4">
                                                            <div class="position-absolute top-0 end-0 mt-3 me-3 z-1">
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <h2 class="head-label">Edit Subject</h2>
                                                            <form action="../processing/subjectprocessing.php" method="POST" class="row g-3 mt-4 needs-validation" novalidate>
                                                                <input type="hidden" name="action" value="updatesubject">
                                                                <input type="hidden" name="subjectid" value="<?= htmlspecialchars($filteredsubjects['id']) ?>">

                                                                <h5>Subject Information</h5>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label" for="subcode">Subject Code</label>
                                                                        <input class="form-control" id="subcode" type="text" name="subjectcode" value="<?= htmlspecialchars($filteredsubjects['subjectcode']) ?>" required />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label" for="subname">Subject Name</label>
                                                                        <input class="form-control" id="subname" type="text" name="subjectname" value="<?= htmlspecialchars(str_replace('LAB', '', $filteredsubjects['subjectname'])) ?>" required />
                                                                    </div>

                                                                </div>

                                                                <h5>Subject Details</h5>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label" for="type">Type</label>
                                                                        <select class="form-select type-select" id="type" name="type" required>
                                                                            <option selected disabled value="">Choose...</option>
                                                                            <option value="Lec" <?= htmlspecialchars($filteredsubjects['type']) == 'Lec' ? 'selected' : '' ?>>Lec</option>
                                                                            <option value="Lab" <?= htmlspecialchars($filteredsubjects['type']) == 'Lab' ? 'selected' : '' ?>>Lab</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label" for="unit">Unit</label>
                                                                        <select class="form-select unit-select" id="unit" name="unit" required>
                                                                            <option selected disabled value="">Choose...</option>
                                                                            <?php if ($filteredsubjects['type'] == 'Lec'): ?>
                                                                                <option value="3.0" <?= htmlspecialchars($filteredsubjects['unit']) == '3.0' ? 'selected' : '' ?>>3.0</option>
                                                                                <option value="2.0" <?= htmlspecialchars($filteredsubjects['unit']) == '2.0' ? 'selected' : '' ?>>2.0</option>

                                                                                <option value="1.0" <?= htmlspecialchars($filteredsubjects['unit']) == '1.0' ? 'selected' : '' ?>>1.0</option>
                                                                            <?php elseif ($filteredsubjects['type'] == 'Lab'): ?>
                                                                                <option value="1.0" <?= htmlspecialchars($filteredsubjects['unit']) == '1.0' ? 'selected' : '' ?>>1.0</option>
                                                                            <?php endif; ?>

                                                                        </select>
                                                                    </div>

                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label" for="hours">Hours</label>
                                                                        <input class="form-control hours-input" id="hoursedit" type="text" name="hours" readonly value="<?= htmlspecialchars($filteredsubjects['hours']) ?>" required />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label" for="focus">Program Focus</label>
                                                                        <select class="form-select" id="focus" name="focus" required>
                                                                            <option selected disabled value="">Choose...</option>
                                                                            <option value="Major" <?= htmlspecialchars($filteredsubjects['focus']) == 'Major' ? 'selected' : '' ?>>Major</option>
                                                                            <option value="Minor" <?= htmlspecialchars($filteredsubjects['focus']) == 'Minor' ? 'selected' : '' ?>>Minor</option>
                                                                            <option value="Major1" <?= htmlspecialchars($filteredsubjects['focus']) == 'Major1' ? 'selected' : '' ?>>Major (no schedule)</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label" for="focus">Require Masters</label>
                                                                        <input class="form-check-input" type="checkbox" id="" name="masters" <?= htmlspecialchars($filteredsubjects['masters']) == 'Yes' ? 'checked' : '' ?>>
                                                                    </div>
                                                                </div>

                                                                <div class="row mt-3 labdetails">
                                                                    <h5>Lab Details</h5>
                                                                    <div class="col-md-6">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="" name="labroom" <?= htmlspecialchars($filteredsubjects['type']) == 'Lab' ? 'checked' : '' ?>>
                                                                            <label class="form-check-label" for="">Requires Lab Room</label>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="modal-footer d-flex justify-content-between">
                                                                    <button type="button" class="cancel" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="confirm">Update Subject</button>
                                                                </div>

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="7">No subjects found.</td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
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
                            <h2 class="head-label">Add Subject</h2>
                            <div class="container form ">
                                <form id="facultyForm" class="row g-3 mt-4 needs-validation" action="../processing/subjectprocessing.php" method="POST" novalidate="">
                                    <input type="text" value='add' name="action" hidden>
                                    <input type="text" value='<?php echo $calendarid;?>' name="calendarid" hidden>
                                    <input type="text" value='<?php echo $departmentid;?>' name="departmentid" hidden>
                                    <input type="text" value='<?php echo $yearlvl;?>' name="yearlvl" hidden>
                                    <input value="add" name="action" hidden>
                                    <!--<h5>Department</h5>
                                    <div class="row ">
                                        <div class="col-md-6">
                                        <label for="">Select Department</label>
                                            <select class="form-select" id="department" name="departmentid" required="">
                                                <option selected="" disabled="" value="">Choose...</option>
                                                <?php foreach($department as $departments){ ?>
                                                <option value="<?php echo $departments['id'];?>"><?php echo $departments['name'];?></option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                    </div>-->
                                    <h5>Subject Information</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label" for="subcode">Subject Code</label>
                                            <input class="form-control" id="subcode" type="text" name="subjectcode" required />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="subname">Subject Name</label>
                                            <input class="form-control" id="subname" type="text" name="subjectname" required />
                                        </div>
                                    </div>
                                    <h5>Subject Details</h5>
                                    <!--<div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label" for="yearlvl">Year Level</label>
                                            <select class="form-select" id="yearlvl" required="">
                                                <option selected="" disabled="" value="">Choose...</option>
                                                <option>1st year</option>
                                                <option>2nd year</option>
                                                <option>3rd year</option>
                                                <option>4th year</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="offered">Offered</label>
                                            <select class="form-select" id="offered" required="">
                                                <option selected="" disabled="" value="">Choose...</option>
                                                <option>1st semester</option>
                                                <option>2nd semester</option>
                                            </select>
                                        </div>
                                    </div>-->
                                    <div class="row ml-5 ">
                                        <div class="col-6">
                                            <div class="form-check ">
                                                <div class="row mt-3 ">
                                                        <div class="col-md-2">
                                                            <h5>Lec <input class="form-check-input " type="checkbox" id="checkbox-3" name="lec" data-bulk-select-row="data-bulk-select-row" /></h5>
                                                        </div>
                                                        <div class="" id="lec-section">
                                                            <div class="col-md-5">
                                                                <label class="form-label" for="unit">Unit</label>
                                                                <select class="form-select" id="unit" required="" name="lecunit">
                                                                    <option selected="" disabled="" value="">Choose...</option>
                                                                    <option value="3.0">3.0</option>
                                                                    <option value="2.0">2.0</option>
                                                                    <option value="1.0">1.0</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label" for="subname">Subject hours</label>
                                                                <input class="form-control" id="subhours" type="text" readonly>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="row mt-3">
                                                        <div class="col-md-2">
                                                            <h5>Lab <input class="form-check-input " type="checkbox" id="checkbox-1" name="lab" data-bulk-select-row="data-bulk-select-row" /></h5>
                                                        </div>
                                                        <div class="" id="lab-section">
                                                            <div class="col-md-5" >
                                                                <label class="form-label" for="unit">Unit</label>
                                                                <select class="form-select" id="unit" required="" name="labunit">
                                                                    <option selected="" disabled="" value="">Choose...</option>
                                                                    <option selected value="1.0">1.0</option>

                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label" for="subname">Subject hours</label>
                                                                <input class="form-control" id="subhourslab" type="text" readonly>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="" name="labroom">
                                                                <label class="form-check-label" for="">Requires Lab Room</label>
                                                            </div>
                                                        </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-6 mt-3">
                                            <div class="row">
                                                <label class="form-label" for="subname">Program Focus</label>
                                                <select class="form-select" id="department" required="" name="focus">
                                                    <option selected="" disabled="" value="">Choose...</option>
                                                    <option>Major</option>
                                                    <option>Minor</option>
                                                    <option value="Major1">Major<p>(no schedule)</p></option>
                                                    <option value="OJT">Major<p>(Immersion)</p></option>
                                                </select>
                                                <div class="row mt-3">
                                                    <label for="">Mark check if the Subject requires <span>Masters</span></label>
                                                    <h5 class="mt-3 "> <input class="form-check-input " type="checkbox" id="" name="masters" data-bulk-select-row="data-bulk-select-row" /> Required</h5>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">


                                        </div>
                                    </div>

                            </div>
                        </div>
                <div class="modal-footer d-flex justify-content-between">

                    <button type="button" class="cancel" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="submit" class="confirm">Done</button>

                </div>
            </form>
        </div>


    </main>
</body>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const labSection = document.getElementById("lab-section");
            const lecSection = document.getElementById("lec-section");
            const labCheckbox = document.getElementById("checkbox-1");
            const lecCheckbox = document.getElementById("checkbox-3");
            const lecUnitSelect = document.querySelector("select[name='lecunit']");
            const labUnitSelect = document.querySelector("select[name='labunit']");
            const lecHoursInput = document.getElementById("subhours");
            const labHoursInput = document.getElementById("subhourslab");

            labSection.style.display = "none";
            lecSection.style.display = "none";

            labCheckbox.addEventListener("change", function () {
                if (this.checked) {
                    labSection.style.display = "flex";
                    labHoursInput.value = "3 hrs";
                } else {
                    labSection.style.display = "none";
                    labHoursInput.value = "";
                }
            });

            lecCheckbox.addEventListener("change", function () {
                if (this.checked) {
                    lecSection.style.display = "flex";
                    lecHoursInput.value = "";
                } else {
                    lecSection.style.display = "none";
                    lecHoursInput.value = "";
                }
            });

            lecUnitSelect.addEventListener("change", function () {
                const lecUnitValue = parseFloat(this.value);
                if (lecUnitValue === 3.0) {
                    lecHoursInput.value = "1.5 hrs 2 days";
                } else if (lecUnitValue === 2.0) {
                    lecHoursInput.value = "2 hrs";
                } else {
                    lecHoursInput.value = "3 hrs";
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function updateUnitOptions(modal) {
                const typeSelect = modal.querySelector(".type-select");
                const unitSelect = modal.querySelector(".unit-select");
                const hoursInput = modal.querySelector(".hours-input");
                const labDetails = modal.querySelector(".labdetails");

                const selectedType = typeSelect.value;
                const previousUnit = unitSelect.value;

                unitSelect.innerHTML = '<option selected disabled value="">Choose...</option>';

                if (selectedType === 'Lab') {
                    labDetails.style.display = 'block';
                } else {
                    labDetails.style.display = 'none';
                }

                if (selectedType === 'Lec') {
                    const lecOptions = [
                        { value: "3.0", text: "3.0" },
                        { value: "2.0", text: "2.0" },
                        { value: "1.0", text: "1.0" }
                    ];

                    lecOptions.forEach(option => {
                        const newOption = document.createElement("option");
                        newOption.value = option.value;
                        newOption.textContent = option.text;
                        unitSelect.appendChild(newOption);
                    });
                } else if (selectedType === 'Lab') {
                    const labOptions = [
                        { value: "1.0", text: "1.0" }
                    ];

                    labOptions.forEach(option => {
                        const newOption = document.createElement("option");
                        newOption.value = option.value;
                        newOption.textContent = option.text;
                        unitSelect.appendChild(newOption);
                    });
                }

                if (previousUnit) {
                    const optionToSelect = Array.from(unitSelect.options).find(option => option.value === previousUnit);
                    if (optionToSelect) {
                        unitSelect.value = previousUnit;
                    } else {
                        unitSelect.value = '';
                    }
                }
            }

            function updateHours(modal) {
                const typeSelect = modal.querySelector(".type-select");
                const unitSelect = modal.querySelector(".unit-select");
                const hoursInput = modal.querySelector(".hours-input");

                const selectedType = typeSelect.value;
                const selectedUnit = unitSelect.value;

                if (selectedType === 'Lec') {
                    switch (selectedUnit) {
                        case "3.0":
                            hoursInput.value = "1.5 hrs, 2 days";
                            break;
                        case "2.0":
                            hoursInput.value = "2 hrs";
                            break;
                        case "1.0":
                            hoursInput.value = "3 hrs";
                            break;
                        default:
                            hoursInput.value = "";
                            break;
                    }
                } else if (selectedType === 'Lab' && selectedUnit === "1.0") {
                    hoursInput.value = "3 hrs";
                } else {
                    hoursInput.value = "";
                }
            }

            document.querySelectorAll(".modal").forEach(modal => {
                const typeSelect = modal.querySelector(".type-select");
                const unitSelect = modal.querySelector(".unit-select");
                const hoursInput = modal.querySelector(".hours-input");
                const labDetails = modal.querySelector(".labdetails");

                if (typeSelect) {
                    typeSelect.addEventListener("change", function () {
                        updateUnitOptions(modal);
                        updateHours(modal);
                    });
                }

                if (unitSelect) {
                    unitSelect.addEventListener("change", function () {
                        updateHours(modal);
                    });
                }


                updateUnitOptions(modal);
                updateHours(modal);
            });
        });
    </script>
    <script>
        window.onload = function() {
            const message = "<?php echo addslashes($message); ?>";
            if (message) {
                const toast = new bootstrap.Toast(document.getElementById('subjectToast'));
                toast.show();
            }
        };
    </script>

    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/academic-view.css">


<style>

</style>
</html>
