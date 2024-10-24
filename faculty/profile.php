<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body>

    <?php

        require_once('../include/user-mainnav.php');
        require_once('../classes/subject.php');
        require_once('../classes/db.php');
        require_once('../classes/faculty.php');
        require_once('../classes/department.php');
        $db = new Database();
        $pdo = $db->connect();

        $subject = new Subject($pdo);
        $faculty = new Faculty($pdo);
        $department = new Department($pdo);
        $facultyinfo=$faculty->getfacultyinfo($_SESSION['id']);
        $facultysubjects=$faculty->getfacultysubjects($_SESSION['id']);
        $facultypreference=$faculty->getfacultydaytime($_SESSION['id']);
    ?>
            <main>
            <div class="container ">
                <div class="row">
                    <div class="text d-flex align-items-center" >
                        <h2> Hola !!! </h2> <span><?php echo  $facultyinfo['fname'];?></span>
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
                                <h3><?php echo $facultyinfo['fname'].' '.$facultyinfo['lname'];?></h3>
                                <div class="row">
                                    
                                    <label for=""><?php $departmmentinfo=$department->getdepartmentinfo($facultyinfo['departmentid']); echo $departmmentinfo['name']?></label>
                                </div>
                                </div>
                                <div class="col-5 d-flex flex-column justify-content-center align-items-end">
                                    <a href="facultyprofiling.php" class="save text-center mb-2 custom-link">Edit Profile</a>
                                
                                </div>

                            
                                    
                                
                            </div>
                    </div>
                    <div class="row">
                        <div class="profile-info col-3 ">
                        <div class="p-4">
                            <div class="form-group">
                                <label for="teachingHour">Teaching Hour</label>
                                <input readonly type="text" class="form-control" id="teachingHour" value="<?php echo $facultyinfo['teachinghours'];?>">
                            </div>
                            <div class="form-group">
                                <label for="genderSelect">Gender</label>
                                <input readonly type="text" class="form-control" name="" id="" value="<?php echo $facultyinfo['gender'];?>">
                                <div class="input-group">
                                    
                                    <div class="input-group-append">
                                    <!--<button class="btn "><i class="fa-solid fa-pen"></i></button>-->
                                    </div>
                                </div>
                            </div>
                            <!--<div class="form-group">
                                <label for="email">Email</label>
                                <div class="input-group">
                                    <input type="email" class="form-control" id="email" value="<?php echo $facultyinfo['email'];?>">
                                    <div class="input-group-append">
                                    <button class="btn "><i class="fa-solid fa-pen"></i></button>
                                    </div>
                                </div>
                            </div>-->
                            <div class="form-group">
                                <label for="contactNo">Contact No.</label>
                                <input readonly type="number" class="form-control" id="contactNo" value="<?php echo $facultyinfo['contactno'];?>">
                                <!--<div class="input-group">
                                    
                                    <button class="btn "><i class="fa-solid fa-pen"></i></button>
                                </div>-->
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
                                            <input readonly type="text" class="form-control"  value="<?php echo $facultyinfo['rank'];?>">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label for="">Start Of Service</label>
                                        <div class="input-group">
                                            <input readonly type="text" class="form-control" value="<?php echo $facultyinfo['startdate'];?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row p-4">
                                <div class="col-12">
                                    <div class="row p-3">
                                        <div class="table-load col-6">
                                            <label class="form-label" for="degree">Employment Type</label>
                                            
                                            <input readonly type="text" class="form-control" value="<?php echo $facultyinfo['type'];?>">
                                        </div>
                                        <!--<div class="table-load col-6" id="specialization-container">
                                            <label class="form-label" for="specialization">Specialization</label>
                                            <select class="form-select" id="specialization" name="specialization" required>
                                                <option selected disabled value="">Choose...</option>
                                            </select>
                                        </div>-->
                                        <div class="table-load col-6">
                                            <label for="">Expertise</label>
                                            <?php foreach ($facultysubjects AS $facultysubject){?>
                                                <li><?php echo $facultysubject['subjectname'];?></li>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row p-4 mb-4">
                                <div class="preference-table p-3">
                                    <label for="">Preferencess</label>
                                    <!--<div class="d-flex justify-content-end">
                                    <button class="btn "><i class="fa-solid fa-pen"></i></button>
                                    </div>-->

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
                                               
                                                <?php  $daysofweek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                                foreach ($facultypreference AS $facultypreferences){
                                                    
                                                    $dayindex = $facultypreferences['day'];
                                                    $day = isset($daysofweek[$dayindex]) ? $daysofweek[$dayindex] : 'Unknown Day';?>
                                                    <tr>
                                                    <td><?php echo $day;?></td>
                                                    <td><?php echo date("g:i A", strtotime($facultypreferences['starttime']));?></td>
                                                    <td><?php echo date("g:i A", strtotime($facultypreferences['endtime']));?></td>
                                                    </tr>
                
                                                <?php } ?>
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
