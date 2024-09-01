<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body>

    <?php

        require_once('../include/user-mainnav.php');
        require_once('../database/datafetch.php');
    ?>
            <main>
            <div class="container ">
                <div class="row">
                    <div class="text d-flex align-items-center" >
                        <h2> Hola !!! </h2> <span> Role</span>
                    </div>
                </div>
                <h5>My Profile</h5>
                <div class="profile-table p-0 m-5">
                    <div class="table-header">
                        <div class="row m-4">
                            <div class="col-1 img-upload">
                                <div class="image-container">
                                    <img src="http://www.clker.com/cliparts/M/o/W/d/C/j/about-icon-md.png" width="70" height="70" class="circle-image">
                                    <label for="file-upload" class="custom-file-upload">
                                        <i class="fa-solid fa-camera"></i>
                                    </label>
                                    <input id="file-upload" type="file" name="image" class="img">
                                </div>
                                <form action="student-profile.php" method="post">
                                </form>
                            </div>
                            <div class="col-6">
                                <h3>Name</h3>
                                <div class="row">
                                    <label for="">[degree]</label>
                                    <label for="">[department]</label>
                                </div>
                            </div>
                            <div class="col-5 d-flex justify-content-end align-items-center ">
                                <button class="save">save</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="profile-info col-3 ">
                        <div class="p-4">
                            <div class="form-group">
                                <label for="teachingHour">Teaching Hour</label>
                                <input type="text" class="form-control" id="teachingHour">
                            </div>
                            <div class="form-group">
                                <label for="genderSelect">Gender</label>
                                <div class="input-group">
                                    <select class="form-control" id="genderSelect">
                                        <option>male</option>
                                    </select>
                                    <div class="input-group-append">
                                    <button class="btn "><i class="fa-solid fa-pen"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <div class="input-group">
                                    <input type="email" class="form-control" id="email">
                                    <div class="input-group-append">
                                    <button class="btn "><i class="fa-solid fa-pen"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="contactNo">Contact No.</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="contactNo">
                                    <button class="btn "><i class="fa-solid fa-pen"></i></button>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-9 px-0 ">
                            <div class="faculty-info p-4">
                                <label for="">Faculty Information</label>
                                <div class="row p-3 ">
                                    <div class="col-6">
                                        <label for="">Position/Rank</label>
                                        <div class="input-group">
                                            <input type="text">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label for="">Start Of Service</label>
                                        <div class="input-group">
                                            <input type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row p-4">
                                <div class="col-7 ">
                                    <div class="row  p-3">
                                        <div class="table-load col-6">
                                            <label class="form-label" for="degree">Highest Degree Obtained</label>
                                            <select class="form-select" id="degree" name="degree" required>
                                                <option selected disabled value="">Choose...</option>
                                                <option value="PhD">PhD</option>
                                                <option value="MD">MD</option>
                                            </select>
                                        </div>
                                        <div class="table-load col-6" id="specialization-container">
                                            <label class="form-label" for="specialization">Specialization</label>
                                            <select class="form-select" id="specialization" name="specialization" required>
                                                <option selected disabled value="">Choose...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5 p-3 ">
                                    <label for="">Course Taught</label>
                                    <li>suject1</li>
                                    <li>subject2</li>
                                    <li>subject3</li>
                                </div>
                            </div>
                            <div class="row p-4 mb-4">
                                <div class="preference-table p-3">
                                    <label for="">Preferencess</label>
                                    <div class="d-flex justify-content-end">
                                    <button class="btn "><i class="fa-solid fa-pen"></i></button>
                                    </div>

                                    <div class="pref p-3 m-4">
                                        <table id="editableTable" class="table table-sm fs-9 mb-0">
                                            <thead>
                                                <tr>

                                                    <th>Day</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Monday</td>
                                                    <td>09:00</td>
                                                    <td>17:00</td>
                                                </tr>
                                                <tr>
                                                    <td>Tuesday</td>
                                                    <td>09:00</td>
                                                    <td>17:00</td>
                                                </tr>
                                                <tr>
                                                    <td>Wednesday</td>
                                                    <td>09:00</td>
                                                    <td>17:00</td>
                                                </tr>
                                                <tr>
                                                    <td>Thursday</td>
                                                    <td>09:00</td>
                                                    <td>17:00</td>
                                                </tr>
                                                <tr>
                                                    <td>Friday</td>
                                                    <td>09:00</td>
                                                    <td>17:00</td>
                                                </tr>
                                                <tr>
                                                    <td>Saturday</td>
                                                    <td>09:00</td>
                                                    <td>17:00</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
</body>
<script src="../js/main.js"></script>
<link rel="stylesheet" href="../css/main.css">
<link rel="stylesheet" href="../css/faculty-css/profile.css">

<?php
        require_once('../include/js.php')
    ?>


</html>
