<?php
require_once("config.php");
if(!isset($_SESSION)){
    session_start();
    
}
#fetch all faculty
$sqlfaculty = "SELECT *, department.name AS departmentname, faculty.id AS facultyid FROM faculty JOIN department ON department.id=faculty.departmentid";
$stmt = $pdo->prepare($sqlfaculty); 
$stmt->execute();  
$faculty = $stmt->fetchAll();



#fetch faculty info
if (isset($_SESSION['id'])){
    $id=$_SESSION['id'];

    $sqlfacultyinfo = "SELECT * FROM faculty WHERE id=:id";
    $stmt = $pdo->prepare($sqlfacultyinfo);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $facultyinfo = $stmt->fetchAll();
    
}

#fetch all department
$sqldepartment = "SELECT * FROM department";
$stmt = $pdo->prepare($sqldepartment); 
$stmt->execute();  
$department = $stmt->fetchAll();

#fetch all subjects
$sqlsubject = "SELECT *,subject.name as subjectname, subject.id as subjectid, department.name AS departmentname, subject.name AS subjectname FROM subject JOIN department ON department.id = subject.departmentid";
$stmt = $pdo->prepare($sqlsubject); 
$stmt->execute();  
$subject = $stmt->fetchAll();

#fetch all rooms
$sqlroom = "SELECT *, department.name AS departmentname FROM room JOIN department ON department.id = room.departmentid";
$stmt = $pdo->prepare($sqlroom); 
$stmt->execute();  
$room = $stmt->fetchAll();

#fetch all calendars
$sqlcalendar = "SELECT * FROM calendar";
$stmt = $pdo->prepare($sqlcalendar); 
$stmt->execute();  
$calendar = $stmt->fetchAll();
?>
