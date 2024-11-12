<?php
require_once '../classes/db.php'; 
require_once '../classes/room.php'; 


$db = new Database();
$pdo = $db->connect();
$room = new Room($pdo); 

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'add':
        addroom();
        break;
    case 'editroom':
        editroom();
        break;
    
    case 'delete':
        deleteroom();
        break;
    case 'list':
        listRooms();
        break;
    default:
        header("Location: ../admin/room.php");
        exit();
}

function addroom() {
    global $room;

    $name = isset($_POST['name']) ? filter_var($_POST['name'], FILTER_SANITIZE_STRING) : '';
    $type = isset($_POST['type']) ? filter_var($_POST['type'], FILTER_SANITIZE_STRING) : '';
    $departmentid = isset($_POST['departmentid']) ? filter_var($_POST['departmentid'], FILTER_SANITIZE_STRING) : '';
    $timestart = isset($_POST['timestart']) ? filter_var($_POST['timestart'], FILTER_SANITIZE_STRING) : '';
    $timeend = isset($_POST['timeend']) ? filter_var($_POST['timeend'], FILTER_SANITIZE_STRING) : '';
    $isexclusive = isset($_POST['isexclusive']) ? 1 : 0;
    $collegeid = isset($_POST['collegeid']) ? filter_var($_POST['collegeid'], FILTER_SANITIZE_STRING) : '';
    $yearlvl = isset($_POST['yearlvl']) ? filter_var($_POST['yearlvl'], FILTER_SANITIZE_STRING) : '';
   
    $result = $room->addroom($name, $type, $departmentid, $timestart, $timeend,$isexclusive ,$collegeid ,$yearlvl);

    if ($result) {
        header("Location: ../admin/room.php?room=added");
    } else {
        header("Location: ../admin/room.php?room=error");
    }
    exit();
}

function editroom() {
    global $room;

    $roomid = isset($_POST['roomid']) ? filter_var($_POST['roomid'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $name = isset($_POST['name']) ? filter_var($_POST['name'], FILTER_SANITIZE_STRING) : '';
    $type = isset($_POST['type']) ? filter_var($_POST['type'], FILTER_SANITIZE_STRING) : '';
    $departmentid = isset($_POST['departmentid']) ? filter_var($_POST['departmentid'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $isexclusive= isset($_POST['isexclusive']) ? filter_var($_POST['isexclusive'], FILTER_SANITIZE_NUMBER_INT) : 0;

    $result = $room->updateroom($roomid, $name, $type, $departmentid, $isexclusive);

    if ($result) {
        header("Location: ../admin/room.php?room=updated");
    } else {
        header("Location: ../admin/room.php?room=error");
    }
    exit();
}

function deleteroom() {
    global $room;
    $id = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $result = $room->deleteroom($id);


    if ($result) {
        header("Location: ../admin/room.php?status=deleted");
    } else {
        header("Location: ../admin/room.php?status=error");
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
