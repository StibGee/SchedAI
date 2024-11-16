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
    case 'adddepartment':
        addscheduledepartment();
        break;
    case 'updateminorcollege':
        updateminor();
        break;
    case 'swap':
        swap();
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
    
    $collegeid = isset($_POST['collegeid']) ? filter_var($_POST['collegeid'], FILTER_SANITIZE_STRING) : '';
    $academicyear= isset($_POST['academicyear']) ? filter_var($_POST['academicyear'], FILTER_SANITIZE_STRING) : '';
    $includegensub = isset($_POST['includegensub']) ? filter_var($_POST['includegensub'], FILTER_SANITIZE_STRING) : '';
    $semester = isset($_POST['semester']) ? filter_var($_POST['semester'], FILTER_SANITIZE_NUMBER_INT) : 0; 
    //$_SESSION['semester']=$semester;
    $calendarid=$curriculum->findcurriculumidcollege($academicyear, $semester,$collegeid);
    //$_SESSION['calendarid']=$calendarid;
  
    $deleteschedulecollege = $schedule->deleteschedulecollege($calendarid, $collegeid);
    
    foreach ($_POST['departmentid1'] as $index => $deptId) {
        
        $departmentid = htmlspecialchars($deptId, ENT_QUOTES, 'UTF-8');
        
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'section') === 0 && is_array($value)) {
                $yearlvl = substr($key, 7); 
                
                $curriculumindex = "curriculum" . $yearlvl;

                if (isset($value[$index]) && isset($_POST[$curriculumindex][$index])) {
                    $section = htmlspecialchars($value[$index], ENT_QUOTES, 'UTF-8');
                    $curriculume = htmlspecialchars($_POST[$curriculumindex][$index], ENT_QUOTES, 'UTF-8');
                    
                    $result = $schedule->addschedule($yearlvl,$academicyear, $departmentid, $semester, $section, $curriculume, $calendarid, $yearlvl, $collegeid);
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
        if ($_SESSION['departmentid']!=0){
            
        }else{
            $minornofacultycount=$schedule->minorfacultycountcollege($_SESSION['collegeid'], $_SESSION['calendarid']);
            if($minornofacultycount==0){
                if ($includegensub){
                    $_SESSION['minor']=1;
                    header("Location: ../admin/general-sub.php");
                }else{
                    $_SESSION['minor']=0;
                    header("Location: ../admin/final-sched.php?scheduling=loading");
                }
            
                
            }else{
                header("Location: ../admin/final-sched.php?subject=nofaculty");
            }
        }
        

    } else {
        header("Location: ../admin/schedule.php?curriculum=$assigned");
    }    
    exit();
}

function addscheduledepartment() {
    session_start();
    global $schedule;
    global $curriculum;
    
    $collegeid = $_POST['collegeid'];
    $departmentid = $_POST['departmentid2'];
    $academicyear= $_SESSION['year'];
    $semester= isset($_POST['semester']) ? filter_var($_POST['semester'], FILTER_SANITIZE_STRING) : '';
    $includegensub= isset($_POST['includegensub']) ? filter_var($_POST['includegensub'], FILTER_SANITIZE_STRING) : '';
    $calendarid=$curriculum->findcurriculumidcollege($academicyear, $semester,$collegeid);
    $_SESSION['calendarid']=$calendarid;
    
    
    
    $deletescheduledepartment = $schedule->deletescheduledepartment($calendarid, $departmentid);
    
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'section') === 0 && is_array($value)) {
            $yearlvl = substr($key, 7); // Extract year level
            
            // Construct the corresponding curriculum index
            $curriculumIndex = "curriculum" . $yearlvl;
            
            // Check if the section and curriculum exist
            if (isset($value[0]) && isset($_POST[$curriculumIndex][0])) {
                // Sanitize section and curriculum
                $section = htmlspecialchars($value[0], ENT_QUOTES, 'UTF-8');
                $curriculum = htmlspecialchars($_POST[$curriculumIndex][0], ENT_QUOTES, 'UTF-8');
                
                // Call the addschedule method
                $result = $schedule->addschedule($yearlvl, $academicyear, $departmentid, $semester, $section, $curriculum, $calendarid, $yearlvl, $collegeid);
    
                // Determine if the schedule was successfully assigned
                $assigned = $result ? 1 : 0;
            }
        }
    }
    
    

    if ($deletescheduledepartment) {
        if ($_SESSION['departmentid']!=0){
            $minornofacultycount=$schedule->minorfacultycountdepartment($departmentid, $_SESSION['calendarid']);
            if($minornofacultycount==0){
                
                if ($includegensub){
                    
                    header("Location: ../admin/general-sub.php");
                }else{
                    header("Location: ../admin/final-sched.php?scheduling=loading");
                }
            }else{
                header("Location: ../admin/final-sched.php?subject=nofaculty");
            }
        }else{
            $minornofacultycount=$schedule->minorfacultycountcollege($_SESSION['collegeid'], $_SESSION['calendarid']);
            if($minornofacultycount==0){
                
                if ($includegensub){
                    
                    header("Location: ../admin/general-sub.php");
                }else{
                    header("Location: ../admin/final-sched.php?scheduling=loading");
                }
            }else{
                header("Location: ../admin/final-sched.php?subject=nofaculty");
            }
        }
        

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
        
        header("Location: ../admin/final-sched.php?scheduling=loading");

    } else {
        header("Location: ../admin/final-sched.php?minor=failed");
    }    
    exit();
}
function swap() {
   
    global $schedule;
    global $curriculum;
  
    $draggedsubjectid = $_POST['draggedsubjectid'];
    $droppedsubjectid = $_POST['droppedsubjectid'];
    
    $draggedsubjectscheduleinfo = $schedule->subjectscheduleinfo($draggedsubjectid);
    $draggedsubjectday = $draggedsubjectscheduleinfo ? $draggedsubjectscheduleinfo['day'] : null;
    $draggedsubjectstarttime = $draggedsubjectscheduleinfo ? $draggedsubjectscheduleinfo['timestart'] : null;
    $draggedsubjectendtime = $draggedsubjectscheduleinfo ? $draggedsubjectscheduleinfo['timeend'] : null;

    $droppeddsubjectscheduleinfo = $schedule->subjectscheduleinfo($droppedsubjectid);
    $droppedsubjectday = $droppeddsubjectscheduleinfo ? $droppeddsubjectscheduleinfo['day'] : null;
    $droppedsubjectstarttime = $droppeddsubjectscheduleinfo ? $droppeddsubjectscheduleinfo['timestart'] : null;
    $droppedsubjectendtime = $droppeddsubjectscheduleinfo ? $droppeddsubjectscheduleinfo['timeend'] : null;

    
    $swap=$schedule->swapschedule($draggedsubjectid, $draggedsubjectday, $draggedsubjectstarttime, $draggedsubjectendtime, $droppedsubjectid, $droppedsubjectday, $droppedsubjectstarttime, $droppedsubjectendtime);

    if($swap){
        header("Location: ../admin/final-sched-room.php");
    }else {
        header("Location: ../admin/final-sched-room.php?swap=failed");
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
