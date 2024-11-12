<?php
require_once '../classes/db.php'; 
require_once '../classes/department.php'; 


$db = new Database();
$pdo = $db->connect();
$department = new Department($pdo); 

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'add':
        adddepartment();
        break;
    case 'editdepartment':
        editdepartment();
        break;
        
    case 'update':
        updatesubject();
        break;
    case 'delete':
        deletedepartment();
        break;
    case 'list':
        listsubjects();
        break;
    default:
        header("Location: ../academicplan-view.php.php");
        exit();
}

function adddepartment() {
    global $department;

    $abbreviation = isset($_POST['abbreviation']) ? filter_var($_POST['abbreviation'], FILTER_SANITIZE_STRING) : '';

    $departmentname = isset($_POST['departmentname']) ? filter_var($_POST['departmentname'], FILTER_SANITIZE_STRING) : '';
    $yearlvl = isset($_POST['yearlvl']) ? filter_var($_POST['yearlvl'], FILTER_SANITIZE_STRING) : '';
    $collegeid=isset($_POST['collegeid']) ? filter_var($_POST['collegeid'], FILTER_SANITIZE_STRING) : '';
   
    $result = $department->adddepartment($abbreviation, $departmentname, $yearlvl, $collegeid);
  
 
    if ($result) {
        header("Location: ../superadmin/department.php?college=added");
    } else {
        header("Location: ../superadmin/department.php?college=error");
    }
    exit();
}

function editdepartment() {
    global $department;

    $departmentid = isset($_POST['departmentid']) ? filter_var($_POST['departmentid'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $departmentname = isset($_POST['departmentname']) ? filter_var($_POST['departmentname'], FILTER_SANITIZE_STRING) : '';
    $abbreviation = isset($_POST['abbreviation']) ? filter_var($_POST['abbreviation'], FILTER_SANITIZE_STRING) : 0;
    $yearlvl = isset($_POST['yearlvl']) ? filter_var($_POST['yearlvl'], FILTER_SANITIZE_STRING) : 0;
    

    $result = $department->editdepartment($departmentid, $departmentname, $abbreviation, $yearlvl);

    if ($result) {
        header("Location: ../superadmin/department.php?department=updated");
    } else {
        header("Location: ../superadmin/department.php?department=error");
    }
    exit();
}

function deletedepartment() {
    global $department;
    $id = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $result = $department->deletedepartment($id);

    if ($result) {
        header("Location: ../superadmin/department.php?department=deleted");
    } else {
        header("Location: ../superadmin/department.php?department=deleteerror");
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
