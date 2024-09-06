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
            <div class="row d-flex align-items-center">
                <div  class="col-4">
                    <div class="row ">
                    <h5>
                        <button onclick="window.location.href='academic-plan.php'">
                            <i class="fa-solid fa-circle-arrow-left"></i>
                        </button>
                        Curriculum Plan <span>SY-</span> <span>Year</span>
                    </h5>
                    </div>
                </div>
                <div class="col-8">
                    <div class="row  d-flex align-items-center justify-content-end">
                        <div class="department col-4">
                            <select class="form-select form-select-sm p-2" id="select-department">
                                <option>Information Technology</option>
                                <option>Computer Science</option>
                            </select>
                        </div>
                        <div class="col-5">
                            <div class="searchbar ">
                                <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="academic-view mt-4 p-3">
                <table class="mb-0 table table-hover ">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th >Description</th>
                            <th >Type</th>
                            <th >Unit</th>
                            <th >Focus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Code</td>
                            <td >Description</td>
                            <td >Type</td>
                            <td >Unit</td>
                            <td >Focus</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</body>

    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/academic-view.css">
    <script src="../js/main.js"></script>
    <?php
        require_once('../include/js.php')
    ?>


</html>
