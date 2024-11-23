<!DOCTYPE html>
<html lang="en">




    <?php

    require_once('../include/user-nav.php');

        require_once('../classes/subject.php');
        require_once('../classes/db.php');
        require_once('../classes/department.php');
        require_once('../classes/faculty.php');

        $db = new Database();
        $pdo = $db->connect();

        $subject = new Subject($pdo);
        $department = new Department($pdo);
        $faculty = new Faculty($pdo);

        if (isset($_POST['facultyid'])){
            $facultyid=$_POST['facultyid'];
        }else{
            $facultyid=$_SESSION['id'];
        }
        $collegedepartment = $department->getcollegedepartment($_SESSION['collegeid']);
        $distinctsubjects = $subject->getdistinctsubjectsdepartment($_SESSION['departmentid']);
        $facultyinfo = $faculty->getfacultyinfo($facultyid);
        $existingsubjects = $faculty->getfacultysubjects($facultyid);
        $departmentinfo = $department->getdepartmentinfo($_SESSION['departmentid']);
        $facultydaytime=$faculty->getfacultydaytime($facultyid);
        $mondaychecked=$tuesdaychecked=$wednesdaychecked=$thursdaychecked=$fridaychecked=$saturdaychecked='';

        foreach($facultydaytime as $facultydaytimes){

            if($facultydaytimes['day']==1){
                $mondaychecked='checked';
                $mondaystarttime=$facultydaytimes['starttime'];
                $mondayendtime=$facultydaytimes['endtime'];
            }
            if($facultydaytimes['day']==2){
                $tuesdaychecked='checked';
                $tuesdaystarttime=$facultydaytimes['starttime'];
                $tuesdayendtime=$facultydaytimes['endtime'];
            }

            if($facultydaytimes['day']==3){
                $wednesdaychecked='checked';
                $wednesdaystarttime=$facultydaytimes['starttime'];
                $wednesdayendtime=$facultydaytimes['endtime'];
            }
            if($facultydaytimes['day']==4){
                $thursdaychecked='checked';
                $thursdaystarttime=$facultydaytimes['starttime'];
                $thursdayendtime=$facultydaytimes['endtime'];
            }
            if($facultydaytimes['day']==5){
                $fridaychecked='checked';
                $fridaystarttime=$facultydaytimes['starttime'];
                $fridayendtime=$facultydaytimes['endtime'];
            }
            if($facultydaytimes['day']==6){
                $saturdaychecked='checked';
                $saturdaystarttime=$facultydaytimes['starttime'];
                $saturdayendtime=$facultydaytimes['endtime'];
            }

        }

    ?>

    <main>
            <!-- NavBar -->

    <div class="container py-2">
        <div class="row wizard">
            <div class="g-3 row year-level">
                <h5>Complete Your Profile</h5>
            </div>
            <div class="col-12 col-md-3 steps sticky-sidebar">
                <div class="step-indicator d-flex justify-content-between mt-3">
                    <div class="step active">
                        1
                        <span class="step-label">Personal Information</span>
                    </div>
                    <div class="step">
                        2
                        <span class="step-label">Faculty Information</span>
                    </div>
                    <div class="step">
                        3
                        <span class="step-label">Teaching Details</span>
                    </div>
                    <div class="step">
                        4
                        <span class="step-label">Professional Qualification</span>
                    </div>
                    <div class="step">
                        5
                        <span class="step-label">Preferences</span>
                    </div>
                </div>
                <div class="mt-3 logo-display">
                    <img src="../img/logo/Sched-logo1.png" width="300">
                </div>
            </div>

            <div class="col-md-9 scrollable-content mt-4">
                <form id="wizardForm" method="POST" action="../processing/facultyprocessing.php">
                    <input type="hidden" name="action" value="editprofiling">
                    <input type="number" name="facultyid" value="<?php echo $facultyinfo['id'];?>" hidden>
                    <div class="step-content active p-4" id="step1">
                        <h5>Personal Information</h5>
                        <?php if($facultyinfo){ ?>

                        <div class="row mt-2">
                            <div class="col-md-5">
                                <label class="form-label" for="firstname">First name</label>
                                <input class="form-control nonumber" id="firstname" type="text" name="fname" value="<?php if(isset($facultyinfo['fname'])){ echo $facultyinfo['fname'];} ?>" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label" for="lastname">Last name</label>
                                <input class="form-control nonumber" id="lastname" type="text" name="lname" value="<?php if(isset($facultyinfo['lname'])){ echo $facultyinfo['lname'];} ?>" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="midleinit">M.I (optional)</label>
                                <input class="form-control nonumber" id="midleinit" type="text" name="mname" value="<?php if(isset($facultyinfo['mname'])){ echo $facultyinfo['mname'];} ?>"  />
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-5">
                                <label class="form-label" for="contactnumber">Contact Number</label>
                                <input class="form-control" id="contactnumber" name="contactno" type="number" value="<?php if(isset($facultyinfo['contactno'])){ echo $facultyinfo['contactno'];} ?>" required />
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="birthdate">Email</label>
                                <input class="form-control" id="birthdate" name="email" type="email" value="<?php if(isset($facultyinfo['email'])){ echo $facultyinfo['email'];} ?>"required />
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="gender">Gender</label>
                                <select class="form-select" id="gender" name="gender" required="" required>
                                    <option selected="" disabled="">Choose...</option>
                                    <option <?php if(isset($facultyinfo['gender']) && $facultyinfo['gender'] == 'Male'){ echo 'selected'; } ?> value="Male">Male</option>
                                    <option <?php if(isset($facultyinfo['gender']) && $facultyinfo['gender'] == 'Female'){ echo 'selected'; } ?> value="Female">Female</option>
                                </select>
                                <div class="invalid-feedback">Please select a Gender</div>
                            </div>
                        </div>
                        <div class="form-footer mt-4 d-flex justify-content-end">
                            <button type="button" class="btn  next-step">Next</button>
                        </div>
                    </div>
                    <div class="step-content p-4" id="step2">
                    <h5>Faculty Information</h5>
                        <div class="row mt-2">
                            <div class="col-6">
                                <label class="form-label" for="position">Type</label>
                                <select class="form-select" id="position" name="type" required>
                                    <option selected="" disabled="" value="">Choose...</option>
                                    <option <?php if(isset($facultyinfo['type']) && $facultyinfo['type'] == 'Regular'){ echo 'selected'; } ?> value="Regular">Regular </option>
                                    <option <?php if(isset($facultyinfo['type']) && $facultyinfo['type'] == 'Contractual'){ echo 'selected'; } ?> value="Contractual">Contractual</option>
                                </select>
                                <div class="invalid-feedback">Please select a type</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="position">Department</label>
                                <input class="form-control" id="startdate" type="number" name="departmentidpost" value="<?php echo $_SESSION['departmentid'];?>" hidden>
                                <input class="form-control" id="startdate" type="text" name="departmentname" value="<?php echo $departmentinfo['abbreviation'];?>" readonly>
                            </div>
                            

                        </div>
                        <div class="row mt-2">
                            <div class="col-6">
                                <label class="form-label" for="startdate">Start Date</label>
                                <input class="form-control" id="startdate" type="date" name="startdate" value="<?php if(isset($facultyinfo['startdate'])){ echo $facultyinfo['startdate'];} ?>" required />
                                <div class="valid-feedback">Looks good!</div>
                            </div>

                            <div class="col-6">
                                <label class="form-label" for="teachinghours">Teaching Hours</label>
                                <input class="form-control" id="teachinghours" type="number" name="teachinghours" value="<?php if(isset($facultyinfo['teachinghours'])){ echo $facultyinfo['teachinghours'];} ?>" required placeholder="Hours/Week" />
                            </div>
                        </div>

                        <div class="form-footer mt-5 d-flex justify-content-between">
                            <button type="button" class="btn prev-step">Previous</button>
                            <button type="button" class="btn  next-step">Next</button>

                        </div>
                    </div>
                    <?php } ?>
                    <div class="step-content p-4" id="step3">
    <h5>Teaching Details</h5>

    <!-- Teaching Specialization Section -->
    <div class="container">
        <label for="" class="form-label">Teaching Specialization</label>
        <div class="wrap p-3 ">
            <div class="table-responsive">
                <table class="table table-sm fs-9 mb-0 text-center">
                    <thead>
                        <tr>
                            <th data-sort="subcode">Subject Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="loadedSubjects1" class="list">
                        <!-- Dynamic content here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Preferred Subject Selection Section -->
        <label for="" class="form-label">Preferred Subject Selection</label>
        <div class="wrap p-3 m-3">
            <div class="table-sub1 table-sub my-3 p-3">
                <div class="table-responsive">
                    <table id="subjects1" class="table table-sm fs-9 mb-0">
                        <thead>
                            <tr>
                                <th data-sort="desc">Subject Name</th>
                                <th>Select</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php foreach ($distinctsubjects as $subjects): ?>
                                <?php
                                    $checked = '';
                                    foreach ($existingsubjects as $existingsubject) {
                                        if ($existingsubject['subjectname'] == $subjects['name']) {
                                            $checked = 'checked';
                                            break;
                                        }
                                    }
                                ?>
                                <tr>
                                    <td class="align-middle desc"><?php echo htmlspecialchars($subjects['name']); ?></td>
                                    <td class="align-middle">
                                        <input type="checkbox" class="form-check-input load-subject-checkbox1"
                                            data-subjectname1="<?php echo htmlspecialchars($subjects['name']); ?>" <?php echo $checked; ?>>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer with Navigation Buttons -->
    <div class="form-footer mt-4 d-flex justify-content-between">
        <button type="button" class="btn prev-step">Previous</button>
        <button type="button" class="btn next-step">Next</button>
    </div>
