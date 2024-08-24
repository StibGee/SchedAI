<?php
require_once("config.php");

$name = trim(stripslashes(htmlspecialchars($_POST['name'])));
$type = trim(stripslashes(htmlspecialchars($_POST['type'])));
$departmentid = trim(stripslashes(htmlspecialchars($_POST['departmentid'])));
$timestart = trim(stripslashes(htmlspecialchars($_POST['timestart'])));
$timeend = trim(stripslashes(htmlspecialchars($_POST['timeend'])));

$stmt = $pdo->prepare("SELECT COUNT(*) FROM room WHERE name = :name");
$stmt->bindParam(':name', $name);
$stmt->execute();
$roomexists = $stmt->fetchColumn();

if ($roomexists) {
    header("Location: ../admin/room.php?room=exist");
    exit();
} else {
    
}

try {
    $stmt = $pdo->prepare("INSERT INTO room (name, type, departmentid, timestart, timeend) VALUES (:name, :type, :departmentid, :timestart, :timeend)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':departmentid', $departmentid);
    $stmt->bindParam(':timestart', $timestart);
    $stmt->bindParam(':timeend', $timeend);
    $stmt->execute();

        $_SESSION['msg']="addroom";
    header('Location: ../admin/room.php');
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>