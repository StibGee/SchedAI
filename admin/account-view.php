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
        <div class="container mb-5">
            <div class="row">
                <div class="text d-flex align-items-center ">
                    <h2> Hola !!! </h2> <span> Role</span>
                </div>
            </div>
            <div class="row mt-4 d-flex align-items-center">
                <div class="header-table col-3">
                    <h3>Accounts</h3>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="position" required="">
                        <option selected="" disabled="" value="">Choose...</option>
                        <option>Dean</option>
                        <option>Visiting Lecturer</option>
                      </select>
                </div>

                <div class="searchbar col-3">
                    <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                </div>
                <div class="col-2 add-account d-flex justify-content-end">
               
                    <button class="add-subject" data-bs-toggle="modal" data-bs-target="#formModal"><i class="fa-solid fa-folder-plus custom-icon-size"></i></button>
                    
                </div>

            </div>

            <div class="account my-4 p-3">
                <div class="account-table ">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Account Id</th>
                                    <th>Name</th>
                                    <th>Rank</th>
                                    <th>Email</th>
                                    <th>access</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody id="tabularTableBody">
                                <tr>
                                    <td>1</td>
                                    <td>qb100</td>
                                    <td>Web Waldo</td>
                                    <td>Dean</td>
                                    <td>Admin</td>
                                    <td>waldo@gmail.com</td>
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
             <!-- Modal Form -->
            <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg mt-6" role="document">
                    <div class="modal-content border-0">
                        <div class="modal-body p-3">
                            <div class="position-absolute top-0 end-0 mt-3 me-3 z-1">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                           
                            </div>
                            <div class="rounded-top-3 form p-4">
                                <h2 class="head-label">Add Account</h2>
                                <div class="container form ">
                                    <form id="facultyForm" class="row g-3 mt-4 needs-validation" novalidate="">

                                        <div class="row mt-3">
                                            <div class="col-6">
                                                <label class="form-label" for="access">Faculty Access</label>
                                                <select class="form-select" id="access" required="">
                                                    <option selected="" disabled="" value="">Choose...</option>
                                                    <option>admin</option>
                                                    <option>Users</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-5">
                                                <label class="form-label" for="name">First Name</label>
                                                <input class="form-control" id="name" type="text" required />
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label" for="surname">Surname</label>
                                                <input class="form-control" id="surname" type="text" required />
                                            </div>
                                            <div class="col-2">
                                                <label class="form-label" for="middleinit">MI</label>
                                                <input class="form-control" id="middleinit" type="text" optional />
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <label class="form-label" for="email">Email</label>
                                                <input class="form-control" id="email" type="email" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label" for="password">Passowrd</label>
                                                <input class="form-control" id="password" type="password" required />
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="cancel" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                <button type="button" class="add" onclick="window.location.href='account-view.php'">Done</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>
</body>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/accounts.css">
    <script src="../js/main.js"></script>
    <?php
        require_once('../include/js.php')
    ?>
</html>
