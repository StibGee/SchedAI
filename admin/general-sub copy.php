<!DOCTYPE html>
<html lang="en">
<?php
    require_once('../include/head.php');
?>
<link rel="stylesheet" href="../css/main.css">
<link rel="stylesheet" href="../css/general-sub.css">
<script src="../js/schedule.js"></script>

<body>
    <?php
        require_once('../include/nav.php');
    ?>
    <main>
    <div class="container mb-1">
            <div class="row">
                <h4>General Subjects</h4>
            </div>

            <div class="container py-3">
                <div class="row d-flex justify-content-end">
                    <div class="col-3 d-flex justify-content-between ">
                        <button type="button" class="cancel" >Cancel</button>
                        <button type="submit" class="confirm">Done</button>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-3 steps sticky-sidebar ">
                        <h5>Year Level</h5>
                        <div class="nav d-flex align-items-center mt-3 text-center">

                            <li>First Year</li>
                        </div>
                    </div>
                    <div class="col-md-9 scrollable-content">
                            <div class="row">
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
                                    </tbody>
                                </table>
                                </div>
                            </div>
                </div>

        </div>
    </main>
</body>



<?php
    require_once('../include/js.php');
?>

</html>

