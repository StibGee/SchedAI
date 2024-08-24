<?php
require_once("config.php");
if(!isset($_SESSION)){
    session_start();
    
}
#fetch all faculty
$sqlfaculty = "SELECT * FROM faculty";
$stmt = $pdo->prepare($sqlfaculty); 
$stmt->execute();  
$faculty = $stmt->fetchAll();

$sqlfacultyinfo = "SELECT * FROM faculty ";
$stmt = $pdo->prepare($sqlfacultyinfo); 
$stmt->execute();  
$facultyinfo = $stmt->fetchAll();

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
?>