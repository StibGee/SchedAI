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
                    <div class="text d-flex align-items-center" >
                        <h2> Hola !!! </h2> <span> Role</span>
                    </div>
                </div>
            <div class="row d-flex align-items-center">
                <div  class="col-4">
                    <h5>New Academic Plan</h5>
                </div>

            </div>
            <div class="container py-3">
                <div class="row">
                <div class="col-md-3 steps sticky-sidebar r">
                    <div class=" g-3 row year-level d-flex">
                        <div class=" col-6">
                        <label class="form-label " id="year-level-label">First Year</label>
                        </div>

                        <div class="col-6">
                            <input placeholder=" No. of Sections" type="number" class="form-control form-control-sm" style="width: 120px;">
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
                        <form id="wizardForm">
                            <div class="step-content active" id="step1">
                                <div class="row">
                                    <label for="">First Year Subjects</label>
                                    <div class="table-load my-3 p-3">
                                    <table class="table table-sm fs-9 mb-0 p-3">
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
                                        <tbody class="list">
                                            <tr>
                                                <td class="align-middle subcode">CS139</td>
                                                <td class="align-middle desc">Web Development</td>
                                                <td class="align-middle subtype">Lec/Lab</td>
                                                <td class="align-middle subtype">Major</td>
                                                <td class="align-middle unit">3.00</td>
                                            </tr>
                                            <!-- Add more rows as needed -->
                                        </tbody>
                                    </table>
                                    </div>

                                </div>
                                <div class="row mt-3 d-flex justify-content-end my-2 p-3">
                                    <div class="searchbar col-3">
                                        <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                                    </div>
                                    <label for="">Select Subjects to Load</label>
                                    <div class="table-sub my-3 p-3">
                                        <table class="table table-sm fs-9 mb-0 ">
                                            <thead>
                                                <tr>
                                                    <th data-sort="subcode">Code</th>
                                                    <th data-sort="desc">Description</th>
                                                    <th data-sort="desc">Focus</th>
                                                    <th data-sort="desc">Select All</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <tr>
                                                    <td class="align-middle subcode">CS139</td>
                                                    <td class="align-middle desc">Web Development</td>
                                                    <td class="align-middle subtype">Lec</td>
                                                    <td class="align-middle">
                                                        <input type="checkbox" class="form-check-input">
                                                    </td>
                                                </tr>
                                                <!-- Add more rows as needed -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary next-step">Next</button>
                            </div>
                            <div class="step-content" id="step2">
                            <div class="row">
                                    <label for="">First Year Subjects</label>
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
                                        <tbody class="list">
                                            <tr>
                                                <td class="align-middle subcode">CS139</td>
                                                <td class="align-middle desc">Web Development</td>
                                                <td class="align-middle subtype">Lec/Lab</td>
                                                <td class="align-middle subtype">Major</td>
                                                <td class="align-middle unit">3.00</td>
                                            </tr>
                                            <!-- Add more rows as needed -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mt-3 d-flex justify-content-end my-2 p-3">
                                    <div class="searchbar col-3">
                                        <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                                    </div>
                                    <label for="">Select Subjects to Load</label>
                                    <div class="table-sub my-3 p-3">
                                        <table class="table table-sm fs-9 mb-0 ">
                                            <thead>
                                                <tr>
                                                    <th data-sort="subcode">Code</th>
                                                    <th data-sort="desc">Description</th>
                                                    <th data-sort="desc">Focus</th>
                                                    <th data-sort="desc">Select All</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <tr>
                                                    <td class="align-middle subcode">CS139</td>
                                                    <td class="align-middle desc">Web Development</td>
                                                    <td class="align-middle subtype">Lec</td>
                                                    <td class="align-middle">
                                                        <input type="checkbox" class="form-check-input">
                                                    </td>
                                                </tr>
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
                                    <label for="">Second Year Subjects</label>
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
                                        <tbody class="list">
                                            <tr>
                                                <td class="align-middle subcode">CS139</td>
                                                <td class="align-middle desc">Web Development</td>
                                                <td class="align-middle subtype">Lec/Lab</td>
                                                <td class="align-middle subtype">Major</td>
                                                <td class="align-middle unit">3.00</td>
                                            </tr>
                                            <!-- Add more rows as needed -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mt-3 d-flex justify-content-end my-2 p-3">
                                    <div class="searchbar col-3">
                                        <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                                    </div>
                                    <label for="">Select Subjects to Load</label>
                                    <div class="table-sub my-3 p-3">
                                        <table class="table table-sm fs-9 mb-0 ">
                                            <thead>
                                                <tr>
                                                    <th data-sort="subcode">Code</th>
                                                    <th data-sort="desc">Description</th>
                                                    <th data-sort="desc">Focus</th>
                                                    <th data-sort="desc">Select All</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <tr>
                                                    <td class="align-middle subcode">CS139</td>
                                                    <td class="align-middle desc">Web Development</td>
                                                    <td class="align-middle subtype">Lec</td>
                                                    <td class="align-middle">
                                                        <input type="checkbox" class="form-check-input">
                                                    </td>
                                                </tr>
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
                                    <label for="">Third Year Subjects</label>
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
                                        <tbody class="list">
                                            <tr>
                                                <td class="align-middle subcode">CS139</td>
                                                <td class="align-middle desc">Web Development</td>
                                                <td class="align-middle subtype">Lec/Lab</td>
                                                <td class="align-middle subtype">Major</td>
                                                <td class="align-middle unit">3.00</td>
                                            </tr>
                                            <!-- Add more rows as needed -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mt-3 d-flex justify-content-end my-2 p-3">
                                    <div class="searchbar col-3">
                                        <input type="search" class="form-control" placeholder="Search..." aria-label="Search" data-last-active-input="">
                                    </div>
                                    <label for="">Forth Subjects to Load</label>
                                    <div class="table-sub my-3 p-3">
                                        <table class="table table-sm fs-9 mb-0 ">
                                            <thead>
                                                <tr>
                                                    <th data-sort="subcode">Code</th>
                                                    <th data-sort="desc">Description</th>
                                                    <th data-sort="desc">Focus</th>
                                                    <th data-sort="desc">Select All</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                <tr>
                                                    <td class="align-middle subcode">CS139</td>
                                                    <td class="align-middle desc">Web Development</td>
                                                    <td class="align-middle subtype">Lec</td>
                                                    <td class="align-middle">
                                                        <input type="checkbox" class="form-check-input">
                                                    </td>
                                                </tr>
                                                <!-- Add more rows as needed -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary prev-step">Previous</button>
                                <button type="button" class="btn btn-submit"onclick="window.location.href='setup-acadplan.php'">Submit</button>
                            </div>
                        </form>
                    </div>
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
        require_once('../include/js.php')
    ?>


</html>
