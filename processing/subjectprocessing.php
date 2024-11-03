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
    case 'updatesubject':
        updatesubject();
        break;
    case 'delete':
        deletesubject();
        break;
    case 'list':
        listsubjects();
        break;
    case 'addfacultysubject':
        addfacultysubject();
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
    $calendarid = isset($_POST['calendarid']) ? trim(stripslashes(htmlspecialchars($_POST['calendarid']))) : '';
    $departmentid = isset($_POST['departmentid']) ? trim(stripslashes(htmlspecialchars($_POST['departmentid']))) : '';
    $yearlvl = isset($_POST['yearlvl']) ? trim(stripslashes(htmlspecialchars($_POST['yearlvl']))) : '';

    $labname = '';
    $labunit = '';

    if (isset($_POST['lab'])) {
        $labname = $subjectname . ' LAB';
        $labunit = isset($_POST['labunit']) ? filter_var($_POST['labunit'], FILTER_SANITIZE_STRING) : '';
    }



    if (isset($_POST['lab']) && isset($_POST['lec'])) {
        $result1 = $subject->addsubjectlec($subjectcode, $subjectname, $lecunit, $focus, $masters, $calendarid, $departmentid, $yearlvl);
        $result2 = $subject->addsubjectlab($subjectcode, $labname, $labunit, $focus, $masters, $calendarid, $departmentid, $yearlvl, $subjectname);
        if ($result1 && $result2) {
            header("Location: ../admin/academicplan-view.php?subject=added");
        } else {
            header("Location: ../admin/academicplan-view.php?subject=error");
        }
    }elseif(isset($_POST['lec'])) {
        $result1 = $subject->addsubjectlec($subjectcode, $subjectname, $lecunit, $focus, $masters, $calendarid, $departmentid, $yearlvl);
        if ($result1) {
            header("Location: ../admin/academicplan-view.php?subject=added");
        } else {
            header("Location: ../admin/academicplan-view.php?subject=error");
        }
    }elseif(isset($_POST['lab'])){
        $result2 = $subject->addsubjectlab($subjectcode, $labname, $labunit, $focus, $masters, $calendarid, $departmentid, $yearlvl, $subjectname);
        if ($result2) {
            header("Location: ../admin/academicplan-view.php?subject=added");
        } else {
            header("Location: ../admin/academicplan-view.php?subject=error");
        }
    }

    exit();
}

function updatesubject() {
    global $subject;

    $masters = isset($_POST['masters']) ? 'Yes' : 'No';
    $subjectid = isset($_POST['subjectid']) ? filter_var($_POST['subjectid'], FILTER_SANITIZE_STRING) : '';
    $subjectcode = isset($_POST['subjectcode']) ? filter_var($_POST['subjectcode'], FILTER_SANITIZE_STRING) : '';
    $subjectname = isset($_POST['subjectname']) ? filter_var($_POST['subjectname'], FILTER_SANITIZE_STRING) : '';
    $type = isset($_POST['type']) ? filter_var($_POST['type'], FILTER_SANITIZE_STRING) : '';
    $unit = isset($_POST['unit']) ? filter_var($_POST['unit'], FILTER_SANITIZE_STRING) : '';
    $focus = isset($_POST['focus']) ? trim(stripslashes(htmlspecialchars($_POST['focus']))) : '';
    $labroom = isset($_POST['labroom']) ? '1' : '0';
    $commonname=$subjectname;
    
    $labname = '';

    if ($type=='Lab') {
        $hours=3;
        $subjectname = $subjectname . ' LAB';
    }else{
        if ($unit==1){
            $hours=3;
        }else{
            $hours=$unit;
        }
    }


    $result = $subject->updatesubject($subjectid,$subjectcode, $subjectname, $type, $unit,$hours, $focus, $labroom, $masters, $commonname);

    if ($result) {
        header("Location: ../admin/academicplan-view.php?subject=edited");
        exit();
    } else {
        header("Location: ../admin/academicplan-view.php?subject=error");
        exit();
    }

    
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
        header("Location: ../admin/academicplan-view.php?subject=deleted");
    } else {
        header("Location: ../admin/academicplan-view.php?subject=error");
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

function addfacultysubject() {
    
    global $schedule;
    global $curriculum;
    global $subject;

    $subjectnames = $_POST['subjectname']; 
    $facultyids = $_POST['facultyid'];
   
    foreach ($subjectnames as $index => $subjectname) {
        $facultyid = $facultyids[$index];
        $result = $subject->addfacultysubject($facultyid, $subjectname);
    }

    if ($result) { 
        header("Location: ../admin/final-sched.php");
        
    } else {
        header("Location: ../admin/schedule.php?curriculum=$assigned");
    }    
    exit();
}
?>
