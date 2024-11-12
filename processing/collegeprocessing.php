<?php
require_once '../classes/db.php'; 
require_once '../classes/college.php'; 


$db = new Database();
$pdo = $db->connect();
$college = new College($pdo); 

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'editcollege':
        editcollege();
        break;
    case 'add':
        addcollege();
        break;
    case 'update':
        updatesubject();
        break;
    case 'delete':
        deletecollege();
        break;
    case 'list':
        listsubjects();
        break;
    default:
        header("Location: ../academicplan-view.php.php");
        exit();
}

function addcollege() {
    global $college;

    $abbreviation = isset($_POST['abbreviation']) ? filter_var($_POST['abbreviation'], FILTER_SANITIZE_STRING) : '';

    $collegename = isset($_POST['collegename']) ? filter_var($_POST['collegename'], FILTER_SANITIZE_STRING) : '';
    $year = isset($_POST['year']) ? filter_var($_POST['year'], FILTER_SANITIZE_STRING) : '';
   
    $result = $college->addcollege($abbreviation, $collegename, $year);

 
    if ($result) {
        header("Location: ../superadmin/colleges.php?college=added");
    } else {
        header("Location: ../superadmin/colleges.php?college=error");
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

function editcollege() {
    global $college;

    $collegeid = isset($_POST['collegeid']) ? filter_var($_POST['collegeid'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $collegename = isset($_POST['collegename']) ? filter_var($_POST['collegename'], FILTER_SANITIZE_STRING) : '';
    $abbreviation = isset($_POST['abbreviation']) ? filter_var($_POST['abbreviation'], FILTER_SANITIZE_STRING) : '';
   
    $result = $college->editcollege($collegeid, $collegename, $abbreviation);

    if ($result) {
        header("Location: ../superadmin/colleges.php?college=updated");
    } else {
        header("Location: ../superadmin/colleges.php?college=error");
    }
    exit();
}

function deletecollege() {
    global $college;
    $id = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $result = $college->deletecollege($id);

    if ($result) {
        header("Location: ../superadmin/colleges.php?college=deleted");
    } else {
        header("Location: ../superadmin/colleges.php?college=deleteerror");
    }
    exit();
}

function listcollege() {
    global $college;

    $rooms = $room->getRooms();
    header('Content-Type: application/json');
    echo json_encode($rooms);
    exit();
}
?>
