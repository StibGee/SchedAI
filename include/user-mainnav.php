<?php session_start();?>

    <aside class="collapse show collapse-horizontal col-sm-2 p-3 border-end" id="collapseWidthExample">
      <a href="#" class="nav_logo d-flex justify-content-center ">
        <img src="../img/logo/Sched-logo1.png" width="100">
    </a>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
      <li class="nav-item">
        <a href="../faculty/dashboard.php" class="nav-link link-body-emphasis" aria-current="page">
          <p class="bi bi-house-door"> Assigned Schedule</p><br>
        </a>
      </li>
      <li>
        <a href="../faculty/profile.php"  class="nav-link link-body-emphasis">
          <p class="bi bi-speedometer2"> My Profile</p><br>
        </a>
      </li>
      <li>
        <a href="../faculty/user-account.php" class="nav-link link-body-emphasis">
          <p class="bi bi-table"> Account Settings</p><br>
        </a>
      </li>
    </ul>
  </aside>
<?php
        require_once('../include/js.php')
    ?>

<?php
        require_once('../include/head.php');
    ?>
<link rel="stylesheet" href="../css/faculty-css/dashboard.css">