</div>

                    <div class="step-content p-4" id="step4">
                        <h5>Professional  Qualifications</h5>
                        <div class="row">
                            <div class=" my-2 p-3 ">
                                <label class="form-label" for="degree">Highest Degree Obtained</label>
                                <select class="form-select" name="highestdegree" id="degree" name="degree" required>
                                    <option selected disabled value="">Choose...</option>
                                    <option value="Doctorate" <?php if($facultyinfo['rank']=='Doctorate'){ echo 'selected';}?>>Doctorate Degree</option>
                                    <option value="Masters" <?php if($facultyinfo['rank']=='Masters'){ echo 'selected';}?>>Masters Degree</option>
                                    <option value="Bachelors" <?php if($facultyinfo['rank']=='Bachelors'){ echo 'selected';}?>>Bachelors Degree</option>
                                </select>
                            </div>
                            <!--<div class="table-load my-2 p-3 col-6" id="specialization-container" style="display: none;">
                                <label class="form-label" for="specialization">Specialization</label>
                                <select class="form-select" id="specialization" name="specialization" required>
                                    <option selected disabled value="">Choose...</option>
                                </select>
                            </div>-->
                        </div>

                        <div class="form-footer mt-4 d-flex justify-content-between">
                            <button type="button" class="btn prev-step">Previous</button>
                            <button type="button" class="btn  next-step">Next</button>
                        </div>
                    </div>
                    <div class="step-content p-4" id="step5">
                        <h5>Preferences</h5>
                        <div class="prefer-table p-3 m-3">
                            <table class="table table-sm fs-9 mb-0 ">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Day</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" name="monday" <?php echo $mondaychecked;?>></td>
                                            <td>Monday</td>
                                            <td><input type="time" name="mondaystartTime" value="<?php if($mondaystarttime){echo $mondaystarttime;}?>"></td>
                                            <td><input type="time" name="mondayendTime" value="<?php if($mondayendtime){echo $mondayendtime;}?>"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="tuesday" <?php echo $tuesdaychecked;?>></td>
                                            <td>Tuesday</td>
                                            <td><input type="time" name="tuesdaystartTime" value="<?php if($tuesdaystarttime){echo $tuesdaystarttime;}?>"></td>
                                            <td><input type="time" name="tuesdayendTime" value="<?php if($tuesdayendtime){echo $tuesdayendtime;}?>"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="wednesday" <?php echo $wednesdaychecked;?>></td>
                                            <td>Wednesday</td>
                                            <td><input type="time" name="wednesdaystartTime" value="<?php if($wednesdaystarttime){echo $wednesdaystarttime;}?>"></td>
                                            <td><input type="time" name="wednesdayendTime" value="<?php if($wednesdayendtime){echo $wednesdayendtime;}?>"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="thursday" <?php echo $thursdaychecked;?>></td>
                                            <td>Thursday</td>
                                            <td><input type="time" name="thursdaystartTime" value="<?php if($thursdaystarttime){echo $thursdaystarttime;}?>"></td>
                                            <td><input type="time" name="thursdayendTime" value="<?php if($thursdayendtime){echo $thursdayendtime;}?>"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="friday" <?php echo $fridaychecked;?>></td>
                                            <td>Friday</td>
                                            <td><input type="time" name="fridaystartTime" value="<?php if($fridaystarttime){echo $fridaystarttime;}?>"></td>
                                            <td><input type="time" name="fridayendTime" value="<?php if($fridayendtime){echo $fridayendtime;}?>"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="saturday" <?php echo $saturdaychecked;?>></td>
                                            <td>Saturday</td>
                                            <td><input type="time" name="saturdaystartTime" value="<?php if($saturdaystarttime){echo $saturdaystarttime;}?>"></td>
                                            <td><input type="time" name="saturdayendTime" value="<?php if($saturdayendtime){echo $saturdayendtime;}?>"></td>
                                        </tr>

                                    </tbody>
                                </table>


                        </div>

                        <div class="form-footer mt-4 d-flex justify-content-between">
                            <button type="button" class="btn  prev-step">Previous</button>
                            <button type="submit" class="btn next-step">Finish</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
    </main>
