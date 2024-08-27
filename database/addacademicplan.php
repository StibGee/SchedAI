<?php
require_once("config.php");

$departmentid = $_POST['departmentid'];
$semester = $_POST['semester'];
$academicyear = $_POST['academicyear'];
$subjectid1 = $_POST['subjectid1'];
$subjectid2 = $_POST['subjectid2'];
$subjectid3 = $_POST['subjectid3'];
$subjectid4 = $_POST['subjectid4'];

$stmt = $pdo->prepare("SELECT id FROM calendar WHERE sem = :sem AND year=:year");
$stmt->bindParam(':sem', $semester);
$stmt->bindParam(':year', $academicyear);
$stmt->execute();
$calendarid = $stmt->fetchColumn();

if ($calendarid) {
    echo "calendar id:".$calendarid;
} else {
    
}




foreach($subjectid1 as $subjectsid1){
    try {
        $stmt = $pdo->prepare("INSERT INTO academicplan (subjectid, calendarid, departmentid, yearlvl) VALUES (:subjectid, :calendarid, :departmentid, 1)");
        $stmt->bindParam(':subjectid', $subjectsid1);
        $stmt->bindParam(':calendarid', $calendarid);
        $stmt->bindParam(':departmentid', $departmentid);
        $stmt->execute();
    
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
foreach($subjectid2 as $subjectsid2){
    
    try {
        $stmt = $pdo->prepare("INSERT INTO academicplan (subjectid, calendarid, departmentid, yearlvl) VALUES (:subjectid, :calendarid,:departmentid, 2)");
        $stmt->bindParam(':subjectid', $subjectsid2);
        $stmt->bindParam(':calendarid', $calendarid);
        $stmt->bindParam(':departmentid', $departmentid);
        $stmt->execute();
    
        
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

}
foreach($subjectid3 as $subjectsid3){
    try {
        $stmt = $pdo->prepare("INSERT INTO academicplan (subjectid, calendarid, departmentid, yearlvl) VALUES (:subjectid, :calendarid, :departmentid, 3)");
        $stmt->bindParam(':subjectid', $subjectsid3);
        $stmt->bindParam(':calendarid', $calendarid);
        $stmt->bindParam(':departmentid', $departmentid);
        $stmt->execute();
    
        
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
foreach($subjectid4 as $subjectsid4){
    try {
        $stmt = $pdo->prepare("INSERT INTO academicplan (subjectid, calendarid, departmentid, yearlvl) VALUES (:subjectid, :calendarid, :departmentid, 4)");
        $stmt->bindParam(':subjectid', $subjectsid4);
        $stmt->bindParam(':calendarid', $calendarid);
        $stmt->bindParam(':departmentid', $departmentid);
        $stmt->execute();
    
            
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
try {
    $sql = "UPDATE calendar SET status = 'loaded' WHERE id = :calendarid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':calendarid', $calendarid, PDO::PARAM_INT);
    $stmt->execute();
}catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
