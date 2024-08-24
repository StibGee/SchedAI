<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body >

    <?php
        require_once('../include/nav.php');
        require_once('../database/datafetch.php');
    ?>
    <main>
        <div class="container mb-1">
            <div class="row">
                <div class="text d-flex align-items-center">
                    <h2> Hola !!! </h2> <span> Role</span>
                </div>
            </div>
            <div class="row d-flex align-items-center">
                <div class="col-5">
                    <h3>Prospectus</h3>
                </div>
                <div class="department col-3">
                    <select class="form-select form-select-sm" id="select-department">
                        <option>Information Technology</option>
                        <option>Computer Science</option>
                    </select>
                </div>
                <div class="col-1">
                    <select class="form-select form-select-sm" id="select-classtype">
                        <option>all</option>
                        <option>lec</option>
                        <option>lab</option>
                    </select>
                </div>
                <div class="col-2 d-flex justify-content-end">
                    <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                </div>
            </div>
            <div class="curriculum-sched mt-4">
                <table class="mb-0 table table-hover">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Period</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($calendar AS $calendars){ ?>
                        <tr>
                            <th scope="row"><?php echo $calendars['name'];?></th>
                            <td><?php if ($calendars['sem']==1){ echo '1st semester';} else{ echo '2nd semester';}?></td>
                          
                            <td>
                                <div class="actions">
                                    <i class="fas fa-edit"></i>
                                    <i class="fas fa-trash"></i>
                                    <i class="fas fa-eye"></i>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Form -->
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mt-6" role="document">
                <div class="modal-content border-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="formModalLabel">Modal Title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="rounded-top-3 bg-body-tertiary p-4">
                            <h2 class="head-label">Add School Year  </h2>
                            <div class="container mt-4">
                            <form id="facultyForm" action="../database/addyear.php" method="POST" novalidate="">
                                    <div class="row">
                                        
                                    <div class="col-md-3">
                                        <label class="form-label" for="birthyear">Year</label>
                                        <div class="d-flex column">
                                            <input class="form-control" id="start" name="startyear" type="number" min="1900" max="2099" required>
                                            <p class="ms-1 me-1 text-center mb-0 align-center">-</p>

                                            <input class="form-control" id="end" name="endyear" type="number" min="1900" max="2099" required>
                                        </div>
                                        
                                        <div class="valid-feedback">Looks good!</div>
                                    </div>

                                    </div>
                                
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">

                            <button type="button" class="cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="confirm">Done</button>
                            </form>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </main>
</body>

    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/sched.css">
    <script src="../js/main.js"></script>
    <?php
        require_once('../include/js.php')
    ?>

<script>
    document.getElementById('start').addEventListener('input', function() {
        const startYear = parseInt(this.value);
        if (!isNaN(startYear) && startYear >= 1900 && startYear < 2099) {
            document.getElementById('end').value = startYear + 1;
        } else {
            document.getElementById('end').value = '';  // Clear the end year if the input is invalid
        }
    });

    document.getElementById('end').addEventListener('input', function() {
        const endYear = parseInt(this.value);
        if (!isNaN(endYear) && endYear > 1900 && endYear <= 2100) {
            document.getElementById('start').value = endYear - 1;
        } else {
            document.getElementById('start').value = '';  // Clear the start year if the input is invalid
        }
    });
</script>

</html>