<link rel="stylesheet" href="../css/main.css">
<link rel="stylesheet" href="../css/user.css">
<script src="../js/user.js"></script>
<?php
        require_once('../include/js.php')
    ?>
<script>
    document.querySelector('.btn-primary[type="submit"]').addEventListener('click', function() {
    document.getElementById('wizardForm').submit();
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.load-subject-checkbox1:checked').forEach(function(checkbox) {
            const subjectName = checkbox.getAttribute('data-subjectname1');


            addToSpecialization(subjectName,checkbox);
        });


        document.querySelectorAll('.load-subject-checkbox1').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const subjectName = this.getAttribute('data-subjectname1');


                if (this.checked) {
                    addToSpecialization(subjectName,  this);
                } else {

                    removeFromSpecialization(subjectName);
                }
            });
        });
    });

    function addToSpecialization(subjectName, checkbox) {
        const tbody = document.getElementById('loadedSubjects1');

        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td hidden><input type="text" name="subjectname[]" value="${subjectName}" class="form-control"></td>
            <td class="text-start">${subjectName}</td>

            <td class="align-middle">
                <button type="button" class="remove-subject btn-danger btn-sm ">Remove</button>
            </td>
        `;

        newRow.querySelector('.remove-subject').addEventListener('click', function() {
            newRow.remove();

            checkbox.checked = false;
        });
        tbody.appendChild(newRow);
    }

    function removeFromSpecialization(subjectName) {
        const tbody = document.getElementById('loadedSubjects1');
        const rows = tbody.querySelectorAll('tr');

        rows.forEach(function(row) {
            const subjectInput = row.querySelector('input[name="subjectname[]"]');


            if (subjectInput && subjectInput.value.trim() === subjectName) {
                row.remove();
            }
        });
    }

</script>
<script>
$(document).ready(function() {
    $('#subjects1').DataTable({
        "pageLength": 10,
        "searching": true,
        "lengthChange": false
    });
});
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        function toggleTimeInputs(day) {
            const checkbox = document.querySelector(`input[name="${day}"]`);
            const startTimeInput = document.querySelector(`input[name="${day}startTime"]`);
            const endTimeInput = document.querySelector(`input[name="${day}endTime"]`);

            if (checkbox && startTimeInput && endTimeInput) {
                startTimeInput.style.display = checkbox.checked ? "inline-block" : "none";
                endTimeInput.style.display = checkbox.checked ? "inline-block" : "none";

                checkbox.addEventListener("change", () => {
                    startTimeInput.style.display = checkbox.checked ? "inline-block" : "none";
                    endTimeInput.style.display = checkbox.checked ? "inline-block" : "none";
                    validateMinimumDays();
                });

                startTimeInput.addEventListener("change", () => {
                    validateTimeRange(startTimeInput, endTimeInput, day);
                });

                endTimeInput.addEventListener("change", () => {
                    validateTimeRange(startTimeInput, endTimeInput, day);
                });
            }
        }

        function validateTimeRange(startTimeInput, endTimeInput, day) {
            const earliestTime = "07:00";
            const latestTime = "19:00";

            
            if (startTimeInput.value && (startTimeInput.value < earliestTime || startTimeInput.value > latestTime)) {
                alert(`Start time for ${day.charAt(0).toUpperCase() + day.slice(1)} must be between 7:00 AM and 7:00 PM.`);
                startTimeInput.value = "";
            }


            if (endTimeInput.value && (endTimeInput.value < earliestTime || endTimeInput.value > latestTime)) {
                alert(`End time for ${day.charAt(0).toUpperCase() + day.slice(1)} must be between 7:00 AM and 7:00 PM.`);
                endTimeInput.value = "";
            }

            if (startTimeInput.value && endTimeInput.value && endTimeInput.value <= startTimeInput.value) {
                alert(`End time for ${day.charAt(0).toUpperCase() + day.slice(1)} should be later than start time.`);
                endTimeInput.value = "";
            }
        }

        function validateMinimumDays() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const checkedCount = Array.from(checkboxes).filter(checkbox => checkbox.checked).length;

            if (checkedCount < 3) {
                alert("You must select at least 3 days.");
            }
        }

        const days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
        days.forEach(day => toggleTimeInputs(day));

        // Validate at least 3 days on form submission
        const form = document.querySelector("form"); // Adjust selector to your form's actual ID/class
        form.addEventListener("submit", function (event) {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const checkedCount = Array.from(checkboxes).filter(checkbox => checkbox.checked).length;

            if (checkedCount < 3) {
                alert("You must select at least 3 days.");
                event.preventDefault(); // Prevent form submission
            }
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const nonumberInputs = document.querySelectorAll('.nonumber');
        nonumberInputs.forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[0-9]/g, '');
            });
        });
    });
</script>
<script>

</script>

</html>
<link rel="stylesheet" href="../css/faculty-css/dashboard.css">
<script>
    function changeclass() {
      $("#main").toggleClass('col-sm-10 col-sm-12');
    }
  </script>
<script src="color-modes.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const steps = document.querySelectorAll("[id^='step']"); // Select elements with IDs starting with 'step'
    const nextButtons = document.querySelectorAll(".next-step");
    const prevButtons = document.querySelectorAll(".prev-step");

    // Function to validate required inputs in the current step
    function validateStep(currentStep) {
        const requiredFields = currentStep.querySelectorAll("[required]");
        return Array.from(requiredFields).every((field) => {
            if (field.type === "checkbox") {
                return field.checked;
            }
            return field.value.trim() !== "";
        });
    }

    // Function to toggle the "Next" button based on validation
    function toggleNextButton(currentStep) {
        const nextButton = currentStep.querySelector(".next-step");
        if (validateStep(currentStep)) {
            nextButton.disabled = false;
        } else {
            nextButton.disabled = true;
        }
    }

    // Function to add event listeners for required fields
    function addValidationListeners(currentStep) {
        const requiredFields = currentStep.querySelectorAll("[required]");
        requiredFields.forEach((field) => {
            field.addEventListener("input", () => {
                toggleNextButton(currentStep);
            });
            if (field.type === "checkbox") {
                field.addEventListener("change", () => {
                    toggleNextButton(currentStep);
                });
            }
        });
    }

    // Function to show the step by ID
    function showStep(stepId) {
        steps.forEach((step) => {
            if (step.id === stepId) {
                step.style.display = "block";
                toggleNextButton(step);
                addValidationListeners(step); // Add listeners when step is shown
            } else {
                step.style.display = "none";
            }
        });
    }

    // Add event listeners for "Next" buttons
    nextButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const currentStep = button.closest("[id^='step']");
            const nextStepId = `step${parseInt(currentStep.id.replace("step", ""), 10) + 1}`;
            showStep(nextStepId);
        });
    });

    // Add event listeners for "Previous" buttons
    prevButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const currentStep = button.closest("[id^='step']");
            const prevStepId = `step${parseInt(currentStep.id.replace("step", ""), 10) - 1}`;
            showStep(prevStepId);
        });
    });

    // Initialize: Show only the first step and set up validation
    steps.forEach((step, i) => {
        step.style.display = i === 0 ? "block" : "none";
        if (i === 0) {
            toggleNextButton(step);
            addValidationListeners(step);
        }
    });
});

</script>

<script>
    
</script>