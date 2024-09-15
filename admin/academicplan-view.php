<!DOCTYPE html>
<html lang="en">
<body >

    <?php
        require_once('../include/nav.php');

    ?>
    <main>
        <div class="container mb-1">
            <div class="row">
                <div  class="col-4">
                    <div class="row ">
                        <h5>
                        <button onclick="window.location.href='academic-plan.php'">
                                <i class="fa-solid fa-circle-arrow-left"></i>
                            </button>
                            Academic Plan <span>Sy</span>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="container py-3">
                <div class="row d-flex justify-content-end">
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
                                <label for="">First Year Subjects Loaded</label>
                                <div class="table-load my-3 p-3">
                                    <table id="" class="table table-sm fs-9 mb-0 p-3">
                                        <thead>
                                            <tr>
                                                <th data-sort="subcode">Code</th>
                                                <th data-sort="desc">Description</th>
                                                <th data-sort="desc">Type</th>
                                                <th data-sort="desc">Unit</th>
                                                <th data-sort="desc">Time</th>
                                                <th data-sort="desc">Focus</th>

                                            </tr>
                                        </thead>
                                        <tbody id="loadedSubjects1" class="list">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
