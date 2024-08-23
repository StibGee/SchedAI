<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body >

    <?php
        require_once('../include/nav.php');
    ?>

    <main>
        <div class="container mb-1">
            <div class="row">
                <div class="text d-flex align-items-center ">
                    <h2> Hola !!! </h2> <span> Role</span>
                </div>
            </div>
            <div class="row  d-flex align-items-center">
                <div class="header-table col-3">
                    <h3>Instructors</h3>
                </div>
                    <div class="col-3">
                        <select class="form-select form-select-sm col" id="select-department">
                            <option>Institute of Technology</option>
                            <option>Computer Science</option>
                        </select>
                    </div>
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
                    <div class="col-2 add-faculty d-flex justify-content-center">

                        <button id="add-faculty" onclick="window.location.href='add-faculty.php'"><i class="fa-solid fa-folder-plus custom-icon-size"></i></button>
                    </div>

            </div>

            <div class="sched-container my-4 p-3">
                <div class="sched-table ">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Id Number</th>
                                    <th>Department</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Contact No.</th>
                                    <th>Gender</th>
                                    <th>Start Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tabularTableBody">
                                <tr>
                                    <td>qb001</td>
                                    <td>Computer Science</td>
                                    <td>Rouel Sebastian Quilantang</td>
                                    <td>Cloutchase</td>
                                    <td>09*******</td>
                                    <td>male</td>
                                    <td>june 17, 1987</td>
                                    <td>
                                        <button type="button" id="dropdownMenuButton" class="btn-dots" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                            <li><a class="dropdown-item" href="#">Edit</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                    </div>

                </div>

            </div>
        </div>
    </main>
</body>
<link rel="stylesheet" href="../css/main.css">
<link rel="stylesheet" href="../css/faculty.css">
<script src="../js/faculty.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</html>
