<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="/assets/css/add-edit-faculty.css">

</head>

<?php
    require_once('./include/nav.php');
?>
    <main>
        <div class="container ">
            <div class="row">
                <div class="text d-flex align-items-center ">
                    <h2> Hola !!! </h2> <span> Role</span>
                </div>
            </div>
            <div class="row  d-flex align-items-center">
                <div class="header-table col-3">
                    <h3>Add Faculty</h3>
                </div>

            </div>
            <div class="container form  p-4 m-4 ">
                <form id="facultyForm" class="row g-3 needs-validation" novalidate="">
                    <h5>Personal Information</h5>
                    <div class="row mt-2">
                      <div class="col-md-5">
                        <label class="form-label" for="firstname">First name</label>
                        <input class="form-control" id="firstname" type="text" required="" />
                        <div class="valid-feedback">Looks good!</div>
                      </div>
                      <div class="col-md-5">
                        <label class="form-label" for="lastname">Last name</label>
                        <input class="form-control" id="lastname" type="text" required="" />
                        <div class="valid-feedback">Looks good!</div>
                      </div>
                      <div class="col-md-2">
                        <label class="form-label" for="midleinit">MI</label>
                        <input class="form-control" id="midleinit" type="text" required="" />
                        <div class="valid-feedback">Looks good!</div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-6">
                        <label class="form-label" for="contactnumber">Contact Number</label>
                        <input class="form-control" id="contactnumber" type="tel" required />
                        <div class="valid-feedback">Looks good!</div>
                      </div>
                      <div class="col-md-4">
                        <label class="form-label" for="birthdate">Birthdate</label>
                        <input class="form-control" id="birthdate" type="date" required />
                        <div class="valid-feedback">Looks good!</div>
                      </div>
                      <div class="col-md-2">
                        <label class="form-label" for="gender">Gender</label>
                        <select class="form-select" id="gender" required="">
                          <option selected="" disabled="" value="">Choose...</option>
                          <option>Male</option>
                          <option>Female</option>
                        </select>
                        <div class="invalid-feedback">Please select a Gender</div>
                      </div>
                    </div>

                    <h5>Account Details</h5>
                    <div class="row mt-2">
                      <div class="col-md-6">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" id="email" type="email" required />
                        <div class="valid-feedback">Looks good!</div>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-control" id="password" type="password" required />
                        <div class="valid-feedback">Looks good!</div>
                      </div>
                    </div>

                    <h5>Faculty Information</h5>
                    <div class="row mt-2">
                      <div class="col-6">
                        <label class="form-label" for="position">Rank/Position</label>
                        <select class="form-select" id="position" required="">
                          <option selected="" disabled="" value="">Choose...</option>
                          <option>Dean</option>
                          <option>Visiting Lecturer</option>
                        </select>
                        <div class="invalid-feedback">Please select a Gender</div>
                      </div>
                      <div class="col-6">
                        <label class="form-label" for="startdate">Start Date</label>
                        <input class="form-control" id="startdate" type="date" required />
                        <div class="valid-feedback">Looks good!</div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-6">
                        <label class="form-label" for="department">Deparment</label>
                        <select class="form-select" id="department" required="">
                          <option selected="" disabled="" value="">Choose...</option>
                          <option>Information Technology</option>
                          <option>Computer Science</option>
                        </select>
                        <div class="invalid-feedback">Please select Department</div>
                      </div>
                      <div class="col-6">
                        <label class="form-label" for="teachinghours">Teaching Hours</label>
                        <input class="form-control" id="teachinghours" type="number" required placeholder="Hours/Week" />
                      </div>
                    </div>

                    <div class="button col-12 d-flex justify-content-end mt-4">
                        <button type="button" class="cancel" onclick="window.location.href='faculty.php'">Cancel</button>
                        <button type="button" class="add" onclick="window.location.href='faculty.php'">Done</button>
                    </div>
                  </form>
            </div>

        </div>
    </main>
</body>

<script src="/assets/js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</html>
