<?php
require_once '../classes/db.php'; 
require_once '../classes/curriculum.php'; 


$db = new Database();
$pdo = $db->connect();
$curriculum = new Curriculum($pdo); 

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'add':
        addcurriculum();
        break;
    case 'editcalendar':
        editcalendar();
            break;
    case 'addcalendar':
        addcalendar();
        break;
    case 'update':
        updateRoom();
        break;
    case 'delete':
        deletecurriculum();
        break;
    case 'deletecalendar':
        deletecalendar();
        break;
    case 'list':
        listRooms();
        break;
    default:
        header("Location: ../admin/room.php");
        exit();
}

function addcurriculum() {
    global $curriculum;

    $academicyear = isset($_POST['academicyear']) ? filter_var($_POST['academicyear'], FILTER_SANITIZE_STRING) : '';
    $semester = isset($_POST['semester']) ? filter_var($_POST['semester'], FILTER_SANITIZE_STRING) : '';
    $collegeid = isset($_POST['collegeid']) ? filter_var($_POST['collegeid'], FILTER_SANITIZE_STRING) : '';
    if(isset($_POST['curriculumplan'])&&($_POST['curriculumplan']=='1')) {
        $curriculumplan='1';
    }else{
        $curriculumplan='0';
    }
    $result = $curriculum->addcurriculum($academicyear, $semester, $curriculumplan, $collegeid);

    if ($result) {
        header("Location: ../admin/academic-plan.php?curriculum=added");
    } else {
        header("Location: ../admin/academic-plan.php?curriculum=error");
    }
    exit();
}
function addcalendar() {
    global $curriculum;

    $academicyear = isset($_POST['academicyear']) ? filter_var($_POST['academicyear'], FILTER_SANITIZE_STRING) : '';
    $semester = isset($_POST['semester']) ? filter_var($_POST['semester'], FILTER_SANITIZE_STRING) : '';
    $collegeid = isset($_POST['collegeid']) ? filter_var($_POST['collegeid'], FILTER_SANITIZE_STRING) : '';
    if(isset($_POST['curriculumplan'])&&($_POST['curriculumplan']=='1')) {
        $curriculumplan='1';
    }else{
        $curriculumplan='0';
    }
    $result = $curriculum->addcalendar($academicyear, $semester, $curriculumplan, $collegeid);

    if ($result) {
        header("Location: ../admin/schedule.php?curriculum=added");
    } else {
        header("Location: ../admin/schedule.php?curriculum=error");
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
function editcalendar() {
    global $curriculum;

    $calendarid = isset($_POST['calendarid']) ? filter_var($_POST['calendarid'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $academicyear = isset($_POST['academicyear']) ? filter_var($_POST['academicyear'], FILTER_SANITIZE_STRING) : '';
    $endyear = isset($_POST['endyear']) ? filter_var($_POST['endyear'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $semester = isset($_POST['semester']) ? filter_var($_POST['semester'], FILTER_SANITIZE_STRING) : '';
    $schedule = isset($_POST['schedule']) ? filter_var($_POST['schedule'], FILTER_SANITIZE_STRING) : '';

    $result = $curriculum->editcalendar($calendarid, $academicyear, $endyear, $semester);

    if ($result && $schedule == 1) {
        header("Location: ../admin/schedule.php?curriculum=updated");
    } elseif ($result && $schedule == 0) {
        header("Location: ../admin/academic-plan.php?curriculum=updated");
    } else {
        header("Location: ../admin/academic-plan.php?curriculum=error");
    }
    
    exit();
}
function deletecurriculum() {
    global $curriculum;
    $id = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $result = $curriculum->deletecurriculum($id);


    if ($result) {
        header("Location: ../admin/academic-plan.php?curriculum=deleted");
    } else {
        header("Location: ../admin//academic-plan.php?curriculum=error");
    }
    exit();
}
function deletecalendar() {
    global $curriculum;
    $id = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $result = $curriculum->deletecurriculum($id);


    if ($result) {
        header("Location: ../admin/schedule.php?curriculum=deleted");
    } else {
        header("Location: ../admin/schedule.php?curriculum=error");
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
