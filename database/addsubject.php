<?php
require_once("config.php");

$departmentid = trim(stripslashes(htmlspecialchars($_POST['departmentid'])));
if (isset($_POST['masters'])) {
    $masters='yes';
}else{
    $masters='no';
}
$subjectcode = trim(stripslashes(htmlspecialchars($_POST['subjectcode'])));
$subjectname = trim(stripslashes(htmlspecialchars($_POST['subjectname'])));

$lec = trim(stripslashes(htmlspecialchars($_POST['lec'])));
$lecunit = trim(stripslashes(htmlspecialchars($_POST['lecunit'])));

if (isset($_POST['lab'])) {
$lab = trim(stripslashes(htmlspecialchars($_POST['lab'])));
$labunit = $_POST['labunit'];

}
$focus = trim(stripslashes(htmlspecialchars($_POST['focus'])));


$stmt = $pdo->prepare("SELECT COUNT(*) FROM subject WHERE subjectcode = :subjectcode");
$stmt->bindParam(':subjectcode', $subjectcode);
$stmt->execute();
$subjectexists = $stmt->fetchColumn();

if ($subjectexists) {
    header("Location: ../admin/subject.php?subject=exist");
    exit();
} else {
    
}

if (isset($_POST['lab'])) {
    
    try {
        $stmt = $pdo->prepare("INSERT INTO subject (subjectcode, name, unit, hours, type, masters, departmentid, focus) VALUES (:subjectcode, :name, :unit, :hours, 'Lec', :masters, :departmentid, :focus)");
        $stmt->bindParam(':subjectcode', $subjectcode);
        $stmt->bindParam(':name', $subjectname);
        $stmt->bindParam(':unit', $lecunit);
        $stmt->bindParam(':hours', $lecunit);
        $stmt->bindParam(':masters', $masters);
        $stmt->bindParam(':departmentid', $departmentid);
        $stmt->bindParam(':focus', $focus);
        $stmt->execute();
    
        $stmt2 = $pdo->prepare("INSERT INTO subject (subjectcode, name, unit, hours, type, masters, departmentid, focus) VALUES (:subjectcode, :name, :unit, 3, 'Lab', :masters, :departmentid, :focus)");
        $stmt2->bindParam(':subjectcode', $subjectcode);
        $stmt2->bindParam(':name', $subjectname);
        $stmt2->bindParam(':unit', $labunit);
        $stmt2->bindParam(':masters', $masters);
        $stmt2->bindParam(':departmentid', $departmentid);
        $stmt2->bindParam(':focus', $focus);
        $stmt2->execute();
    
        $_SESSION['msg']="addsubject";
        header('Location: ../admin/subjects.php');
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    
} else {
    try {
        $stmt = $pdo->prepare("INSERT INTO subject (subjectcode, name, unit, hours, type, masters, departmentid, focus) VALUES (:subjectcode, :name, :unit, :hours, 'lec', :masters, :departmentid, :focus)");
        $stmt->bindParam(':subjectcode', $subjectcode);
        $stmt->bindParam(':name', $subjectname);
        $stmt->bindParam(':unit', $lecunit);
        $stmt->bindParam(':hours', $lecunit);
        $stmt->bindParam(':masters', $masters);
        $stmt->bindParam(':departmentid', $departmentid);
        $stmt->bindParam(':focus', $focus);
        $stmt->execute();
    
        $_SESSION['msg']="addsubject"; 
        header('Location: ../admin/subjects.php');
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    
}




?>
