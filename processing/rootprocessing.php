<?php
require_once '../classes/db.php'; 
require_once '../classes/root.php'; 


$db = new Database();
$pdo = $db->connect();
$root = new Root($pdo); 

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'add':
        addroot();
        break;
    case 'update':
        updateRoom();
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

function addroot() {
    global $room;

    $collegeid = isset($_POST['collegeid']) ? filter_var($_POST['collegeid'], FILTER_SANITIZE_STRING) : '';
    $departmentid = isset($_POST['departmentid']) && !empty($_POST['departmentid']) 
    ? filter_var($_POST['departmentid'], FILTER_SANITIZE_STRING) 
    : null;
    $role = isset($_POST['role']) ? filter_var($_POST['role'], FILTER_SANITIZE_STRING) : '';
    $contactno = isset($_POST['contactno']) ? filter_var($_POST['contactno'], FILTER_SANITIZE_STRING) : '';
    $fname = isset($_POST['fname']) ? filter_var($_POST['fname'], FILTER_SANITIZE_STRING) : '';
    $lname = isset($_POST['lname']) ? filter_var($_POST['lname'], FILTER_SANITIZE_STRING) : '';
    $mname = isset($_POST['mname']) ? filter_var($_POST['mname'], FILTER_SANITIZE_STRING) : '';
    $username = isset($_POST['username']) ? filter_var($_POST['username'], FILTER_SANITIZE_STRING) : '';
    $password = isset($_POST['password']) ? filter_var($_POST['password'], FILTER_SANITIZE_STRING) : '';
    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);

    $result = $root->addroot($collegeid, $departmentid, $role, $contactno, $fname, $lname, $mname, $username, $hashedpassword);

    if ($result) {
        header("Location: ../superadmin/root.php?root=added");
    } else {
        header("Location: ../superadmin/root.php?root=error");
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
