<?php
require_once("config.php");

$departmentid = $_POST['departmentid'];
$semester = $_POST['semester'];
$academicyear = $_POST['academicyear'];
$academicplan = $_POST['academicplan'];
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

$section1 = generateSectionLetters($section1_count);
$section2 = generateSectionLetters($section2_count);
$section3 = generateSectionLetters($section3_count);
$section4 = generateSectionLetters($section4_count);




$yearLevels = [1, 2, 3, 4]; // Year levels to loop through
$sections = [
    1 => $section1,  // Sections for year level 1
    2 => $section2,  // Sections for year level 2
    3 => $section3,  // Sections for year level 3
    4 => $section4   // Sections for year level 4
];

try {
    foreach ($sections as $yearlvl => $sectionArray) {
        foreach ($sectionArray as $section) {
            $stmt = $pdo->prepare("
                INSERT INTO subjectschedule (subjectid, calendarid, yearlvl, section, departmentid)
                SELECT subjectid, calendarid, yearlvl, :section, departmentid
                FROM academicplan
                WHERE calendarid = :calendarid AND departmentid = :departmentid AND yearlvl = :yearlvl
            ");

            $stmt->bindParam(':section', $section);
            $stmt->bindValue(':calendarid', $calendarid, PDO::PARAM_INT);
            $stmt->bindValue(':departmentid', $departmentid, PDO::PARAM_INT);
            $stmt->bindValue(':yearlvl', $yearlvl, PDO::PARAM_INT);

            $stmt->execute();
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


try {
    $sql = "UPDATE calendar SET status = 'loaded' WHERE id = :calendarid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':calendarid', $calendarid, PDO::PARAM_INT);
    $stmt->execute();
}catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

//header("Location: ../admin/loadingscreen.php");
?>
