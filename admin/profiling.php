<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body >

    <?php
        require_once('../include/user-nav.php');

        require_once('../classes/subject.php');
        require_once('../classes/db.php');
        require_once('../classes/faculty.php');
        require_once('../classes/department.php');

        $db = new Database();
        $pdo = $db->connect();

        $subject = new Subject($pdo);
        $department = new Department($pdo);
        $faculty = new Faculty($pdo);

        if (isset($_POST['facultyid'])){
            $facultyid=$_POST['facultyid'];
        }
        if ($_SESSION['departmentid']!=0){
            $distinctsubjects = $subject->getdistinctsubjectscollege($_SESSION['collegeid']);
            $collegedepartment = $department->getdepartmentdepartment($_SESSION['departmentid']);
            
        }else{
            $distinctsubjects = $subject->getdistinctsubjectscollege($_SESSION['collegeid']);
            
            $collegedepartment = $department->getcollegedepartment($_SESSION['collegeid']);
        }

        $facultyinfo = $faculty->getfacultyinfo($facultyid);
        $existingsubjects = $faculty->getfacultysubjects($facultyid);
        if ($facultyinfo) {

        } else {
            echo 'No faculty information found.';
        }
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
    <div class="container py-2">
        <div class="row ">
            <div class="g-3 row year-level">

                <h5>Edit Faculty Profile</h5>
            </div>
            <div class="col-md-3 steps sticky-sidebar">

                <div class="step-indicator d-flex align-items-start mt-3">
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
                        <span class="step-label">Professional Qualificaton</span>
                    </div>
                    <div class="step">
                        5
                        <span class="step-label">Preferences</span>
                    </div>
                </div>
                <div class="mt-3">
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
                                <input class="form-control" id="firstname" type="text" name="fname" value="<?php if(isset($facultyinfo['fname'])){ echo $facultyinfo['fname'];} ?>" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label" for="lastname">Last name</label>
                                <input class="form-control" id="lastname" type="text" name="lname" value="<?php if(isset($facultyinfo['lname'])){ echo $facultyinfo['lname'];} ?>" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="midleinit">MI</label>
                                <input class="form-control" id="midleinit" type="text" name="mname" value="<?php if(isset($facultyinfo['mname'])){ echo $facultyinfo['mname'];} ?>" required />
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-5">
                                <label class="form-label" for="contactnumber">Contact Number</label>
                                <input class="form-control" id="contactnumber" name="contactno" type="tel" value="<?php if(isset($facultyinfo['contactno'])){ echo $facultyinfo['contactno'];} ?>">
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" id="email" name="email" type="email" value="<?php if(isset($facultyinfo['email'])){ echo $facultyinfo['email'];} ?>">
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="gender">Gender</label>
                                <select class="form-select" id="gender" name="gender" required="">
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
                                <select class="form-select" id="position" name="type" required="">
                                    <option selected="" disabled="" value="">Choose...</option>
                                    <option <?php if(isset($facultyinfo['type']) && $facultyinfo['type'] == 'Regular'){ echo 'selected'; } ?> value="Regular">Regular </option>
                                    <option <?php if(isset($facultyinfo['type']) && $facultyinfo['type'] == 'Contractual'){ echo 'selected'; } ?> value="Contractual">Contractual</option>
                                </select>
                                <div class="invalid-feedback">Please select a type</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="position">Department</label>
                                <select class="form-select" id="position" name="departmentidpost" required="">
                                    <?php foreach ($collegedepartment AS  $collegedepartments){?>
                                        <option <?php if($facultyinfo['departmentid']==$collegedepartments['id']){echo 'selected';}?> value="<?php echo $collegedepartments['id'];?>"><?php echo  $collegedepartments['abbreviation'];?></option>
                                    
                                    <?php }?>
                                </select>
                                <div class="invalid-feedback">Please select a type</div>
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
                            <button type="button" class="btn  prev-step">Previous</button>
                            <button type="button" class="btn next-step">Next</button>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="step-content p-4" id="step3">
                        <h5>Teaching Details</h5>
                        <div class="container "><label for="">Teaching Specialization</label>
                            <div class="wrap p-3 m-3">
                                <table class="table table-sm fs-9 mb-0 text-center ">
                                    <thead>
                                        <tr>
                                            <th data-sort="subcode">Subject Name</th>

                                            <th>action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="loadedSubjects1" class="list">

                                    </tbody>
                                </table>
                            </div>
                            <label for="">Preferred Subject Selection</label>
                            <div class="wrap p-3 m-3">
                                <div class="table-sub1 table-sub my-3 p-3">
                                <table id="subjects1" class="table table-sm fs-9 mb-0">
                                    <thead>
                                        <tr>

                                            <th data-sort="desc">Subject Name</th>

                                            <th>Select</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        <?php
                                        foreach ($distinctsubjects as $subjects) {

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
                                                    data-subjectname1="<?php echo htmlspecialchars($subjects['name']); ?>"

                                                    <?php echo $checked; ?>>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                </tbody>


                                </table>
                                </div>
                            </div>
                        </div>

                        <div class="form-footer mt-4 d-flex justify-content-between">
                            <button type="button" class="btn  prev-step">Previous</button>
                            <button type="button" class="btn  next-step step3btn">Next</button>
                        </div>
                    </div>
                    <div class="step-content p-4" id="step4">
                        <h5>Professional  Qualifications</h5>
                        <div class="row">
                            <div class="table-load my-2 p-3 col-6">
                                <label class="form-label" for="degree">Highest Degree Obtained</label>
                                <select class="form-select" name="highestdegree" id="degree" name="degree" required>
                                    <option selected disabled value="">Choose...</option>
                                    <option value="Doctorate" <?php if($facultyinfo['rank']=='Doctorate'){ echo 'selected';}?>>Doctorate Degree</option>
                                    <option value="Masters" <?php if($facultyinfo['rank']=='Masters'){ echo 'selected';}?>>Masters Degree</option>
                                    <option value="Bachelors" <?php if($facultyinfo['rank']=='Bachelors'){ echo 'selected';}?>>Bachelor Degree</option>
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
                            <button type="button" class="btn  prev-step">Previous</button>
                            <button type="button" class="btn next-step">Next</button>
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
                            <button type="button" class="btn prev-step">Previous</button>
                            <button type="submit" class="btn next-step step5btn" disabled>Finish</button>
                        </div>
                    </div>
            </div>
            </form>

        </div>
    </div>
    </main>
</body>
<link rel="stylesheet" href="../css/faculty-css/dashboard.css">
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
            <td class="align-middle">${subjectName}</td>

            <td class="align-middle">
                <button type="button" class="btn btn-danger btn-sm remove-subject">Remove</button>
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
    const nextButton = document.querySelector(".step3btn"); // The "Next" button for Step 3
    const checkboxes = document.querySelectorAll(".load-subject-checkbox1"); // All checkboxes for subject selection
    const step3Content = document.querySelector("#step3"); // The step3 content
    let messageBox = document.createElement("div"); // Create a message box
    messageBox.style.color = "red"; // Set message text color to red
    messageBox.style.marginTop = "10px"; // Add some spacing for the message box
    step3Content.appendChild(messageBox); // Add the message box to step3 content

    // Function to count selected checkboxes
    function countSelectedSubjects() {
        let selectedCount = 0;
        checkboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                selectedCount++;
            }
        });
        return selectedCount;
    }

    // Function to handle validation and enable/disable the next button
    function updateNextButtonState() {
        const selectedSubjects = countSelectedSubjects();
        
        // Enable the button if 3 or more subjects are selected, otherwise disable it
        if (selectedSubjects >= 3) {
            nextButton.disabled = false; // Enable the button
            messageBox.textContent = ""; // Clear the message if condition is met
        } else {
            nextButton.disabled = true;  // Disable the button
            messageBox.textContent = "Please select at least 3 subjects."; // Show the message
        }
    }

    // Event listener for each checkbox to trigger validation
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", updateNextButtonState);
    });

    // Handle "Next" button click event
    nextButton.addEventListener("click", function () {
        const selectedSubjects = countSelectedSubjects();
        if (selectedSubjects < 3) {
            messageBox.textContent = "Please select at least 3 subjects."; // Show the message
        }
    });

    // Initial validation when the page loads
    updateNextButtonState();
});

