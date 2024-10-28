<?php
require_once '../classes/db.php'; 
require_once '../classes/faculty.php'; 


$db = new Database();
$pdo = $db->connect();
$faculty = new Faculty ($pdo); 


$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'add':
        addschedule();
        break;
    case 'addprofiling':
        addfacultypreferences();
        break;
    case 'editprofiling':
        editfacultyprofiling();
        break;
        
    case 'update':
        updateRoom();
        break;
    case 'delete':
        deletefaculty();
        break;
    case 'list':
        listRooms();
        break;
    case 'logout':
        logout();
        break;
    case 'changepass':
        facultychangepass();
        break;    
    case 'login':
        login($pdo);
        break;      
    default:
        header("Location: ../admin/room.php");
        exit();
}

function login($pdo) {
    global $schedule;
    global $db;
    global $faculty;
    
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim(stripslashes(htmlspecialchars($_POST['username'])));
        $password = trim(stripslashes(htmlspecialchars($_POST['password'])));
    
        $stmt=$pdo->prepare("SELECT * FROM faculty WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();
    
        if(!isset($_SESSION)){
            session_start();
        }
        if (!$user){
            $_SESSION['error']='nouser';
            header('Location: ../index.php');
        }
        if ($user && password_verify($password, $user['password'])) {
            
            $role = $user['role'];
            $collegeid = $user['collegeid'];
            $departmentid = $user['departmentid'];
            $id = $user['id'];
            $name = $user['fname'];
    
            $_SESSION['id']=$id;
            $_SESSION['collegeid']=$collegeid;
            $_SESSION['departmentid']=$departmentid;
            $_SESSION['fname']=$name;
    
            
            $_SESSION['role']=$role;
            
           
            
            if ($role=='collegesecretary'){
                $_SESSION['scheduling']='college';
                header('Location: ../admin/facultyloading.php');

                exit();
            }elseif($role=='departmenthead'){
                $_SESSION['scheduling']='department';
                header('Location: ../admin/facultyloading.php');
                exit();
            }else{
                $facultysubjects=$faculty->getfacultysubjects($_SESSION['id']);
                if (empty($facultysubjects)) {
                    header("Location: ../faculty/facultyprofiling.php");
                    exit();
                }else{
                    header('Location: ../faculty/dashboard.php');
                    exit();
                }
            }
             
        } else {
            $_SESSION['error']='wrongpassword';
            header('Location: ../index.php');
            exit();
        }
    }  
    
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
    $result1 = $schedule->addschedule(1,$academicyear, $departmentid, $semester, $section1, $curriculum1, $calendarid);
    $result2 = $schedule->addschedule(2,$academicyear, $departmentid, $semester, $section2, $curriculum2, $calendarid);
    $result3 = $schedule->addschedule(3,$academicyear, $departmentid, $semester, $section3, $curriculum3, $calendarid);
    $result4 = $schedule->addschedule(4,$academicyear, $departmentid, $semester, $section4, $curriculum4, $calendarid);

    if ($result1 && $result2 && $result3 && $result4) {
        header("Location: ../admin/academic-plan.php?curriculum=added");
    } else {
        header("Location: ../admin/academic-plan.php?curriculum=error");
    }    
    exit();
}
function addfacultypreferences() {
    global $faculty;
    
    $fname = isset($_POST['fname']) ? filter_var($_POST['fname'], FILTER_SANITIZE_STRING) : '';
    $lname = isset($_POST['lname']) ? filter_var($_POST['lname'], FILTER_SANITIZE_STRING) : '';
    $mname =  isset($_POST['mname']) ? filter_var($_POST['mname'], FILTER_SANITIZE_STRING) : '';
    $contactno = isset($_POST['contactno']) ? filter_var($_POST['contactno'], FILTER_SANITIZE_STRING) : '';
    $bday =isset($_POST['bday']) ? filter_var($_POST['bday'], FILTER_SANITIZE_STRING) : '';
    $gender = isset($_POST['gender']) ? filter_var($_POST['gender'], FILTER_SANITIZE_STRING) : '';
    $type = isset($_POST['type']) ? filter_var($_POST['type'], FILTER_SANITIZE_STRING) : '';
    $startdate = isset($_POST['startdate']) ? filter_var($_POST['startdate'], FILTER_SANITIZE_STRING) : '';
    $teachinghours = isset($_POST['teachinghours']) ? filter_var($_POST['teachinghours'], FILTER_SANITIZE_STRING) : '';
    if(isset($highestdegree)){ $highestdegree = $_POST['highestdegree'];}
    $facultyid = isset($_POST['facultyid']) ? filter_var($_POST['facultyid'], FILTER_SANITIZE_STRING) : '';
    $subjectname = isset($_POST['subjectname']) ? $_POST['subjectname'] : [];


    $monday = isset($_POST['monday']) ? 1 : 0;
    $mondaystartTime = isset($_POST['mondaystartTime']) ? $_POST['mondaystartTime'] : null;
    $mondayendTime = isset($_POST['mondayendTime']) ? $_POST['mondayendTime'] : null;
    
    $tuesday = isset($_POST['tuesday']) ? 1 : 0;
    $tuesdaystartTime = isset($_POST['tuesdaystartTime']) ? $_POST['tuesdaystartTime'] : null;
    $tuesdayendTime = isset($_POST['tuesdayendTime']) ? $_POST['tuesdayendTime'] : null;
    
    $wednesday = isset($_POST['wednesday']) ? 1 : 0;
    $wednesdaystartTime = isset($_POST['wednesdaystartTime']) ? $_POST['wednesdaystartTime'] : null;
    $wednesdayendTime = isset($_POST['wednesdayendTime']) ? $_POST['wednesdayendTime'] : null;
    
    $thursday = isset($_POST['thursday']) ? 1 : 0;
    $thursdaystartTime = isset($_POST['thursdaystartTime']) ? $_POST['thursdaystartTime'] : null;
    $thursdayendTime = isset($_POST['thursdayendTime']) ? $_POST['thursdayendTime'] : null;
    
    $friday = isset($_POST['friday']) ? 1 : 0;
    $fridaystartTime = isset($_POST['fridaystartTime']) ? $_POST['fridaystartTime'] : null;
    $fridayendTime = isset($_POST['fridayendTime']) ? $_POST['fridayendTime'] : null;
    
    $saturday = isset($_POST['saturday']) ? 1 : 0;
    $saturdaystartTime = isset($_POST['saturdaystartTime']) ? $_POST['saturdaystartTime'] : null;
    $saturdayendTime = isset($_POST['saturdayendTime']) ? $_POST['saturdayendTime'] : null;

    $facultysubject = $faculty->addfacultysubject($subjectname, $facultyid);

    if (isset($_POST['monday'])){
        $monday = $faculty->addtimepreference($facultyid, '1',$mondaystartTime,$mondayendTime);
    }
    if (isset($_POST['tuesday'])){
        $tuesday = $faculty->addtimepreference($facultyid, '2',$tuesdaystartTime,$tuesdayendTime);
    }
    if (isset($_POST['wednesday'])){
        $wednesday = $faculty->addtimepreference($facultyid, '3',$wednesdaystartTime,$wednesdayendTime);
    }
    if (isset($_POST['thursday'])){
        $thursday = $faculty->addtimepreference($facultyid, '4',$thursdaystartTime,$thursdayendTime);
    }
    if (isset($_POST['friday'])){
        $friday = $faculty->addtimepreference($facultyid, '5',$fridaystartTime,$fridayendTime);
    }
    if (isset($_POST['saturday'])){
        $saturday = $faculty->addtimepreference($facultyid, '6',$saturdaystartTime,$saturdayendTime);
    }

    if ($facultysubject && ($monday || $tuesday || $wednesday || $thursday || $friday || $saturday)) {
        if($_SESSION['role']=='faculty'){
            header("Location: ../faculty/dasboard.php");
        }else{
            header("Location: ../admin/faculty.php?curriculum=edited");
        }
    } else {
        header("Location: ../admin/academic-plan.php?curriculum=error");
    }    
    exit();
}
function editfacultyprofiling() {
    global $faculty;
    session_start();
    $fname = isset($_POST['fname']) ? filter_var($_POST['fname'], FILTER_SANITIZE_STRING) : '';
    $lname = isset($_POST['lname']) ? filter_var($_POST['lname'], FILTER_SANITIZE_STRING) : '';
    $mname =  isset($_POST['mname']) ? filter_var($_POST['mname'], FILTER_SANITIZE_STRING) : '';
    $contactno = isset($_POST['contactno']) ? filter_var($_POST['contactno'], FILTER_SANITIZE_STRING) : '';
    $bday =isset($_POST['bday']) ? filter_var($_POST['bday'], FILTER_SANITIZE_STRING) : '';
    $gender = isset($_POST['gender']) ? filter_var($_POST['gender'], FILTER_SANITIZE_STRING) : '';
    $type = isset($_POST['type']) ? filter_var($_POST['type'], FILTER_SANITIZE_STRING) : '';
    $startdate = isset($_POST['startdate']) ? filter_var($_POST['startdate'], FILTER_SANITIZE_STRING) : '';
    $teachinghours = isset($_POST['teachinghours']) ? filter_var($_POST['teachinghours'], FILTER_SANITIZE_STRING) : '';
    $highestdegree = isset($_POST['highestdegree']) ? filter_var($_POST['highestdegree'], FILTER_SANITIZE_STRING) : '';
    $facultyid = isset($_POST['facultyid']) ? filter_var($_POST['facultyid'], FILTER_SANITIZE_STRING) : '';
    $subjectname = isset($_POST['subjectname']) ? $_POST['subjectname'] : [];


    $monday = isset($_POST['monday']) ? 1 : 0;
    $mondaystartTime = isset($_POST['mondaystartTime']) ? $_POST['mondaystartTime'] : null;
    $mondayendTime = isset($_POST['mondayendTime']) ? $_POST['mondayendTime'] : null;
    
    $tuesday = isset($_POST['tuesday']) ? 1 : 0;
    $tuesdaystartTime = isset($_POST['tuesdaystartTime']) ? $_POST['tuesdaystartTime'] : null;
    $tuesdayendTime = isset($_POST['tuesdayendTime']) ? $_POST['tuesdayendTime'] : null;
    
    $wednesday = isset($_POST['wednesday']) ? 1 : 0;
    $wednesdaystartTime = isset($_POST['wednesdaystartTime']) ? $_POST['wednesdaystartTime'] : null;
    $wednesdayendTime = isset($_POST['wednesdayendTime']) ? $_POST['wednesdayendTime'] : null;
    
    $thursday = isset($_POST['thursday']) ? 1 : 0;
    $thursdaystartTime = isset($_POST['thursdaystartTime']) ? $_POST['thursdaystartTime'] : null;
    $thursdayendTime = isset($_POST['thursdayendTime']) ? $_POST['thursdayendTime'] : null;
    
    $friday = isset($_POST['friday']) ? 1 : 0;
    $fridaystartTime = isset($_POST['fridaystartTime']) ? $_POST['fridaystartTime'] : null;
    $fridayendTime = isset($_POST['fridayendTime']) ? $_POST['fridayendTime'] : null;
    
    $saturday = isset($_POST['saturday']) ? 1 : 0;
    $saturdaystartTime = isset($_POST['saturdaystartTime']) ? $_POST['saturdaystartTime'] : null;
    $saturdayendTime = isset($_POST['saturdayendTime']) ? $_POST['saturdayendTime'] : null;

    //edit faculty info
    $editfaculty=$faculty->editfacultyinfo($fname, $lname, $mname, $contactno, $bday, $gender, $type, $startdate, $teachinghours, $highestdegree, $facultyid);

    //reset facultysubject 
    $resetfacultysubject= $faculty->resetfacultysubject($facultyid);

    //update facultysubject 
    $addfacultysubject= $faculty->addfacultysubject($subjectname, $facultyid);

    if (isset($_POST['monday'])){
        $dayexisting = $faculty->checkfacultyday($facultyid, '1');
        if (!$dayexisting){
            $monday = $faculty->addtimepreference($facultyid, '1',$mondaystartTime,$mondayendTime);
        }else{
            $monday = $faculty->edittimepreference($facultyid, '1',$mondaystartTime,$mondayendTime);
        }
    }else{
        $monday = $faculty->deletetimepreference($facultyid, '1');
    }

    if (isset($_POST['tuesday'])){
        $dayexisting = $faculty->checkfacultyday($facultyid, '2');
        if (!$dayexisting){
            $tuesday = $faculty->addtimepreference($facultyid, '2',$tuesdaystartTime,$tuesdayendTime);
        }else{
            $tuesday = $faculty->edittimepreference($facultyid, '2',$tuesdaystartTime,$tuesdayendTime);
        }
    }else{
        $tuesday = $faculty->deletetimepreference($facultyid, '2');
    }

    if (isset($_POST['wednesday'])){
        $dayexisting = $faculty->checkfacultyday($facultyid, '3');
        if (!$dayexisting){
            $wednesday = $faculty->addtimepreference($facultyid, '3',$wednesdaystartTime,$wednesdayendTime);
        }else{
            $wednesday = $faculty->edittimepreference($facultyid, '3',$wednesdaystartTime,$wednesdayendTime);
        }
     }else{
        $wednesday = $faculty->deletetimepreference($facultyid, '3');
    }

    if (isset($_POST['thursday'])){
        $dayexisting = $faculty->checkfacultyday($facultyid, '4');
        if (!$dayexisting){
            $thursday = $faculty->addtimepreference($facultyid, '4',$thursdaystartTime,$thursdayendTime);
        }else{
            $thursday = $faculty->edittimepreference($facultyid, '4',$thursdaystartTime,$thursdayendTime);
        }
    }else{
        $thursday = $faculty->deletetimepreference($facultyid, '4');
    }

    if (isset($_POST['friday'])){
        $dayexisting = $faculty->checkfacultyday($facultyid, '5');
        if (!$dayexisting){
            $friday = $faculty->addtimepreference($facultyid, '5',$fridaystartTime,$fridayendTime);
        }else{
            $friday = $faculty->edittimepreference($facultyid, '5',$fridaystartTime,$fridayendTime);
        }
    }else{
        $friday = $faculty->deletetimepreference($facultyid, '5');
    }

    if (isset($_POST['saturday'])){
        $dayexisting = $faculty->checkfacultyday($facultyid, '6');
        if (!$dayexisting){
            $saturday = $faculty->addtimepreference($facultyid, '6',$saturdaystartTime,$saturdayendTime);
        }else{
            $saturday = $faculty->edittimepreference($facultyid, '6',$saturdaystartTime,$saturdayendTime);
        }
    }else{
        $saturday = $faculty->deletetimepreference($facultyid, '6');
    }

    if ($editfaculty && $resetfacultysubject && $addfacultysubject && ($monday || $tuesday || $wednesday || $thursday || $friday || $saturday)) {
        
        if($_SESSION['role']=='faculty'){
            header("Location: ../faculty/dashboard.php");
        }else{
            header("Location: ../admin/faculty.php?curriculum=edited");
        }
       
        
    } else {
        header("Location: ../admin/faculty.php?curriculum=error");
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

function deletefaculty() {
    global $faculty;
    $id = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $result = $faculty->deletefaculty($id);


    if ($result) {
        header("Location: ../admin/faculty.php?faculty=deleted");
    } else {
        header("Location: ../admin//faculty.php?faculty=error");
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

function logout() {
    session_start();
    $_SESSION = array();

    session_destroy();

    header("Location: ../index.php");
    exit;
}
function facultychangepass() {
    global $faculty; 

    $facultyid = isset($_POST['facultyid']) ? filter_var($_POST['facultyid'], FILTER_SANITIZE_NUMBER_INT) : 0;
    $oldpass= isset($_POST['oldpass']) ? filter_var($_POST['oldpass'], FILTER_SANITIZE_STRING) : '';
    $newpass= isset($_POST['newpass']) ? filter_var($_POST['newpass'], FILTER_SANITIZE_STRING) : '';

    $result=$faculty->changepass($facultyid, $oldpass, $newpass);
    if ($result){
        header("Location: ../faculty/user-account.php?password=changed");
    }else{
        header("Location: ../faculty/user-account.php?password=error");
    }
}
?>
