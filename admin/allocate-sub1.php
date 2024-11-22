<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/allocate.css">
    <script src="../js/main.js"></script>
    <script src="../js/allocate.js"></script>
<body >

    <?php
        require_once('../include/nav.php');
        require_once('../database/datafetch.php');

        $departmentid=$_POST['departmentid'];
        $semester=$_POST['semester'];
        $academicyear=$_POST['academicyear'];

    ?>
    <main>
        <div class="container mb-1">
            <div class="row">
                <h5>
                    <button onclick="window.location.href='setup-acadplan.php'">
                        <i class="fa-solid fa-circle-arrow-left"></i>
                    </button>
                    New Academic Plan
                </h5>
            </div>

            <div class="container py-3">
                <div class="row">
                    <div class="col-md-3 steps sticky-sidebar ">

                        <h5>Year Level</h5>
                        <div class="nav d-flex align-items-center mt-3 text-center">

                            <li>First Year</li>
                        </div>
                    </div>
                    <div class="col-md-9 scrollable-content">
                            <input type="number" name="departmentid" value=<?php echo $departmentid;?> hidden>
                            <input type="number" name="semester" value=<?php echo $semester;?> hidden>
                            <input type="number" name="academicyear" value=<?php echo $academicyear;?> hidden>
                            <div class="row">
                                <div class="button d-flex justify-content-end">
                                <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                                </div>

                            </div>
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
                                <div class="row mt-3 d-flex justify-content-end my-2 p-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="">Select Subjects to Load</label>
                                        </div>
                                        <div class="col-6">
                                            <label for="curriculum">Select Curriculum</label>
                                            <select class="form-select" id="curriculum" required="">
                                                <option selected="" disabled="" value="">Choose...</option>
                                                <option>2018-2021</option>
                                                <option>2022-2024</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="table-sub1 table-sub my-3 p-3">
                                        <table id="subjects1" class="table table-sm fs-9 mb-0 ">
                                            <thead>
                                                <tr>
                                                    <th data-sort="subcode" hidden>id</th>
                                                    <th data-sort="subcode">Code</th>
                                                    <th data-sort="desc">Description</th>
                                                    <th data-sort="desc">Type</th>
                                                    <th data-sort="desc">Unit</th>
                                                    <th data-sort="desc">Focus</th>
                                                    <th data-sort="desc">Select</th>

                                                </tr>
                                            </thead>
                                            <tbody class="list">

                                                <tr>
                                                    <td class="align-middle subid" hidden><?php echo $subjects['subjectid'];?></td>
                                                    <td class="align-middle subcode"><input type="text"></td>
                                                    <td class="align-middle desc"><input type="text"></td>
                                                    <td class="align-middle subtype"><select name="" id=""><option value="Lec"></option></select></td>
                                                    <td class="align-middle subtype"><input type="text"></td>
                                                    <td class="align-middle subtype"><select name="" id=""><option value="Lec"></option></select></td>

                                                    <td class="align-middle">
                                                        <button>+</button>
                                                    </td>
                                                </tr>
                                                <!-- Add more rows as needed -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary next-step">Next</button>
                            </div>
                    </div>
            </div>

        </div>
    </main>
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
</body>


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