</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {

// Function to toggle time input visibility based on checkbox selection
function toggleTimeInputs(day) {
    const checkbox = document.querySelector(`input[name="${day}"]`);
    const startTimeInput = document.querySelector(`input[name="${day}startTime"]`);
    const endTimeInput = document.querySelector(`input[name="${day}endTime"]`);

    if (checkbox && startTimeInput && endTimeInput) {
        // Toggle time input fields based on checkbox status
        startTimeInput.style.display = checkbox.checked ? "inline-block" : "none";
        endTimeInput.style.display = checkbox.checked ? "inline-block" : "none";

        // Event listener to toggle visibility when checkbox is changed
        checkbox.addEventListener("change", () => {
            startTimeInput.style.display = checkbox.checked ? "inline-block" : "none";
            endTimeInput.style.display = checkbox.checked ? "inline-block" : "none";
        });

        // Event listeners for validating time range and start/end time logic
        startTimeInput.addEventListener("change", () => {
            validateTimeRange(startTimeInput, endTimeInput, day);
        });

        endTimeInput.addEventListener("change", () => {
            validateTimeRange(startTimeInput, endTimeInput, day);
        });
    }
}

// Function to validate that time values are within valid range
function validateTimeRange(startTimeInput, endTimeInput, day) {
    const earliestTime = "07:00";
    const latestTime = "19:00";

    // Validate start time within range
    if (startTimeInput.value && (startTimeInput.value < earliestTime || startTimeInput.value > latestTime)) {
        alert(`Start time for ${capitalizeFirstLetter(day)} must be between 7:00 AM and 7:00 PM.`);
        startTimeInput.value = ""; // Reset the value if out of range
    }

    // Validate end time within range
    if (endTimeInput.value && (endTimeInput.value < earliestTime || endTimeInput.value > latestTime)) {
        alert(`End time for ${capitalizeFirstLetter(day)} must be between 7:00 AM and 7:00 PM.`);
        endTimeInput.value = ""; // Reset the value if out of range
    }

    // Validate that end time is after start time
    if (startTimeInput.value && endTimeInput.value && endTimeInput.value <= startTimeInput.value) {
        alert(`End time for ${capitalizeFirstLetter(day)} should be later than start time.`);
        endTimeInput.value = ""; // Reset the value if invalid
    }

    // Validate that the end time is at least 6 hours after the start time
    if (startTimeInput.value && endTimeInput.value) {
        const startTime = new Date(`1970-01-01T${startTimeInput.value}:00`);
        const endTime = new Date(`1970-01-01T${endTimeInput.value}:00`);

        // Calculate the time difference in hours
        const timeDifference = (endTime - startTime) / (1000 * 60 * 60);

        // If the time difference is less than 6 hours, show an alert
        if (timeDifference < 6) {
            alert(`The end time for ${capitalizeFirstLetter(day)} must be at least 6 hours after the start time.`);
            endTimeInput.value = ""; // Reset the value if the time difference is too short
        }
    }
}

// Helper function to capitalize the first letter of the day name
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// Initialize the time input toggling for each day
const days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
days.forEach(day => toggleTimeInputs(day));

});


