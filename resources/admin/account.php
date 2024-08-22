<!DOCTYPE html>
<html lang="en">
<?php
        require_once('./include/head.php');
    ?>

<body >

    <?php
        require_once('./include/nav.php');
    ?>
    <main>
        <div class="container mb-5">
            <div class="row">
                <div class="text  d-flex align-items-center ">
                    <h2> Hola !!!  <span> Role</span></h2>
                </div>
            </div>
            <div class="header-table ">
                <h3>Accounts</h3>
                <p>View and update your general account information.</p>
            </div>
            <div class="container mt-4">
                <label for="account" class="head-label">Account Registered</label>
                <p>Allowed individuals to become official users of the system</p>
                <div class="reg-account d-flex justify-content-between align-items-center p-2 mt-4 ">
                    <label for="" class="mx-3">View Account Registered</label>
                    <button type="button" class="view " onclick="window.location.href='account-view.php'">View</button>
                </div>
            </div>
        </div>

    </main>

</body>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/accounts.css">
    <script src="/assets/js/main.js"></script>
    <?php
        require_once('./include/js.php')
    ?>
</html>
