<!DOCTYPE html>
<html lang="en">
<?php
        require_once('../include/head.php');
    ?>

<body>

    <?php

        require_once('../include/user-mainnav.php');
        require_once('../database/datafetch.php');
    ?>
            <main>
            <div class="container ">
                <div class="row">
                    <div class="text d-flex align-items-center" >
                        <h2> Hola !!! </h2> <span> Role</span>
                    </div>
                </div>
                <h5>My Account</h5>
                <div class="container mt-5">
                    <form>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Password">
                        </div>
                        <div class="form-group forgot-password">
                            <a href="#">Change password?</a>
                        </div>

                    </form>
                </div>
            </div>
        </main>
</body>
<script src="../js/main.js"></script>
<link rel="stylesheet" href="../css/main.css">
<link rel="stylesheet" href="../css/faculty-css/user-account.css">

<?php
        require_once('../include/js.php')
    ?>


</html>
