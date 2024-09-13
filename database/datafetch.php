<?php
require_once("config.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

#fetch all faculty
$sqlfaculty = "SELECT *, department.name AS departmentname, faculty.id AS facultyid, faculty.lname as facultylname FROM faculty JOIN department ON department.id=faculty.departmentid";
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
$sqlsubject = "SELECT *,subject.name as subjectname, subject.id as subjectid, subject.name AS subjectname FROM subject";
$stmt = $pdo->prepare($sqlsubject); 
$stmt->execute();  
$subject = $stmt->fetchAll();

#fetch all rooms
$sqlroom = "SELECT *, department.name AS departmentname, room.name AS roomname, room.id as roomid FROM room JOIN department ON department.id = room.departmentid";
$stmt = $pdo->prepare($sqlroom); 
$stmt->execute();  
$room = $stmt->fetchAll();

#fetch all calendars
$sqlcalendar = "SELECT * FROM calendar";
$stmt = $pdo->prepare($sqlcalendar); 
$stmt->execute();  
$calendar = $stmt->fetchAll();

if (isset($calendarid) && isset($departmentid)){
    $sqlsubjectschedule = "SELECT subject.name as subjectname, subjectcode, subject.type as subjecttype, subject.unit as subjectunit, room.name as roomname, subjectschedule.timestart as starttime,subjectschedule.timeend as endtime,day, yearlvl, section, faculty.fname as facultyfname, faculty.mname as facultymname, faculty.lname as facultylname FROM subjectschedule LEFT JOIN faculty ON subjectschedule.facultyid = faculty.id JOIN department ON department.id=subjectschedule.departmentid JOIN subject ON subject.id=subjectschedule.subjectid JOIN room ON room.id=subjectschedule.roomid WHERE subjectschedule.calendarid=$calendarid and subjectschedule.departmentid=$departmentid ORDER BY subjectcode,FIELD(subjecttype, 'Lec', 'Lab'),subjectunit DESC, section asc";
    $stmt = $pdo->prepare($sqlsubjectschedule); 
    $stmt->execute();  
    $subjectschedule = $stmt->fetchAll();

}


?>
