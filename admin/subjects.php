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
            <div class="row d-flex align-items-center">
                <div  class="col-4">
                    <h5>New Academic Plan</h5>
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
                        <div class="step active">
                            1
                            <span class="step-label">First Year</span>
                        </div>
                        <div class="step">
                            2
                            <span class="step-label">Second Year</span>
                        </div>
                        <div class="step">
                            3
                            <span class="step-label">Third Year</span>
                        </div>
                        <div class="step">
                            4
                            <span class="step-label">Fourth Year</span>
                        </div>
                    </div>
                </div>
                    <div class="col-md-9 scrollable-content">
                        <form action="../database/addacademicplan.php" method="POST" id="wizardForm">
                            <input type="number" name="departmentid" value=<?php echo $departmentid;?> hidden>
                            <input type="number" name="semester" value=<?php echo $semester;?> hidden>
                            <input type="number" name="academicyear" value=<?php echo $academicyear;?> hidden>
                            <div class="step-content active" id="step1">
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
                                                <th data-sort="desc">Focus</th>
                                                <th> </th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadedSubjects1" class="list">

                                        </tbody>
                                    </table>
                                    </div>

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
                                <button type="button" class="btn btn-primary next-step">Next</button>
                            </div>
                            <div class="step-content" id="step2">
                            <div class="row">
                                    <label for="">Second Year Subjects</label>
                                    <div class="table-load my-3 p-3">
                                    <table class="table table-sm fs-9 mb-0">
                                        <thead>
                                            <tr>
                                                <th data-sort="subcode">Code</th>
                                                <th data-sort="desc">Description</th>
                                                <th data-sort="type">Type</th>
                                                <th data-sort="focus">Focus</th>
                                                <th data-sort="units">Units</th>
                                                <th> </th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadedSubjects2" class="list">

                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                <div class="row mt-3 d-flex justify-content-end my-2 p-3">

                                    <label for="">Select Subjects to Load</label>
                                    <div class="table-sub2 table-sub my-3 p-3">
                                        <table id="subjects2" class="table table-sm fs-9 mb-0">
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
                                                <?php foreach ($subject as $subjects){ ?>
                                                <tr>
                                                    <td class="align-middle subid" hidden><?php echo $subjects['subjectid'];?></td>
                                                    <td class="align-middle subcode"><?php echo $subjects['subjectcode']; ?></td>
                                                    <td class="align-middle desc"><?php echo $subjects['subjectname']; ?></td>
                                                    <td class="align-middle subtype"><?php echo $subjects['type']; ?></td>
                                                    <td class="align-middle subtype"><?php echo $subjects['unit']; ?></td>
                                                    <td class="align-middle subtype"><?php echo $subjects['focus']; ?></td>

                                                    <td class="align-middle">
                                                        <input type="checkbox" class="form-check-input load-subject-checkbox load-subject-checkbox2" data-subjectid2="<?php echo $subjects['subjectid']; ?>" data-subjectcode2="<?php echo $subjects['subjectcode']; ?>" data-subjectname2="<?php echo $subjects['subjectname']; ?>" data-type2="<?php echo $subjects['type']; ?>" data-unit2="<?php echo $subjects['unit']; ?>" data-focus2="<?php echo $subjects['focus']; ?>">
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                                <!-- Add more rows as needed -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                                <button type="button" class="btn next-step">Next</button>
                            </div>
                            <div class="step-content" id="step3">
                            <div class="row">
                                    <label for="">Third Year Subjects</label>
                                    <div class="table-load my-3 p-3">
                                    <table class="table table-sm fs-9 mb-0">
                                        <thead>
                                            <tr>
                                                <th data-sort="subcode">Code</th>
                                                <th data-sort="desc">Description</th>
                                                <th data-sort="type">Type</th>
                                                <th data-sort="focus">Focus</th>
                                                <th data-sort="units">Units</th>
                                                <th> </th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadedSubjects3" class="list">

                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                <div class="row mt-3 d-flex justify-content-end my-2 p-3">
                                    <div class="searchbar col-3">
                                        <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                                    </div>
                                    <label for="">Select Subjects to Load</label>
                                    <div class="table-sub3 table-sub my-3 p-3">
                                        <table id="subjects3" class="table table-sm fs-9 mb-0 ">
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
                                                <?php foreach ($subject as $subjects){ ?>
                                                <tr>
                                                    <td class="align-middle subid" hidden><?php echo $subjects['subjectid'];?></td>
                                                    <td class="align-middle subcode"><?php echo $subjects['subjectcode']; ?></td>
                                                    <td class="align-middle desc"><?php echo $subjects['subjectname']; ?></td>
                                                    <td class="align-middle subtype"><?php echo $subjects['type']; ?></td>
                                                    <td class="align-middle subtype"><?php echo $subjects['unit']; ?></td>
                                                    <td class="align-middle subtype"><?php echo $subjects['focus']; ?></td>

                                                    <td class="align-middle">
                                                        <input type="checkbox" class="form-check-input load-subject-checkbox3" data-subjectid3="<?php echo $subjects['subjectid']; ?>" data-subjectcode3="<?php echo $subjects['subjectcode']; ?>" data-subjectname3="<?php echo $subjects['subjectname']; ?>" data-type3="<?php echo $subjects['type']; ?>" data-unit3="<?php echo $subjects['unit']; ?>" data-focus3="<?php echo $subjects['focus']; ?>">
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                                <!-- Add more rows as needed -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                                <button type="button" class="btn btn-primary next-step">Next</button>
                            </div>
                            <div class="step-content" id="step4">
                            <div class="row">
                                    <label for="">Fourth Year Subjects</label>
                                    <div class="table-load my-3 p-3">
                                    <table class="table table-sm fs-9 mb-0">
                                        <thead>
                                            <tr>
                                                <th data-sort="subcode">Code</th>
                                                <th data-sort="desc">Description</th>
                                                <th data-sort="type">Type</th>
                                                <th data-sort="focus">Focus</th>
                                                <th data-sort="units">Units</th>
                                                <th> </th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadedSubjects4" class="list">

                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                <div class="row mt-3 d-flex justify-content-end my-2 p-3">
                                    <div class="searchbar col-3">
                                        <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                                    </div>
                                    <label for="">Forth Subjects to Load</label>
                                    <div class="table-sub4 table-sub my-3 p-3">
                                        <table id="subjects4" class="table table-sm fs-9 mb-0 ">
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
                                                <?php foreach ($subject as $subjects){ ?>
                                                <tr>
                                                    <td class="align-middle subid" hidden><?php echo $subjects['subjectid'];?></td>
                                                    <td class="align-middle subcode"><?php echo $subjects['subjectcode']; ?></td>
                                                    <td class="align-middle desc"><?php echo $subjects['subjectname']; ?></td>
                                                    <td class="align-middle subtype"><?php echo $subjects['type']; ?></td>
                                                    <td class="align-middle subtype"><?php echo $subjects['unit']; ?></td>
                                                    <td class="align-middle subtype"><?php echo $subjects['focus']; ?></td>

                                                    <td class="align-middle">
                                                        <input type="checkbox" class="form-check-input load-subject-checkbox4" data-subjectid4="<?php echo $subjects['subjectid']; ?>" data-subjectcode4="<?php echo $subjects['subjectcode']; ?>" data-subjectname4="<?php echo $subjects['subjectname']; ?>" data-type4="<?php echo $subjects['type']; ?>" data-unit4="<?php echo $subjects['unit']; ?>" data-focus4="<?php echo $subjects['focus']; ?>">
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                                <!-- Add more rows as needed -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                                <button type="submit" class="btn btn-submit">Submit</button>
                            </div>

                    </div>
                    </form>
                </div>
            </div>

        </div>
    </main>
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
