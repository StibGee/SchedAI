<?php
require_once '../classes/db.php'; 
require_once '../classes/subject.php'; 


$db = new Database();
$pdo = $db->connect();
$subject = new Subject($pdo); 

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'add':
        addsubject();
        break;
    case 'update':
        updatesubject();
        break;
    case 'delete':
        deletesubject();
        break;
    case 'list':
        listsubjects();
        break;
    default:
        header("Location: ../academicplan-view.php.php");
        exit();
}

function addsubject() {
    global $subject;

    $masters = isset($_POST['masters']) ? 'Yes' : 'No';

    $subjectcode = isset($_POST['subjectcode']) ? filter_var($_POST['subjectcode'], FILTER_SANITIZE_STRING) : '';
    $subjectname = isset($_POST['subjectname']) ? filter_var($_POST['subjectname'], FILTER_SANITIZE_STRING) : '';
    $lecunit = isset($_POST['lecunit']) ? filter_var($_POST['lecunit'], FILTER_SANITIZE_STRING) : '';
    $focus = isset($_POST['focus']) ? trim(stripslashes(htmlspecialchars($_POST['focus']))) : '';
    $calendarid = isset($_POST['focus']) ? trim(stripslashes(htmlspecialchars($_POST['calendarid']))) : '';
    $departmentid = isset($_POST['focus']) ? trim(stripslashes(htmlspecialchars($_POST['departmentid']))) : '';
    $yearlvl = isset($_POST['focus']) ? trim(stripslashes(htmlspecialchars($_POST['yearlvl']))) : '';

    $labname = '';
    $labunit = '';

    if (isset($_POST['lab'])) {
        $labname = $subjectname . ' LAB';
        $labunit = isset($_POST['labunit']) ? filter_var($_POST['labunit'], FILTER_SANITIZE_STRING) : '';
    }

    $result1 = $subject->addsubjectlec($subjectcode, $subjectname, $lecunit, $focus, $masters, $calendarid, $departmentid, $yearlvl);

    if (isset($_POST['lab'])) {
        $result2 = $subject->addsubjectlab($subjectcode, $labname, $labunit, $focus, $masters, $calendarid, $departmentid, $yearlvl);
        if ($result1 && $result2) {
            header("Location: ../admin/academicplan-view.php?subject=added");
        } else {
            header("Location: ../admin/academicplan-view.php?subject=error");
        }
    } else {
        if ($result1) {
            header("Location: ../admin/academicplan-view.php?subject=added");
        } else {
            header("Location: ../admin/academicplan-view.php?subject=error");
        }
    }

    exit();
}

function updateroom() {
    global $room;

    $id = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $name = isset($_POST['name']) ? filter_var($_POST['name'], FILTER_SANITIZE_STRING) : '';
    $capacity = isset($_POST['capacity']) ? filter_var($_POST['capacity'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $type = isset($_POST['type']) ? filter_var($_POST['type'], FILTER_SANITIZE_STRING) : '';
    $departmentid = isset($_POST['departmentid']) ? filter_var($_POST['departmentid'], FILTER_SANITIZE_STRING) : '';
    $timestart = isset($_POST['timestart']) ? filter_var($_POST['timestart'], FILTER_SANITIZE_STRING) : '';
    $timeend = isset($_POST['timeend']) ? filter_var($_POST['timeend'], FILTER_SANITIZE_STRING) : '';

    $result = $room->updateRoom($id, $name, $capacity, $type, $departmentid, $timestart, $timeend);

    if ($result) {
        header("Location: ../admin/room.php?room=updated");
    } else {
        header("Location: ../admin/room.php?room=error");
    }
    exit();
}

function deletesubject() {
    global $subject;
    $id = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $result = $subject->deletesubject($id);

    if ($result) {
        header("Location: ../admin/academicplan-view.php?status=deleted");
    } else {
        header("Location: ../admin/academicplan-view.php?status=error");
    }
    exit();
}

function listRooms() {
    global $room;

    $rooms = $room->getRooms();
    header('Content-Type: application/json');
    echo json_encode($rooms);
    exit();
}
?>