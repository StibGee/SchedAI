<?php
require_once("config.php");

$startyear = trim(stripslashes(htmlspecialchars($_POST['startyear'])));
$endyear = trim(stripslashes(htmlspecialchars($_POST['endyear'])));
$combined = $startyear . '-' . $endyear;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM calendar WHERE name = :name");
$stmt->bindParam(':name', $combined);
$stmt->execute();
$yearexists = $stmt->fetchColumn();

if ($yearexists) {
    header("Location: ../admin/prospectus.php?prospectus=exist");
    exit();
} else {
    
}

try {
    $stmt = $pdo->prepare("INSERT INTO calendar (sem, year, name) VALUES (1, :year, :name)");
    $stmt->bindParam(':year', $startyear);
    $stmt->bindParam(':name', $combined);
    $stmt->execute();

    $stmt = $pdo->prepare("INSERT INTO calendar (sem, year, name) VALUES (2, :year, :name)");
    $stmt->bindParam(':year', $startyear);
    $stmt->bindParam(':name', $combined);
    $stmt->execute();

        $_SESSION['msg']="addroom";
    header('Location: ../admin/prospectus.php');
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
