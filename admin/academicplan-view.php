<!DOCTYPE html>
<html lang="en">
<body >

    <?php
    
        require_once('../include/nav.php');
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        echo $_SESSION['yearlvl'];
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['academicplanyear']) && isset($_POST['academicplansem']) && isset($_POST['academicplancalendarid'])) {
                $year = htmlspecialchars($_POST['academicplanyear']);
                $_SESSION['year']=$year;
                $sem = htmlspecialchars($_POST['academicplansem']);
                $_SESSION['sem']=$sem;

                $calendarid = htmlspecialchars($_POST['academicplancalendarid']);
                $_SESSION['calendarid']=$calendarid;

                
            } else {
                $calendarid=$_SESSION['calendarid'];
                $sem=$_SESSION['sem'];
                $year=$_SESSION['year'];
                
            }
            if (isset($_SESSION['departmentid'])){
                $departmentid = htmlspecialchars($_SESSION['departmentid']);
            }else{
                $departmentid =1;
                $_SESSION['departmentid']=$departmentid;
            }
            if (isset($_POST['academicplanyearlvl'])){
                $yearlvl= htmlspecialchars($_POST['academicplanyearlvl']);
                $_SESSION['yearlvl']=$yearlvl;
            }else{
                $yearlvl= 1;
                $_SESSION['yearlvl']=$yearlvl;
                
            }
        } else {
            echo "Form not submitted.";
        }

    ?>
    <main>
        <div class="container mb-1">
            <div class="row">
                <div  class="col-4">
                    <div class="row ">
                        <h5>
                        <button onclick="window.location.href='academic-plan.php'">
                                <i class="fa-solid fa-circle-arrow-left"></i>
                            </button>
                            Academic Plan for <span><?php if ($sem==1){echo $sem.'st Sem S.Y '.$year;}else{echo $sem.'nd Sem S.Y '.$year;};?></span>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="container py-3">
                <div class="row d-flex justify-content-end">
                    <div class="col-1">
                        <select class="form-select  form-select-sm " id="select-position">
                            <option>all</option>
                            <option>Dean</option>
                            <option>Visiting</option>
                        </select>
                    </div>

                    <div class="searchbar col-3 ">
                        <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                    </div>
                    <div class="col-3 d-flex align-items-center justify-content-start">
                        <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 steps sticky-sidebar ">
                        <h5>Year Level</h5>
                        <div class="nav d-flex align-items-center mt-3 text-center">
                            <form action="academicplan-view.php" method="POST">
                                <input type="hidden" name="academicplanyearlvl" value="1">
                                <button type="submit" class="<?php if ($yearlvl==1){echo 'currentyearlvl';}?>">First Year</button>
                            </form>
                            <form action="academicplan-view.php" method="POST">
                                <input type="hidden" name="academicplanyearlvl" value="2">
                                <button type="submit" class="<?php if ($yearlvl==2){echo 'currentyearlvl';}?>">Second Year</button>
                            </form>
                            <form action="academicplan-view.php" method="POST">
                                <input type="hidden" name="academicplanyearlvl" value="3">
                                <button type="submit" class="<?php if ($yearlvl==3){echo 'currentyearlvl';}?>">Third Year</button>
                            </form>
                            <form action="academicplan-view.php" method="POST">
                                <input type="hidden" name="academicplanyearlvl" value="4">
                                <button type="submit" class="<?php if ($yearlvl==4){echo 'currentyearlvl';}?>">Fourth Year</button>
                            </form>
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

                                            </tr>
                                        </thead>
                                        <tbody id="loadedSubjects1" class="list">

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
                                <form id="facultyForm" class="row g-3 mt-4 needs-validation" action="../database/addsubject.php" method="POST" novalidate="">
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
                                                            <label class="form-label ml-5" for="subtype">Type </label>
                                                            <h5>Lec </h5>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label class="form-label" for="unit">Unit</label>
                                                            <select class="form-select" id="unit" required="" name="lecunit">
                                                                <option selected="" disabled="" value="">Choose...</option>
                                                                <option value="2.0">2.0</option>
                                                                <option value="3.0">3.0</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="subname">Subject hours</label>
                                                            <input class="form-control" id="subname" type="number" required  />
                                                        </div>
                                                </div>
                                                <div class="row mt-3">
                                                        <div class="col-md-2">
                                                            <h5>Lab <input class="form-check-input " type="checkbox" id="checkbox-1" name="lab" data-bulk-select-row="data-bulk-select-row" /></h5>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <select class="form-select" id="unit" required="" name="labunit">
                                                                <option selected="" disabled="" value="">Choose...</option>

                                                                <option selected value="1.0">1.0</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input class="form-control" id="subname" type="number" required  />
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
                                                </select>
                                                <div class="row mt-3">
                                                    <label for="">Mark check if the Subject requires <span>Masters</span></label>
                                                    <h5 class="mt-3 "> <input class="form-check-input " type="checkbox" id="checkbox-1" name="masters" data-bulk-select-row="data-bulk-select-row" /> Required</h5>
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
    
    </script>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/academic-view.css">
    <script src="../js/main.js"></script>
    <?php
        require_once('../include/js.php')
    ?>
    


</html>
