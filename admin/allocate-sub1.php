<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>
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
                    <div class="text d-flex align-items-center" >
                        <h2> Hola !!! </h2> <span> Role</span>
                    </div>
                </div>
            <div class="row d-flex align-items-center">
                <div  class="col-4">
                    <h5>New Academic Plan</h5>
                </div>

            </div>
            <form action="../database/addacademicplan.php" method="POST" id="wizardForm">
            <div class="container py-3">
                <div class="row">
                <div class="col-md-3 steps sticky-sidebar r">
                
                    <div class=" g-3 row year-level d-flex">
                        <div class=" col-6">
                        <label class="form-label " id="year-level-label">First Year</label>
                        </div>

                        <div id="sectionInputsContainer" class="col-6">
                            <div class="input-group col-6" data-year="1">
                                <input placeholder="No. of Sections" type="number" name="section1" id="section1" class="form-control form-control-sm" style="width: 120px;">
                            </div>
                            <div class="input-group col-6" data-year="2">
                                <input placeholder="No. of Sections" type="number" name="section2" id="section2" class="form-control form-control-sm" style="width: 120px;">
                            </div>
                            <div class="input-group col-6" data-year="3">
                                <input placeholder="No. of Sections" type="number" name="section3" id="section3" class="form-control form-control-sm" style="width: 120px;">
                            </div>
                            <div class="input-group col-6" data-year="4">
                                <input placeholder="No. of Sections" type="number" name="section4" id="section4" class="form-control form-control-sm" style="width: 120px;">
                            </div>
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
                                    
                                    <label for="">Select Subjects to Load</label>
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
                                                <?php foreach ($subject as $subjects){ ?>
                                                <tr>
                                                    <td class="align-middle subid" hidden><?php echo $subjects['subjectid'];?></td>
                                                    <td class="align-middle subcode"><?php echo $subjects['subjectcode']; ?></td>
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
                </div>
            </div>
            </form>
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
    document.querySelector('.table-sub1 tbody').addEventListener('change', function(e) {
        if (e.target.classList.contains('load-subject-checkbox1')) {
            const subjectCode1 = e.target.getAttribute('data-subjectcode1');
            const subjectid1 = e.target.getAttribute('data-subjectid1');
            const loadedSubjectsTable1 = document.getElementById('loadedSubjects1');

            if (e.target.checked) {
                const subjectName1 = e.target.getAttribute('data-subjectname1');
                const type1 = e.target.getAttribute('data-type1');
                const unit1 = e.target.getAttribute('data-unit1');
                const focus1 = e.target.getAttribute('data-focus1');

                const row = `
                    <tr data-subjectid1="${subjectid1}">
                        <td hidden><input type="text" name="subjectid1[]" value="${subjectid1}" class="form-control"></td>
                        <td>${subjectCode1}</td>
                        <td>${subjectName1}</td>
                        <td>${type1}</td>
                        <td>${unit1}</td>
                        <td>${focus1}</td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-subject1">Remove</button></td>
                    </tr>
                `;
                loadedSubjectsTable1.insertAdjacentHTML('beforeend', row);
            } else {
                // If unchecked, remove the corresponding row
                const rowToRemove = loadedSubjectsTable1.querySelector(`tr[data-subjectid1="${subjectid1}"]`);
                if (rowToRemove) {
                    rowToRemove.remove();
                }
            }
        }
    });

    document.getElementById('loadedSubjects1').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-subject1')) {
            const row = e.target.closest('tr');
            const subjectid1 = row.getAttribute('data-subjectid1');

            // Uncheck the corresponding checkbox in the "Select Subjects to Load" table
            const checkbox = document.querySelector(`.load-subject-checkbox1[data-subjectid1="${subjectid1}"]`);
            if (checkbox) {
                checkbox.checked = false;
            }
            row.remove();
        }
    });
    //subject2
    document.querySelector('.table-sub2 tbody').addEventListener('change', function(e) {
        if (e.target.classList.contains('load-subject-checkbox2')) {
            const subjectCode2 = e.target.getAttribute('data-subjectcode2');
            const subjectid2 = e.target.getAttribute('data-subjectid2');
            const loadedSubjectsTable2 = document.getElementById('loadedSubjects2');

            if (e.target.checked) {
                const subjectName2 = e.target.getAttribute('data-subjectname2');
                const type2 = e.target.getAttribute('data-type2');
                const unit2 = e.target.getAttribute('data-unit2');
                const focus2 = e.target.getAttribute('data-focus2');

                const row = `
                    <tr data-subjectid2="${subjectid2}">
                        <td hidden><input type="text" name="subjectid2[]" value="${subjectid2}" class="form-control"></td>
                        <td>${subjectCode2}</td>
                        <td>${subjectName2}</td>
                        <td>${type2}</td>
                        <td>${unit2}</td>
                        <td>${focus2}</td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-subject2">Remove</button></td>
                    </tr>
                `;
                loadedSubjectsTable2.insertAdjacentHTML('beforeend', row);
            } else {
                // If unchecked, remove the corresponding row
                const rowToRemove = loadedSubjectsTable2.querySelector(`tr[data-subjectid2="${subjectid2}"]`);
                if (rowToRemove) {
                    rowToRemove.remove();
                }
            }
        }
    });

    document.getElementById('loadedSubjects2').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-subject2')) {
            const row = e.target.closest('tr');
            const subjectid2 = row.getAttribute('data-subjectid2');

            // Uncheck the corresponding checkbox in the "Select Subjects to Load" table
            const checkbox = document.querySelector(`.load-subject-checkbox2[data-subjectid2="${subjectid2}"]`);
            if (checkbox) {
                checkbox.checked = false;
            }
            row.remove();
        }
    });
    //subject 3
    // For subject3
    document.querySelector('.table-sub3 tbody').addEventListener('change', function(e) {
        if (e.target.classList.contains('load-subject-checkbox3')) {
            const subjectCode3 = e.target.getAttribute('data-subjectcode3');
            const subjectid3 = e.target.getAttribute('data-subjectid3');
            const loadedSubjectsTable3 = document.getElementById('loadedSubjects3');

            if (e.target.checked) {
                const subjectName3 = e.target.getAttribute('data-subjectname3');
                const type3 = e.target.getAttribute('data-type3');
                const unit3 = e.target.getAttribute('data-unit3');
                const focus3 = e.target.getAttribute('data-focus3');

                const row = `
                    <tr data-subjectid3="${subjectid3}">
                        <td hidden><input type="text" name="subjectid3[]" value="${subjectid3}" class="form-control"></td>
                        <td>${subjectCode3}</td>
                        <td>${subjectName3}</td>
                        <td>${type3}</td>
                        <td>${unit3}</td>
                        <td>${focus3}</td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-subject3">Remove</button></td>
                    </tr>
                `;
                loadedSubjectsTable3.insertAdjacentHTML('beforeend', row);
            } else {
                // If unchecked, remove the corresponding row
                const rowToRemove = loadedSubjectsTable3.querySelector(`tr[data-subjectid3="${subjectid3}"]`);
                if (rowToRemove) {
                    rowToRemove.remove();
                }
            }
        }
    });

    document.getElementById('loadedSubjects3').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-subject3')) {
            const row = e.target.closest('tr');
            const subjectid3 = row.getAttribute('data-subjectid3');

            // Uncheck the corresponding checkbox in the "Select Subjects to Load" table
            const checkbox = document.querySelector(`.load-subject-checkbox3[data-subjectid3="${subjectid3}"]`);
            if (checkbox) {
                checkbox.checked = false;
            }
            row.remove();
        }
    });
    //subject 4
    // For subject4
    document.querySelector('.table-sub4 tbody').addEventListener('change', function(e) {
        if (e.target.classList.contains('load-subject-checkbox4')) {
            const subjectCode4 = e.target.getAttribute('data-subjectcode4');
            const subjectid4 = e.target.getAttribute('data-subjectid4');
            const loadedSubjectsTable4 = document.getElementById('loadedSubjects4');

            if (e.target.checked) {
                const subjectName4 = e.target.getAttribute('data-subjectname4');
                const type4 = e.target.getAttribute('data-type4');
                const unit4 = e.target.getAttribute('data-unit4');
                const focus4 = e.target.getAttribute('data-focus4');

                const row = `
                    <tr data-subjectid4="${subjectid4}">
                        <td hidden><input type="text" name="subjectid4[]" value="${subjectid4}" class="form-control"></td>
                        <td>${subjectCode4}</td>
                        <td>${subjectName4}</td>
                        <td>${type4}</td>
                        <td>${unit4}</td>
                        <td>${focus4}</td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-subject4">Remove</button></td>
                    </tr>
                `;
                loadedSubjectsTable4.insertAdjacentHTML('beforeend', row);
            } else {
                // If unchecked, remove the corresponding row
                const rowToRemove = loadedSubjectsTable4.querySelector(`tr[data-subjectid4="${subjectid4}"]`);
                if (rowToRemove) {
                    rowToRemove.remove();
                }
            }
        }
    });

    document.getElementById('loadedSubjects4').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-subject4')) {
            const row = e.target.closest('tr');
            const subjectid4 = row.getAttribute('data-subjectid4');

            // Uncheck the corresponding checkbox in the "Select Subjects to Load" table
            const checkbox = document.querySelector(`.load-subject-checkbox4[data-subjectid4="${subjectid4}"]`);
            if (checkbox) {
                checkbox.checked = false;
            }
            row.remove();
        }
    });

</script>

    


</html>

