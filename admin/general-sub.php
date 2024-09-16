<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>
<body >

    <?php
        require_once('../include/nav.php');
        require_once('../database/datafetch.php');

        $departmentid=1;
        $semester=1;
        $academicyear=1;

    ?>
    <main>
        <div class="container mb-1">
            <div class="row d-flex align-items-center">
                <div  class="col-4">
                <h5>General Subjects for <?php echo ($departmentid == 1 ? 'BSCS ' : 'IT ') . ($semester == 1 ? '1st Sem' : '2nd Sem'); ?></h5>
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
                            <input type="number" name="departmentid" value='<?php echo $departmentid;?>' hidden>
                            <input type="number" name="semester" value='<?php echo $semester;?>' hidden>
                            <input type="number" name="academicyear" value='<?php echo $academicyear;?>' hidden>
                            <div class="step-content active" id="step1">
                                <div class="row mt-3 d-flex justify-content-end my-2 p-3">
                                    <label for="">Set up Schedule for General Subjects</label>
                                    <div class="table-load my-3 p-3">
                                    <table id="" class="table table-sm fs-9 mb-0 p-3 text-center">
                                        <div class="generalsubjects">HIST101</div>
                                        <thead>
                                            <tr>
                                                <th>Sections</th>
                                                <th>Description</th>
                                                <th>Unit</th>
                                                <th>Day</th>
                                                <th>Time</th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadedSubjects1" class="list">
                                            <tr>
                                                <td>CS1A</td>
                                                <td>Rizal Works</td>
                                                <td>3.0</td>
                                                <td>
                                                    <select>
                                                        <option value="monday">Monday</option>
                                                        <option value="tuesday">Tuesday</option>
                                                        <option value="wednesday">Wednesday</option>
                                                        <option value="thursday">Thursday</option>
                                                        <option value="friday">Friday</option>
                                                        <option value="saturday">Saturday</option>
                                                    </select>
                                                    <select>
                                                        <option value="monday">Monday</option>
                                                        <option value="tuesday">Tuesday</option>
                                                        <option value="wednesday">Wednesday</option>
                                                        <option value="thursday">Thursday</option>
                                                        <option value="friday">Friday</option>
                                                        <option value="saturday">Saturday</option>
                                                    </select>
                                                </td>
                                                <td><input type="time" class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <td>CS1B</td>
                                                <td>Rizal Works</td>
                                                <td>3.0</td>
                                                <td>
                                                    <select>
                                                        <option value="monday">Monday</option>
                                                        <option value="tuesday">Tuesday</option>
                                                        <option value="wednesday">Wednesday</option>
                                                        <option value="thursday">Thursday</option>
                                                        <option value="friday">Friday</option>
                                                        <option value="saturday">Saturday</option>
                                                    </select>
                                                    <select>
                                                        <option value="monday">Monday</option>
                                                        <option value="tuesday">Tuesday</option>
                                                        <option value="wednesday">Wednesday</option>
                                                        <option value="thursday">Thursday</option>
                                                        <option value="friday">Friday</option>
                                                        <option value="saturday">Saturday</option>
                                                    </select>
                                                </td>
                                                <td><input type="time" class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <td>CS1C</td>
                                                <td>Rizal Works</td>
                                                <td>3.0</td>
                                                <td>
                                                    <select>
                                                        <option value="monday">Monday</option>
                                                        <option value="tuesday">Tuesday</option>
                                                        <option value="wednesday">Wednesday</option>
                                                        <option value="thursday">Thursday</option>
                                                        <option value="friday">Friday</option>
                                                        <option value="saturday">Saturday</option>
                                                    </select>
                                                    <select>
                                                        <option value="monday">Monday</option>
                                                        <option value="tuesday">Tuesday</option>
                                                        <option value="wednesday">Wednesday</option>
                                                        <option value="thursday">Thursday</option>
                                                        <option value="friday">Friday</option>
                                                        <option value="saturday">Saturday</option>
                                                    </select>
                                                </td>
                                                <td><input type="time" class="form-control"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                                <button type="button" class="btn btn-primary next-step">Next</button>
                            </div>
                            <div class="step-content" id="step2">
                            <div class="row mt-3 d-flex justify-content-end my-2 p-3">
                                    <label for="">Set up Schedule for General Subjects</label>
                                    <div class="table-load my-3 p-3">
                                    <table id="" class="table table-sm fs-9 mb-0 p-3 text-center">
                                        <div class="generalsubjects">HIST101</div>
                                        <thead>
                                            <tr>
                                                <th>Sections</th>
                                                <th>Description</th>
                                                <th>Unit</th>
                                                <th>Day</th>
                                                <th>Time</th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadedSubjects1" class="list">
                                            <tr>
                                                <td>CS2A</td>
                                                <td>Rizal Works</td>
                                                <td>3.0</td>
                                                <td>
                                                    <select>
                                                        <option value="monday">Monday</option>
                                                        <option value="tuesday">Tuesday</option>
                                                        <option value="wednesday">Wednesday</option>
                                                        <option value="thursday">Thursday</option>
                                                        <option value="friday">Friday</option>
                                                        <option value="saturday">Saturday</option>
                                                    </select>
                                                    <select>
                                                        <option value="monday">Monday</option>
                                                        <option value="tuesday">Tuesday</option>
                                                        <option value="wednesday">Wednesday</option>
                                                        <option value="thursday">Thursday</option>
                                                        <option value="friday">Friday</option>
                                                        <option value="saturday">Saturday</option>
                                                    </select>
                                                </td>
                                                <td><input type="time" class="form-control"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                                <button type="button" class="btn next-step">Next</button>
                            </div>
                            <div class="step-content" id="step3">
                            
                            <div class="row mt-3 d-flex justify-content-end my-2 p-3">
                                    <label for="">Set up Schedule for General Subjects</label>
                                    <div class="table-load my-3 p-3">
                                    <table id="" class="table table-sm fs-9 mb-0 p-3 text-center">
                                        <div class="generalsubjects">HIST101</div>
                                        <thead>
                                            <tr>
                                                <th>Sections</th>
                                                <th>Description</th>
                                                <th>Unit</th>
                                                <th>Day</th>
                                                <th>Time</th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadedSubjects1" class="list">
                                            <tr>
                                                <td>CS3A</td>
                                                <td>Rizal Works</td>
                                                <td>3.0</td>
                                                <td>
                                                    <select>
                                                        <option value="monday">Monday</option>
                                                        <option value="tuesday">Tuesday</option>
                                                        <option value="wednesday">Wednesday</option>
                                                        <option value="thursday">Thursday</option>
                                                        <option value="friday">Friday</option>
                                                        <option value="saturday">Saturday</option>
                                                    </select>
                                                    <select>
                                                        <option value="monday">Monday</option>
                                                        <option value="tuesday">Tuesday</option>
                                                        <option value="wednesday">Wednesday</option>
                                                        <option value="thursday">Thursday</option>
                                                        <option value="friday">Friday</option>
                                                        <option value="saturday">Saturday</option>
                                                    </select>
                                                </td>
                                                <td><input type="time" class="form-control"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                                <button type="button" class="btn btn-primary next-step">Next</button>
                            </div>
                            <div class="step-content" id="step4">
                            <div class="row mt-3 d-flex justify-content-end my-2 p-3">
                                    <label for="">Set up Schedule for General Subjects</label>
                                    <div class="table-load my-3 p-3">
                                    <table id="" class="table table-sm fs-9 mb-0 p-3 text-center">
                                        <div class="generalsubjects">HIST101</div>
                                        <thead>
                                            <tr>
                                                <th>Sections</th>
                                                <th>Description</th>
                                                <th>Unit</th>
                                                <th>Day</th>
                                                <th>Time</th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadedSubjects1" class="list">
                                            <tr>
                                                <td>CS4A</td>
                                                <td>Rizal Works</td>
                                                <td>3.0</td>
                                                <td>
                                                    <select>
                                                        <option value="monday">Monday</option>
                                                        <option value="tuesday">Tuesday</option>
                                                        <option value="wednesday">Wednesday</option>
                                                        <option value="thursday">Thursday</option>
                                                        <option value="friday">Friday</option>
                                                        <option value="saturday">Saturday</option>
                                                    </select>
                                                    <select>
                                                        <option value="monday">Monday</option>
                                                        <option value="tuesday">Tuesday</option>
                                                        <option value="wednesday">Wednesday</option>
                                                        <option value="thursday">Thursday</option>
                                                        <option value="friday">Friday</option>
                                                        <option value="saturday">Saturday</option>
                                                    </select>
                                                </td>
                                                <td><input type="time" class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <td>CS4B</td>
                                                <td>Rizal Works</td>
                                                <td>3.0</td>
                                                <td>
                                                    <select>
                                                        <option value="monday">Monday</option>
                                                        <option value="tuesday">Tuesday</option>
                                                        <option value="wednesday">Wednesday</option>
                                                        <option value="thursday">Thursday</option>
                                                        <option value="friday">Friday</option>
                                                        <option value="saturday">Saturday</option>
                                                    </select>
                                                    <select>
                                                        <option value="monday">Monday</option>
                                                        <option value="tuesday">Tuesday</option>
                                                        <option value="wednesday">Wednesday</option>
                                                        <option value="thursday">Thursday</option>
                                                        <option value="friday">Friday</option>
                                                        <option value="saturday">Saturday</option>
                                                    </select>
                                                </td>
                                                <td><input type="time" class="form-control"></td>
                                            </tr>
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

    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/allocate.css">
    <script src="../js/main.js"></script>
    <script src="../js/allocate.js"></script>
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