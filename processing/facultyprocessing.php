<?php
require_once '../classes/db.php'; 
require_once '../classes/faculty.php'; 
require_once '../classes/email.php'; 
require_once '../classes/department.php';

$db = new Database();

$pdo = $db->connect();
$faculty = new Faculty ($pdo); 
$email = new Email($pdo);
$department = new Department($pdo);

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'setactive':
        setactive();
        break;
    case 'addfaculty':
        addfaculty();
        break;
    case 'addrootfaculty':
        addrootfaculty();
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
    if(!isset($_SESSION)){
        session_start();
    }
    if($_POST['username']=='admin' && $_POST['password']=='admin'){
        $_SESSION['fname']='Admin';
        $_SESSION['role']='Admin';
        header('Location: ../superadmin/landing.php');
        exit();
    }
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim(stripslashes(htmlspecialchars($_POST['username'])));
        $password = trim(stripslashes(htmlspecialchars($_POST['password'])));
    
        $stmt=$pdo->prepare("SELECT * FROM faculty WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();
    
        
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
function addfaculty() {
    global $faculty;
    global $email;
    $fname = trim(stripslashes(htmlspecialchars($_POST['fname'])));
    $mname = trim(stripslashes(htmlspecialchars($_POST['mname'])));
    $lname = trim(stripslashes(htmlspecialchars($_POST['lname'])));
    $contactno = trim(stripslashes(htmlspecialchars($_POST['contactno'])));
    $bday = trim(stripslashes(htmlspecialchars($_POST['bday'])));
    $gender = trim(stripslashes(htmlspecialchars($_POST['gender'])));
    $username = trim(stripslashes(htmlspecialchars($_POST['username'])));
    $password = trim(stripslashes(htmlspecialchars($_POST['password'])));
    $type = trim(stripslashes(htmlspecialchars($_POST['type'])));
    $startdate = trim(stripslashes(htmlspecialchars($_POST['startdate'])));
    $departmentid = trim(stripslashes(htmlspecialchars($_POST['departmentid'])));
    $collegeid = trim(stripslashes(htmlspecialchars($_POST['collegeid'])));
    $teachinghours = trim(stripslashes(htmlspecialchars($_POST['teachinghours'])));
    $rank = trim(stripslashes(htmlspecialchars($_POST['rank'])));
    $emailadd = trim(stripslashes(htmlspecialchars($_POST['emailadd'])));
    $fullname=$fname.' '.$lname;
    if (isset($_POST['rank'])&&($_POST['rank']=='phd')) {
        $masters='Yes';
        $phd='Yes';
    }elseif(isset($_POST['rank'])&&($_POST['rank']=='masters')) {
        $masters='Yes';
        $phd='No';
    }else{
        $masters='No';
        $phd='No';
    }

    if(isset($_POST['emailadd'])){$emailfaculty=$email->emailnewfaculty($emailadd, $fullname, $username, $password);}
    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
    
    $addfaculty = $faculty->addfaculty($fname,$mname,$lname,$contactno,$bday,$gender,$username,$hashedpassword,$type,$startdate,$departmentid,$collegeid,$teachinghours,$rank,$masters,$phd,$emailadd
    );
    if ($addfaculty) {
        header("Location: ../admin/faculty.php?faculty=added");
        exit();
    }
}

function addrootfaculty() {
    global $faculty;
    global $email;
    global $department;
    
    $departmentid = trim(stripslashes(htmlspecialchars($_POST['departmentid'])));
    $role = trim(stripslashes(htmlspecialchars($_POST['role'])));
    $emailadd = trim(stripslashes(htmlspecialchars($_POST['emailadd'])));
    $fname = trim(stripslashes(htmlspecialchars($_POST['fname'])));
    $mname = trim(stripslashes(htmlspecialchars($_POST['mname'])));
    $lname = trim(stripslashes(htmlspecialchars($_POST['lname'])));
    $fullname=$fname.' '.$lname;
    $username = trim(stripslashes(htmlspecialchars($_POST['username'])));
    $password = trim(stripslashes(htmlspecialchars($_POST['password'])));
    $departmentinfo=$department->getdepartmentinfo($departmentid);
    $collegeid=htmlspecialchars($departmentinfo['collegeid']);

   

    if(isset($_POST['emailadd'])){$emailfaculty=$email->emailnewfaculty($emailadd, $fullname, $username, $password);}
    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
    
    $addfaculty = $faculty->addrootfaculty($fname,$mname,$lname,$username,$hashedpassword,$departmentid,$collegeid,$emailadd, $role);

    if ($addfaculty) {
        header("Location: ../superadmin/users.php?user=added");
        exit();
    }
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
            header("Location: ../admin/faculty.php?faculty=edited");
        }
    } else {
        header("Location: ../admin/academic-plan.php?curriculum=error");
    }    
    exit();
}
function setactive() {
    global $faculty;
    
    $facultyid = isset($_POST['facultyid']) ? filter_var($_POST['facultyid'], FILTER_SANITIZE_STRING) : '';
    $active = isset($_POST['active']) ? filter_var($_POST['active'], FILTER_SANITIZE_STRING) : '';
    

    //edit faculty info
    $activefaculty=$faculty->setfacultyactive($facultyid, $active);

    if ($activefaculty) {
        
        
        header("Location: ../admin/faculty.php?active=updated");
        
        
    } else {
        header("Location: ../admin/faculty.php?active=error");
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
    $email =isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_STRING) : '';
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
    $editfaculty=$faculty->editfacultyinfo($fname, $lname, $mname, $contactno, $email, $gender, $type, $startdate, $teachinghours, $highestdegree, $facultyid);

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
            header("Location: ../faculty/profile.php?profile=updated");
        }else{
            header("Location: ../admin/faculty.php?faculty=updated");
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
