<?php
require_once '../classes/db.php'; 
require_once '../classes/schedule.php'; 
require_once '../classes/curriculum.php'; 


$db = new Database();
$pdo = $db->connect();
$schedule = new Schedule ($pdo); 
$curriculum = new Curriculum ($pdo); 

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'addcollege':
        addschedulecollege();
        break;
    case 'updateminorcollege':
        updateminor();
        break;
    case 'delete':
        deletecurriculum();
        break;
    case 'list':
        listRooms();
        break;
    default:
        header("Location: ../admin/room.php");
        exit();
}

function addschedule() {
    global $schedule;
    global $curriculum;

    $academicyear= isset($_POST['academicyear']) ? filter_var($_POST['academicyear'], FILTER_SANITIZE_STRING) : '';
    $departmentid= isset($_POST['departmentid']) ? filter_var($_POST['departmentid'], FILTER_SANITIZE_STRING) : '';
    $semester= isset($_POST['semester']) ? filter_var($_POST['semester'], FILTER_SANITIZE_STRING) : '';
    $section1= isset($_POST['section1']) ? filter_var($_POST['section1'], FILTER_SANITIZE_STRING) : '';
    $curriculum1= isset($_POST['curriculum1']) ? filter_var($_POST['curriculum1'], FILTER_SANITIZE_STRING) : '';
    $section2= isset($_POST['section2']) ? filter_var($_POST['section2'], FILTER_SANITIZE_STRING) : '';
    $curriculum2= isset($_POST['curriculum2']) ? filter_var($_POST['curriculum2'], FILTER_SANITIZE_STRING) : '';
    $section3= isset($_POST['section3']) ? filter_var($_POST['section3'], FILTER_SANITIZE_STRING) : '';
    $curriculum3= isset($_POST['curriculum3']) ? filter_var($_POST['curriculum3'], FILTER_SANITIZE_STRING) : '';
    $section4= isset($_POST['section4']) ? filter_var($_POST['section4'], FILTER_SANITIZE_STRING) : '';
    $curriculum4= isset($_POST['curriculum4']) ? filter_var($_POST['curriculum4'], FILTER_SANITIZE_STRING) : '';

    $calendarid=$curriculum->findcurriculumid($academicyear, $semester);
    $request = $schedule->addrequest($departmentid, $calendarid);
    $result1 = $schedule->addschedule('1',$academicyear, $departmentid, $semester, $section1, $curriculum1, $calendarid, '1');

    $result2 = $schedule->addschedule('2',$academicyear, $departmentid, $semester, $section2, $curriculum2, $calendarid, '2');

    $result3 = $schedule->addschedule('3',$academicyear, $departmentid, $semester, $section3, $curriculum3, $calendarid, '3');
    $result4 = $schedule->addschedule('4',$academicyear, $departmentid, $semester, $section4, $curriculum4, $calendarid, '4');

    if ($result1 && $result11 && $result2 && $result22 && $result3 && $result4) {
        header("Location: ../admin/academic-plan.php?curriculum=addeds");
    } else {
        header("Location: ../admin/academic-plan.php?curriculum=errors");
    }    
    exit();
}

function addschedulecollege() {
    session_start();
    global $schedule;
    global $curriculum;
    
    $collegeid = $_SESSION['collegeid'];
    $academicyear= isset($_POST['academicyear']) ? filter_var($_POST['academicyear'], FILTER_SANITIZE_STRING) : '';
    $semester= isset($_POST['semester']) ? filter_var($_POST['semester'], FILTER_SANITIZE_STRING) : '';
    $_SESSION['semester']=$semester;
    $calendarid=$curriculum->findcurriculumid($academicyear, $semester);
    $_SESSION['calendarid']=$calendarid;
   
    //$request = $schedule->addrequest($departmentid, $calendarid);
    $deleteschedulecollege = $schedule->deleteschedulecollege($calendarid, $collegeid);
    foreach ($_POST['departmentid'] as $index => $deptId) {
        $departmentid = htmlspecialchars($deptId, ENT_QUOTES, 'UTF-8');

        foreach ($_POST as $key => $value) {
            if (strpos($key, 'section') === 0 && is_array($value)) {
                $yearlvl = substr($key, 7); 
                
                $curriculumindex = "curriculum" . $yearlvl;

                if (isset($value[$index]) && isset($_POST[$curriculumindex][$index])) {
                    $section = htmlspecialchars($value[$index], ENT_QUOTES, 'UTF-8');
                    $curriculum = htmlspecialchars($_POST[$curriculumindex][$index], ENT_QUOTES, 'UTF-8');
                    
                    $result = $schedule->addschedule($yearlvl,$academicyear, $departmentid, $semester, $section, $curriculum, $calendarid, $yearlvl);
                    if ($result){
                        $assigned=1;
                    }else{
                        $assigned=0;
                    }
                  
                }
            }
        }
     
    }


    if ($deleteschedulecollege) {

        header("Location: ../admin/general-sub.php");

    } else {
        header("Location: ../admin/schedule.php?curriculum=$assigned");
    }    
    exit();
}
function updateminor() {
   
    global $schedule;
    global $curriculum;
  
    foreach ($_POST['subjectscheduleid'] as $index => $subjectscheduleid) {
       
        $day = isset($_POST['day'][$index]) ? $_POST['day'][$index] : 'N/A';
        $timestart = isset($_POST['timestart'][$index]) ? $_POST['timestart'][$index] : 'N/A'; 
        $timeend = isset($_POST['timeend'][$index]) ? $_POST['timeend'][$index] : 'N/A'; 

        $updateminor=$schedule->updateminor($subjectscheduleid, $day, $timestart, $timeend); 
    }



    if ($updateminor) {

        header("Location: ../admin/schedule.php?assigned");

    } else {
        header("Location: ../admin/schedule.php?curriculum");
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


function listRooms() {
    global $room;

    $rooms = $room->getRooms();
    header('Content-Type: application/json');
    echo json_encode($rooms);
    exit();
}
?>
