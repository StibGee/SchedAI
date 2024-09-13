<?php 
    require_once('../database/datafetch.php');
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $mname = $_POST['mname'];
    $contactno = $_POST['contactno'];
    $bday = $_POST['bday'];
    $gender = $_POST['gender'];
    $type = $_POST['type'];
    $startdate = $_POST['startdate'];
    $teachinghours = $_POST['teachinghours'];
    if(isset($highestdegree)){ $highestdegree = $_POST['highestdegree'];}
    
    $facultyid = $_POST['facultyid'];
    $subjectid=$_POST['subjectid'];

    $monday = isset($_POST['monday']) ? 1 : 0;
    $mondaystartTime = isset($_POST['mondaystartTime']) ? $_POST['mondaystartTime'] : null;
    $mondayendTime = isset($_POST['mondayendTime']) ? $_POST['mondayendTime'] : null;
    
    $tuesday = isset($_POST['tuesday']) ? 1 : 0;
    $tuesdaystartTime = isset($_POST['tuesdaystartTime']) ? $_POST['tuesdaystartTime'] : null;
    $tuesdayendTime = isset($_POST['tuesdayendTime']) ? $_POST['tuesdayendTime'] : null;
    
    $wednesday = isset($_POST['wednesday']) ? 1 : 0;
    $wednesdaystartTime = isset($_POST['wednesdaystartTime']) ? $_POST['wednesdaystartTime'] : null;
    $wednesdayendTime = isset($_POST['wednesdayendTime']) ? $_POST['wednesdayendTime'] : null;
    
    $thursday = isset($_POST['thursday']) ? 1 : 0;
    $thursdaystartTime = isset($_POST['thursdaystartTime']) ? $_POST['thursdaystartTime'] : null;
    $thursdayendTime = isset($_POST['thursdayendTime']) ? $_POST['thursdayendTime'] : null;
    
    $friday = isset($_POST['friday']) ? 1 : 0;
    $fridaystartTime = isset($_POST['fridaystartTime']) ? $_POST['fridaystartTime'] : null;
    $fridayendTime = isset($_POST['fridayendTime']) ? $_POST['fridayendTime'] : null;
    
    $saturday = isset($_POST['saturday']) ? 1 : 0;
    $saturdaystartTime = isset($_POST['saturdaystartTime']) ? $_POST['saturdaystartTime'] : null;
    $saturdayendTime = isset($_POST['saturdayendTime']) ? $_POST['saturdayendTime'] : null;

    foreach($subjectid as $subjectids){
        try {
            $stmt = $pdo->prepare("INSERT INTO facultysubject (facultyid, subjectid) VALUES (:facultyid,:subjectid)");
            $stmt->bindParam(':facultyid', $facultyid);
            $stmt->bindParam(':subjectid', $subjectids);
            $stmt->execute();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    

    if (isset($monday) && $monday) {
        try {
            $stmt = $pdo->prepare("INSERT INTO facultypreferences (facultyid, day, starttime, endtime) VALUES (:facultyid, 1, :starttime, :endtime)");
            $stmt->bindParam(':facultyid', $facultyid);
            $stmt->bindParam(':starttime', $mondaystartTime);
            $stmt->bindParam(':endtime', $mondayendTime);
            $stmt->execute();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

   
    if (isset($tuesday) && $tuesday) {
        try {
            $stmt = $pdo->prepare("INSERT INTO facultypreferences (facultyid, day, starttime, endtime) VALUES (:facultyid, 2, :starttime, :endtime)");
            $stmt->bindParam(':facultyid', $facultyid);
            $stmt->bindParam(':starttime', $tuesdaystartTime);
            $stmt->bindParam(':endtime', $tuesdayendTime);
            $stmt->execute();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    
    if (isset($wednesday) && $wednesday) {
        try {
            $stmt = $pdo->prepare("INSERT INTO facultypreferences (facultyid, day, starttime, endtime) VALUES (:facultyid, 3, :starttime, :endtime)");
            $stmt->bindParam(':facultyid', $facultyid);
            $stmt->bindParam(':starttime', $wednesdaystartTime);
            $stmt->bindParam(':endtime', $wednesdayendTime);
            $stmt->execute();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    if (isset($thursday) && $thursday) {
        try {
            $stmt = $pdo->prepare("INSERT INTO facultypreferences (facultyid, day, starttime, endtime) VALUES (:facultyid, 4, :starttime, :endtime)");
            $stmt->bindParam(':facultyid', $facultyid);
            $stmt->bindParam(':starttime', $thursdaystartTime);
            $stmt->bindParam(':endtime', $thursdayendTime);
            $stmt->execute();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

 
    if (isset($friday) && $friday) {
        try {
            $stmt = $pdo->prepare("INSERT INTO facultypreferences (facultyid, day, starttime, endtime) VALUES (:facultyid, 5, :starttime, :endtime)");
            $stmt->bindParam(':facultyid', $facultyid);
            $stmt->bindParam(':starttime', $fridaystartTime);
            $stmt->bindParam(':endtime', $fridayendTime);
            $stmt->execute();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    if (isset($saturday) && $saturday) {
        try {
            $stmt = $pdo->prepare("INSERT INTO facultypreferences (facultyid, day, starttime, endtime) VALUES (:facultyid, 6, :starttime, :endtime)");
            $stmt->bindParam(':facultyid', $facultyid);
            $stmt->bindParam(':starttime', $saturdaystartTime);
            $stmt->bindParam(':endtime', $saturdayendTime);
            $stmt->execute();
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

   
    $_SESSION['msg'] = "profileupdated";
    header('Location: ../faculty/user-dashboard.php');
    
?>