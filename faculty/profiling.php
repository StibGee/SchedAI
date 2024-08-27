<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body >

    <?php
        require_once('../include/user-nav.php');
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
                <form id="wizardForm">
                    <div class="step-content active p-4" id="step1">
                        <h5>Personal Information</h5>
                        <div class="row mt-2">
                            <div class="col-md-5">
                                <label class="form-label" for="firstname">First name</label>
                                <input class="form-control" id="firstname" type="text" name="fname" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label" for="lastname">Last name</label>
                                <input class="form-control" id="lastname" type="text" name="lname" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="midleinit">MI</label>
                                <input class="form-control" id="midleinit" type="text" name="mname" required />
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-5">
                                <label class="form-label" for="contactnumber">Contact Number</label>
                                <input class="form-control" id="contactnumber" name="contactno" type="tel" required />
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="birthdate">Birthdate</label>
                                <input class="form-control" id="birthdate" name="bday" type="date" required />
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="gender">Gender</label>
                                <select class="form-select" id="gender" name="gender" required="">
                                    <option selected="" disabled="">Choose...</option>
                                    <option value="Male">Male</option>
                                    <option value="Male">Female</option>
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
                                    <option value="regular">Regular </option>
                                    <option value="contractual">Contractual</option>
                                </select>
                                <div class="invalid-feedback">Please select a Gender</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="facultyid">Faculty ID</label>
                                <input class="form-control" id="facultyid" type="number" name="facultyid" required placeholder="" />
                            </div>

                        </div>
                        <div class="row mt-2">
                            <div class="col-6">
                                <label class="form-label" for="startdate">Start Date</label>
                                <input class="form-control" id="startdate" type="date" name="startdate" required />
                                <div class="valid-feedback">Looks good!</div>
                            </div>

                            <div class="col-6">
                                <label class="form-label" for="teachinghours">Teaching Hours</label>
                                <input class="form-control" id="teachinghours" type="number" name="teachinghours" required placeholder="Hours/Week" />
                            </div>
                        </div>

                        <div class="form-footer mt-5 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="button" class="btn btn-primary next-step">Next</button>
                        </div>
                    </div>

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
                                    <tbody >
                                        <tr>
                                            <td>CC135</td>
                                            <td>WebDev</td>
                                            <td>Major</td>
                                            <td>Loaded by <span class="text-danger">Admin</span></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>CC135</td>
                                            <td>WebDev</td>
                                            <td>Major</td>
                                            <td></td>
                                            <td>remove</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <label for="">Preferred Subject Selection</label>
                            <div class="wrap p-3 m-3">
                                <table class="table table-sm fs-9 mb-0 text-center">
                                    <thead>
                                        <tr>
                                            <th data-sort="subcode">Code</th>
                                            <th data-sort="desc">Description</th>
                                            <th data-sort="type">Type</th>
                                            <th>Select</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>CC135</td>
                                            <td>WebDev</td>
                                            <td>Major</td>
                                            <td><input type="checkbox" name="select"></td>
                                        </tr>
                                    </tbody>
                                </table>
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
                                <select class="form-select" id="degree" name="degree" required>
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
                                            <td><input type="checkbox" name="select"></td>
                                            <td>Monday</td>
                                            <td><input type="time" name="startTime"></td>
                                            <td><input type="time" name="endTime"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="select"></td>
                                            <td>Tuesday</td>
                                            <td><input type="time" name="startTime"></td>
                                            <td><input type="time" name="endTime"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="select"></td>
                                            <td>Wednesday</td>
                                            <td><input type="time" name="startTime"></td>
                                            <td><input type="time" name="endTime"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="select"></td>
                                            <td>Thursday</td>
                                            <td><input type="time" name="startTime"></td>
                                            <td><input type="time" name="endTime"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="select"></td>
                                            <td>Friday</td>
                                            <td><input type="time" name="startTime"></td>
                                            <td><input type="time" name="endTime"></td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" name="select"></td>
                                            <td>Saturday</td>
                                            <td><input type="time" name="startTime"></td>
                                            <td><input type="time" name="endTime"></td>
                                        </tr>

                                    </tbody>
                                </table>

                            <div class="table-load my-2 p-3 col-6" id="specialization-container" style="display: none;">
                                <label class="form-label" for="specialization">Specialization</label>
                                <select class="form-select" id="specialization" name="specialization" required>
                                    <option selected disabled value="">Choose...</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-footer mt-4 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Previous</button>
                            <button type="submit" class="btn btn-primary" onclick="window.location.href='../faculty/user-dashboard.php'">Finish</button>
                        </div>
                    </div>
                </form>
            </div>
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

</html>

