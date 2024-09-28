<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body>

    <?php

        require_once('../include/admin-nav.php');
        require_once('../database/datafetch.php');
    ?>
<main>
<div class="container mb-1">
            <div class="row d-flex align-items-center">
                <div class="col-9">
                    <h3>
                        <button class="button" onclick="window.location.href='landing.php'">
                            <i class="fa-solid fa-circle-arrow-left"></i>
                        </button>
                        List of Colleges
                    </h3>
                </div>
                <div class="col-3 d-flex align-items-center justify-content-start">
                        <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                        </div>
            </div>

            <div class="colleges mt-4">

                <table class="mb-0 table table-hover">
                    <thead>
                        <tr>
                            <th>College</th>
                            <th>Abbreviation</th>
                            <th>Year Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-href="../SuperAdmin/department.php">
                            <td>College of Computing Studies</td>
                            <td>CCS</td>
                            <td>2023</td>
                            <td ><img src="../img/icons/view.png" alt=""><img src="../img/icons/delete.png" alt=""> </td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal Form -->
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mt-6" role="document">
            <div class="modal-content border-0">
                <div class="modal-header border-0">
                    <h4 class="modal-title" id="formModalLabel">Add New Curriculum</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5">
                    <form action="../processing/curriculumprocessing.php" method="POST">
                        <input type="text" value="add" name="action" hidden>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="startyear">Enter Year Created</label>
                                <div class="input-group mt-2">
                                    <input type="number" name="academicyear" id="startyear" class="form-control form-control-sm" style="width: 120px;">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="subname">Input College Name</label>
                                    <input class="form-control" id="subname" type="text" name="subjectname" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="abbriviation">Abbreviation</label>
                                    <input class="form-control" id="subname" type="text" name="subjectname" required />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Done</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

</main>
</body>
<link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/superadmin/dashboard.css">
    <script src="../js/facultyloading.js"></script>
    <?php
        require_once('../include/js.php')
    ?>
</html>
<script>
        document.addEventListener("DOMContentLoaded", function() {
            const rows = document.querySelectorAll("tbody tr[data-href]");
            rows.forEach(row => {
                row.addEventListener("click", function() {
                    window.location.href = this.getAttribute("data-href");
                });
            });
        });
    </script>
