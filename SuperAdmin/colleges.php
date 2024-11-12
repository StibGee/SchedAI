<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body>

    <?php
        $_SESSION['currentpage']='colleges';
        require_once('../include/admin-nav.php');
        require_once('../classes/db.php');
        require_once('../classes/college.php');

        $db = new Database();
        $pdo = $db->connect();

        $college = new College($pdo);
        $allcollege = $college->getallcollege();
        
    ?>
<main>
<div class="container mb-1">
            <div class="row d-flex align-items-center">
                <div class="col-9">
                    <h3>
                        <button class="button" onclick="window.location.href='landing.php'">
                            <i class="fa-solid fa-circle-arrow-left"></i>
                        </button>
                        Colleges
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
                            <th>No.</th>
                            <th>College</th>
                            <th>Abbreviation</th>
                          
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $count=1; foreach ($allcollege as $allcolleges): ?>
                        
                        
                            <tr onclick="submitCollegeForm('<?php echo $allcolleges['id']; ?>')">
                                
                                <td><?php echo $count; ?></td>
                                <td><?php echo $allcolleges['name']; ?></td>
                                <td><?php echo $allcolleges['abbreviation']; ?></td>
                                <td>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editcollegeform<?php echo $allcolleges['id']; ?>" onclick="event.stopPropagation();">Edit</button>
                                    <form action="../processing/collegeprocessing.php" method="post" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $allcolleges['id']; ?>">
                                        <button type="submit" class="btn btn-danger" onclick="event.stopPropagation(); return confirm('Are you sure you want to delete this college?');">Delete</button>
                                    </form>
                                </td>
                            </tr> 
                            <!-- edit modal-->
                            <div class="modal fade" id="editcollegeform<?php echo $allcolleges['id']; ?>" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg mt-6" role="document">
                                <div class="modal-content border-0">
                                    <div class="modal-header border-0">
                                        <h4 class="modal-title" id="formModalLabel">Add New College</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body px-5">
                                        <form action="../processing/collegeprocessing.php" method="POST">
                                            <input type="text" value="editcollege" name="action" hidden>
                                            <input type="text" value="<?php echo $allcolleges['id']; ?>" name="collegeid" hidden>
                                            
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="collegename">Input College Name</label>
                                                        <input class="form-control" id="collegename" type="text" name="collegename" value="<?php echo $allcolleges['name']; ?>" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="abbriviation">Abbreviation</label>
                                                        <input class="form-control" id="collegename" type="text" name="abbreviation" value="<?php echo $allcolleges['abbreviation']; ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-between">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success">Done</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                                                


                    <?php $count+=1; endforeach; ?>
                </tbody>

                </table>
            </div>
        </div>
        
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg mt-6" role="document">
            <div class="modal-content border-0">
                <div class="modal-header border-0">
                    <h4 class="modal-title" id="formModalLabel">Add New College</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5">
                    <form action="../processing/collegeprocessing.php" method="POST">
                        <input type="text" value="add" name="action" hidden>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="collegename">Input College Name</label>
                                    <input class="form-control" id="collegename" type="text" name="collegename" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="abbriviation">Abbreviation</label>
                                    <input class="form-control" id="abbreviation" type="text" name="abbreviation" required />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Done</button>
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
    <script>
    function submitCollegeForm(collegeId) {
    
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../SuperAdmin/department.php';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'collegeid';
        input.value = collegeId;

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
</script>