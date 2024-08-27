<?php
require_once("config.php");

$departmentid = $_POST['departmentid'];
$semester = $_POST['semester'];
$academicyear = $_POST['academicyear'];
$subjectid1 = $_POST['subjectid1'];
$subjectid2 = $_POST['subjectid2'];
$subjectid3 = $_POST['subjectid3'];
$subjectid4 = $_POST['subjectid4'];
$section1_count = $_POST['section1'];
$section2_count = $_POST['section2'];
$section3_count = $_POST['section3'];
$section4_count = $_POST['section4'];

$stmt = $pdo->prepare("SELECT id FROM calendar WHERE sem = :sem AND year=:year");
$stmt->bindParam(':sem', $semester);
$stmt->bindParam(':year', $academicyear);
$stmt->execute();
$calendarid = $stmt->fetchColumn();

if ($calendarid) {
    echo "calendar id:".$calendarid;
} else {
    
}
function generateSectionLetters($numSections) {
    if ($numSections > 26) {
        throw new Exception('Number of sections exceeds the available letters (A-Z).');
    }
    return array_slice(range('A', 'Z'), 0, $numSections);
}

// Generate section arrays for each year level
$section1 = generateSectionLetters($section1_count);
$section2 = generateSectionLetters($section2_count);
$section3 = generateSectionLetters($section3_count);
$section4 = generateSectionLetters($section4_count);




foreach($subjectid1 as $subjectsid1){
    foreach($section1 as $sections1){
        try {
            $stmt = $pdo->prepare("INSERT INTO subjectschedule (subjectid, calendarid, yearlvl, section, departmentid) VALUES (:subjectid, :calendarid, 1, :section, :departmentid)");
            $stmt->bindParam(':subjectid', $subjectsid1);
            $stmt->bindParam(':calendarid', $calendarid);
            $stmt->bindParam(':section', $sections1);
            $stmt->bindParam(':departmentid', $departmentid);
            $stmt->execute();
        
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
}
foreach($subjectid2 as $subjectsid2){
    foreach($section2 as $sections2){
        try {
            $stmt = $pdo->prepare("INSERT INTO subjectschedule (subjectid, calendarid, yearlvl, section, departmentid) VALUES (:subjectid, :calendarid, 2, :section, :departmentid)");
            $stmt->bindParam(':subjectid', $subjectsid2);
            $stmt->bindParam(':calendarid', $calendarid);
            $stmt->bindParam(':section', $sections2);
            $stmt->bindParam(':departmentid', $departmentid);
            $stmt->execute();
        
            
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
foreach($subjectid3 as $subjectsid3){
    foreach($section3 as $sections3){
        try {
            $stmt = $pdo->prepare("INSERT INTO subjectschedule (subjectid, calendarid, yearlvl, section, departmentid) VALUES (:subjectid, :calendarid, 3, :section, :departmentid)");
            $stmt->bindParam(':subjectid', $subjectsid3);
            $stmt->bindParam(':calendarid', $calendarid);
            $stmt->bindParam(':section', $sections3);
            $stmt->bindParam(':departmentid', $departmentid);
            $stmt->execute();
        
            
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
foreach($subjectid4 as $subjectsid4){
    foreach($section4 as $sections4){
        try {
            $stmt = $pdo->prepare("INSERT INTO subjectschedule (subjectid, calendarid, yearlvl, section, departmentid) VALUES (:subjectid, :calendarid, 4, :section, :departmentid)");
            $stmt->bindParam(':subjectid', $subjectsid4);
            $stmt->bindParam(':calendarid', $calendarid);
            $stmt->bindParam(':section', $sections4);
            $stmt->bindParam(':departmentid', $departmentid);
            $stmt->execute();
        
                
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
echo "\nAdditional Data:\n";
echo "Department ID: $departmentid\n";
echo "Semester: $semester\n";
echo "Academic Year: $academicyear\n";
?>
