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
                        <button class="button" onclick="window.location.href='colleges.php'">
                            <i class="fa-solid fa-circle-arrow-left"></i>
                        </button>
                        College of Computing Studies
                    </h3>
                </div>
                <div class="col-3 d-flex align-items-center justify-content-start">
                        <button class="button-modal " data-bs-toggle="modal" data-bs-target="#formModal"><img src="../img/icons/add-icon.png" alt=""></button>
                        </div>
            </div>

            <div class="colleges mt-4">
                <table>
                    <thead>
                        <tr>
                            <th>Seal</th>
                            <th>College</th>
                            <th>Department</th>
                            <th>Year Created</th>
                            <th>Schedule</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-href="college_details.html">
                            <td class="seal-cell">img</td>
                            <td>College of Computing Studies</td>
                            <td>Computer Science</td>
                            <td>2023</td>
                            <td></td>
                            <td>
                                <div class="action-dropdown">
                                    <button class="action-button">...</button>
                                    <div class="action-dropdown-content">
                                        <a href="#">View Faculty</a>
                                        <a href="#">View Schedule</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="subname">Input College Name</label>
                                    <input class="form-control" id="subname" type="text" name="subjectname" required />
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dept">Enter Department</label>
                                <div class="input-group mt-2">
                                    <input type="number" name="academicyear" id="startyear" class="form-control form-control-sm" style="width: 120px;">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="abbriviation">Year Created</label>
                                    <input class="form-control" id="subname" type="number" name="subjectname" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="profile-image">Upload Seal</label>
                                    <div class="profile-image-container">
                                        <label for="profile-image">
                                            <img id="profile-image-preview" src="../img/icons/upload.png" alt="Profile Image" />
                                        </label>
                                        <input type="file" id="profile-image" name="profile-image" accept="image/*" style="display: none;" />
                                    </div>
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
    <link rel="stylesheet" href="../css/superadmin/department.css">
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
        document.getElementById('profile-image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-image-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
