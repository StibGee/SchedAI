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
                <div class="text d-flex align-items-center ">
                    <h2> Hola !!! </h2> <span> Role</span>
                </div>
            </div>
            <div class="row d-flex align-items-center">
                <div  class="col-4">
                    <div class="row ">
                        <div class="col-6">
                            <a href="../admin/academic-plan.php" class="nav_links">
                                <span class="nav_acad">Academic Plan</span>
                            </a>
                        </div>
                        <div class="col-6 ">
                            <a href="../admin/subjects.php" class="nav_links">
                                <span class="nav_sub">Subjects</span>
                            </a>

                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="row  d-flex align-items-center justify-content-end">
                        <div class="department col-4">
                            <select class="form-select form-select-sm" id="select-department">
                                <option>Information Technology</option>
                                <option>Computer Science</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <select class="form-select form-select-sm" id="select-classtype">
                                <option>all</option>
                                <option>lec</option>
                                <option>lab</option>
                            </select>
                        </div>
                        <div class="col-3 d-flex align-items-center justify-content-start">
                            <button onclick="window.location.href='allocate-sub.php'"> <img src="../img/icons/add-icon.png" alt=""></button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="curriculum-sched mt-4">
                <table class="mb-0 table table-hover ">
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

    </main>
</body>

    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/academic-plan.css">
    <script src="../js/main.js"></script>
    <?php
        require_once('../include/js.php')
    ?>


</html>
