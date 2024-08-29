<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body >

    <?php
        require_once('../include/user-nav.php');
        require_once('../database/datafetch.php');

    ?>

    <main>
    <div class="container py-2">
        <div class="row ">
            <div class="g-3 row year-level">
                <h5>Complete Your Profile</h5>
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
                <form id="wizardForm" method="POST" action="../database/addprofiling.php">
                    <input type="number" name="facultyid" value="<?php echo $_SESSION['id'];?>" hidden>
                    <div class="step-content active p-4" id="step1">
                        <h5>Personal Information</h5>
                        <?php foreach($facultyinfo as $facultyinfos){ ?>

                        <div class="row mt-2">
                            <div class="col-md-5">
                                <label class="form-label" for="firstname">First name</label>
                                <input class="form-control" id="firstname" type="text" name="fname" value="<?php if(isset($facultyinfos['fname'])){ echo $facultyinfos['fname'];} ?>" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label" for="lastname">Last name</label>
                                <input class="form-control" id="lastname" type="text" name="lname" value="<?php if(isset($facultyinfos['lname'])){ echo $facultyinfos['lname'];} ?>" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="midleinit">MI</label>
                                <input class="form-control" id="midleinit" type="text" name="mname" value="<?php if(isset($facultyinfos['mname'])){ echo $facultyinfos['mname'];} ?>" required />
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-5">
                                <label class="form-label" for="contactnumber">Contact Number</label>
                                <input class="form-control" id="contactnumber" name="contactno" type="tel" value="<?php if(isset($facultyinfos['contactno'])){ echo $facultyinfos['contactno'];} ?>" required />
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="birthdate">Birthdate</label>
                                <input class="form-control" id="birthdate" name="bday" type="date" value="<?php if(isset($facultyinfos['bday'])){ echo $facultyinfos['bday'];} ?>"required />
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="gender">Gender</label>
                                <select class="form-select" id="gender" name="gender" required="">
                                    <option selected="" disabled="">Choose...</option>
                                    <option <?php if(isset($facultyinfos['gender']) && $facultyinfos['gender'] == 'Male'){ echo 'selected'; } ?> value="Male">Male</option>
                                    <option <?php if(isset($facultyinfos['gender']) && $facultyinfos['gender'] == 'Female'){ echo 'selected'; } ?> value="Female">Female</option>
                                </select>
                                <div class="invalid-feedback">Please select a Gender</div>
                            </div>
                        </div>
                        <div class="form-footer mt-4 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary next-step">Next</button>
                        </div>
                    </div>
                    <div class="step-content p-4" id="step2">
                    <h5>Faculty Information</h5>
                        <div class="row mt-2">
                            <div class="col-6">
                                <label class="form-label" for="position">Type</label>
                                <select class="form-select" id="position" name="type" required="">
                                    <option selected="" disabled="" value="">Choose...</option>
                                    <option <?php if(isset($facultyinfos['type']) && $facultyinfos['type'] == 'Regular'){ echo 'selected'; } ?> value="regular">Regular </option>
                                    <option <?php if(isset($facultyinfos['type']) && $facultyinfos['type'] == 'Contractual'){ echo 'selected'; } ?> value="contractual">Contractual</option>
                                </select>
                                <div class="invalid-feedback">Please select a type</div>
                            </div>

                        </div>
                        <div class="row mt-2">
                            <div class="col-6">
                                <label class="form-label" for="startdate">Start Date</label>
                                <input class="form-control" id="startdate" type="date" name="startdate" value="<?php if(isset($facultyinfos['startdate'])){ echo $facultyinfos['startdate'];} ?>" required />
                                <div class="valid-feedback">Looks good!</div>
                            </div>

                            <div class="col-6">
                                <label class="form-label" for="teachinghours">Teaching Hours</label>
                                <input class="form-control" id="teachinghours" type="number" name="teachinghours" value="<?php if(isset($facultyinfos['teachinghours'])){ echo $facultyinfos['teachinghours'];} ?>" required placeholder="Hours/Week" />
                            </div>
                        </div>

                        <div class="form-footer mt-5 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="button" class="btn btn-primary next-step">Next</button>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="step-content p-4" id="step3">
                        <h5>Teaching Details</h5>
                        <div class="container ">
                            <label for="">Teaching Specialization</label>
                            <div class="wrap p-3 m-3">
                                <table class="table table-sm fs-9 mb-0 text-center ">
                                    <thead>
                                        <tr>
                                            <th data-sort="subcode">Code</th>
                                            <th data-sort="desc">Description</th>
                                            <th data-sort="type">Type</th>
                                            <th></th>
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
                                <table id="subjects1" class="table table-sm fs-9 mb-0 text-center">
                                    <thead>
                                        <tr>
                                            <th data-sort="subcode">Code</th>
                                            <th data-sort="desc">Description</th>
                                            <th data-sort="type">Type</th>
                                            <th>Select</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        <?php $seenSubjectCodes = [];

                                        foreach ($subject as $subjects) {

                                            if (!in_array($subjects['subjectcode'], $seenSubjectCodes)) {

                                                $seenSubjectCodes[] = $subjects['subjectcode'];
                                                $displaySubjectCode = $subjects['subjectcode'];
                                            } else {
                                                $displaySubjectCode = '';
                                            }
                                        ?>
                                        <tr>
                                            <td class="align-middle subid" hidden><?php echo $subjects['subjectid'];?></td>
                                            <td class="align-middle subcode"><?php echo $displaySubjectCode; ?></td>
                                            <td class="align-middle desc"><?php echo $subjects['subjectname']; ?></td>
                                            <td class="align-middle subtype"><?php echo $subjects['type']; ?></td>
                                            <td class="align-middle subtype"><?php echo $subjects['unit']; ?></td>
                                            <td class="align-middle subtype"><?php echo $subjects['focus']; ?></td>

                                            <td class="align-middle">
                                                <input type="checkbox" class="form-check-input load-subject-checkbox1" data-subjectid1="<?php echo $subjects['subjectid']; ?>" data-subjectcode1="<?php echo $subjects['subjectcode']; ?>" data-subjectname1="<?php echo $subjects['subjectname']; ?>" data-type1="<?php echo $subjects['type']; ?>" data-unit1="<?php echo $subjects['unit']; ?>" data-focus1="<?php echo $subjects['focus']; ?>">
                                            </td>
                                        </tr>
                                    <?php } ?>
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>

                        <div class="form-footer mt-4 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="button" class="btn btn-primary next-step">Next</button>
                        </div>
                    </div>
                    <div class="step-content p-4" id="step4">
                        <h5>Professional  Qualifications</h5>
                        <div class="row">
                            <div class="table-load my-2 p-3 col-6">
                                <label class="form-label" for="degree">Highest Degree Obtained</label>
                                <select class="form-select" name="highestdegree" id="degree" name="degree" required>
                                    <option selected disabled value="">Choose...</option>
                                    <option value="PhD">PhD</option>
                                    <option value="MD">MD</option>
                                </select>
                            </div>
                            <div class="table-load my-2 p-3 col-6" id="specialization-container" style="display: none;">
                                <label class="form-label" for="specialization">Specialization</label>
                                <select class="form-select" id="specialization" name="specialization" required>
                                    <option selected disabled value="">Choose...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-footer mt-4 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="button" class="btn btn-primary next-step">Next</button>
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
                                            <td><input type="checkbox" name="monday"></td>
                                            <td>Monday</td>
                                            <td><input type="time" name="mondaystartTime"></td>
                                            <td><input type="time" name="mondayendTime"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="tuesday"></td>
                                            <td>Tuesday</td>
                                            <td><input type="time" name="tuesdaystartTime"></td>
                                            <td><input type="time" name="tuesdayendTime"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="wednesday"></td>
                                            <td>Wednesday</td>
                                            <td><input type="time" name="wednesdaystartTime"></td>
                                            <td><input type="time" name="wednesdayendTime"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="thursday"></td>
                                            <td>Thursday</td>
                                            <td><input type="time" name="thursdaystartTime"></td>
                                            <td><input type="time" name="thursdayendTime"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="friday"></td>
                                            <td>Friday</td>
                                            <td><input type="time" name="fridaystartTime"></td>
                                            <td><input type="time" name="fridayendTime"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="saturday"></td>
                                            <td>Saturday</td>
                                            <td><input type="time" name="saturdaystartTime"></td>
                                            <td><input type="time" name="saturdayendTime"></td>
                                        </tr>

                                    </tbody>
                                </table>

                            
                        </div>

                        <div class="form-footer mt-4 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="submit" class="btn btn-primary">Finish</button>
                        </div>
                    </div>
            </div>
            </form>

        </div>
    </div>
    </main>
</body>
<link rel="stylesheet" href="../css/main.css">
<link rel="stylesheet" href="../css/user.css">
<script src="../js/user.js"></script>
<?php
        require_once('../include/js.php')
    ?>
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
                        <td hidden><input type="text" name="subjectid[]" value="${subjectId}" class="form-control"></td>
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
});

</script>
<script>
    document.querySelector('.btn-primary[type="submit"]').addEventListener('click', function() {
    document.getElementById('wizardForm').submit();
});

</script>


</html>