</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const finishButton = document.querySelector(".step5btn"); // Finish button for Step 5
    const checkboxes = document.querySelectorAll('#step5 input[type="checkbox"]'); // All checkboxes within step5
    const timeInputs = document.querySelectorAll('#step5 input[type="time"]'); // All time inputs within step5
    
    // Function to count checked checkboxes within step5
    function countCheckedDays() {
        let checkedCount = 0;
        checkboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                checkedCount++;
            }
        });
        return checkedCount;
    }

    // Function to check if all selected days have time inputs filled
    function areTimeInputsFilled() {
        let allTimeInputsFilled = true;

        checkboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                const day = checkbox.name;
                const startTimeInput = document.querySelector(`input[name="${day}startTime"]`);
                const endTimeInput = document.querySelector(`input[name="${day}endTime"]`);
                
                // Check if both start and end times are filled
                if (!startTimeInput.value || !endTimeInput.value) {
                    allTimeInputsFilled = false;
                }
            }
        });

        return allTimeInputsFilled;
    }

    // Function to handle enabling/disabling the Finish button
    function updateFinishButtonState() {
        const checkedDays = countCheckedDays();
        const timeInputsFilled = areTimeInputsFilled();

        // Enable the button if at least 3 checkboxes are selected and time inputs are filled, otherwise disable it
        if (checkedDays >= 3 && timeInputsFilled) {
            finishButton.disabled = false; // Enable the button
        } else {
            finishButton.disabled = true;  // Disable the button
        }
    }

    // Add event listeners to checkboxes to track changes
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", updateFinishButtonState);
    });

    // Add event listeners to time inputs to track changes
    timeInputs.forEach((timeInput) => {
        timeInput.addEventListener("change", updateFinishButtonState);
    });

    // Initial check when the page loads
    updateFinishButtonState();
});

</script>
</html>

