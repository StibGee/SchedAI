<?php
require_once('db.php');

class Faculty {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addfaculty($fname, $mname, $lname, $contactno, $bday, $gender, $username, $hashedpassword, $type, $startdate, $departmentid, $collegeid, $teachinghours, $rank, $masters, $phd, $emailadd) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM faculty WHERE fname = :fname");
        $stmt->bindParam(':fname', $fname);
        $stmt->execute();
        $facultyexists = $stmt->fetchColumn();
    
        if ($facultyexists) {
            header("Location: ../admin/faculty.php?faculty=exist");
            exit();
        }

        $stmt = $this->pdo->prepare("INSERT INTO faculty (fname, mname, lname, contactno, bday, gender, username, password, type, startdate, departmentid, collegeid, teachinghours, rank, masters, phd, email) VALUES (:fname, :mname, :lname, :contactno, :bday, :gender, :username, :password, :type, :startdate, :departmentid, :collegeid, :teachinghours, :rank, :masters, :phd, :email)");
    
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':mname', $mname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':contactno', $contactno);
        $stmt->bindParam(':bday', $bday);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedpassword);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':startdate', $startdate);
        $stmt->bindParam(':departmentid', $departmentid);
        $stmt->bindParam(':collegeid', $collegeid);
        $stmt->bindParam(':teachinghours', $teachinghours);
        $stmt->bindParam(':rank', $rank);
        $stmt->bindParam(':masters', $masters);
        $stmt->bindParam(':phd', $phd);
        $stmt->bindParam(':email', $emailadd);
        return $stmt->execute();
    }
    
    public function addrootfaculty($fname,$mname,$lname,$username,$hashedpassword,$departmentid,$collegeid,$emailadd, $role){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM faculty WHERE fname = :fname");
        $stmt->bindParam(':fname', $fname);
        $stmt->execute();
        $facultyexists = $stmt->fetchColumn();
    
        if ($facultyexists) {
            header("Location: ../admin/faculty.php?faculty=exist");
            exit();
        }

        $stmt = $this->pdo->prepare("INSERT INTO faculty (fname, mname, lname,username, password, departmentid, collegeid, email, role) VALUES (:fname, :mname, :lname, :username, :password, :departmentid, :collegeid, :email,:role)");
    
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':mname', $mname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedpassword);
        $stmt->bindParam(':departmentid', $departmentid);
        $stmt->bindParam(':collegeid', $collegeid);
        $stmt->bindParam(':email', $emailadd);
        $stmt->bindParam(':role', $role);
        return $stmt->execute();
    }

    public function checkfacultyday($facultyid, $day) {
        $sql = "SELECT COUNT(*) FROM facultypreferences WHERE facultyid = :facultyid AND day = :day";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':facultyid', $facultyid);
        $stmt->bindParam(':day', $day);
        $stmt->execute();
        $facultydayexists = $stmt->fetchColumn();
        
        return $facultydayexists > 0;
    }
    
    public function addfacultysubject($subjectname, $facultyid) {
        foreach($subjectname as $subjectnames) {
            $sql = "INSERT INTO facultysubject (facultyid, subjectname) VALUES (:facultyid, :subjectname)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':facultyid', $facultyid, PDO::PARAM_INT);
            $stmt->bindParam(':subjectname', $subjectnames, PDO::PARAM_STR);
            $stmt->execute(); 
        }
        return true;
    }
    
    public function addtimepreference($facultyid, $day, $starttime,$endtime){
        $sql ="INSERT INTO facultypreferences (facultyid, day, starttime, endtime) VALUES (:facultyid, :day, :starttime, :endtime)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':day', $day);
        $stmt->bindParam(':facultyid', $facultyid);
        $stmt->bindParam(':starttime', $starttime);
        $stmt->bindParam(':endtime', $endtime);
        return $stmt->execute();
    }
    public function edittimepreference($facultyid, $day, $starttime, $endtime) {
        $sql = "UPDATE facultypreferences SET starttime = :starttime, endtime = :endtime WHERE facultyid = :facultyid AND day = :day";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':day', $day);
        $stmt->bindParam(':facultyid', $facultyid);
        $stmt->bindParam(':starttime', $starttime);
        $stmt->bindParam(':endtime', $endtime);
        return $stmt->execute();
    }
    public function setfacultyactive($facultyid, $active){
        $sql = "UPDATE faculty SET active = :active WHERE id = :facultyid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':facultyid', $facultyid);
        $stmt->bindParam(':active', $active);
        return $stmt->execute();
    }
    public function deletetimepreference($facultyid, $day){
        $sql = "DELETE FROM facultypreferences WHERE facultyid =:facultyid AND day=:day";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':facultyid', $facultyid);
        $stmt->bindParam(':day', $day);
        return $stmt->execute();
    }
    public function editfacultyinfo($fname, $lname, $mname, $contactno, $email, $gender, $type, $startdate, $teachinghours, $highestdegree, $facultyid) {
        $sql = "UPDATE faculty SET fname = :fname, lname = :lname, mname = :mname, contactno = :contactno, email = :email, gender = :gender, type = :type, startdate = :startdate, teachinghours = :teachinghours, rank = :rank WHERE id = :facultyid";
        $stmt = $this->pdo->prepare($sql);
    
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':mname', $mname);
        $stmt->bindParam(':contactno', $contactno);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':startdate', $startdate);
        $stmt->bindParam(':teachinghours', $teachinghours);
        $stmt->bindParam(':rank', $highestdegree);
        $stmt->bindParam(':facultyid', $facultyid);
    
        return $stmt->execute();
    }
    
    
    public function resetfacultysubject($facultyid){
        $sql = "DELETE FROM facultysubject WHERE facultyid = :facultyid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':facultyid', $facultyid);
        return $stmt->execute();
    }

    

    public function getroombyid($id) {
        $sql = "SELECT * FROM rooms WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateroom($id, $name, $capacity) {
        $sql = "UPDATE rooms SET name = :name, capacity = :capacity WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':capacity' => $capacity,
            ':id' => $id
        ]);
    }

    public function deletefaculty($id) {
        $sql = "DELETE FROM faculty WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getfacultyinfo($id) {
        $sql = "SELECT * FROM faculty WHERE id=:id";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute(); 
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getallfaculty() {
        $sql = "SELECT *,faculty.id AS facultyid, department.name AS departmentname FROM faculty JOIN department ON department.id = faculty.departmentid";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getallauthorizedfaculty() {
        $sql = "SELECT *,faculty.id AS facultyid, college.abbreviation as collegename, department.abbreviation AS departmentname FROM faculty JOIN department ON department.id = faculty.departmentid JOIN college ON college.id=department.collegeid WHERE faculty.role='collegesecretary' OR faculty.role='departmenthead'";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getallfacultycollege($collegeid) {
        $sql = "SELECT *, faculty.id AS facultyid, department.name AS departmentname, CONCAT(faculty.fname, ' ', faculty.lname) AS facultyname
                FROM faculty 
                JOIN department ON department.id = faculty.departmentid 
                WHERE department.collegeid = :collegeid";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['collegeid' => $collegeid]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getinitialcollegefaculty($collegeid) {
        $sql = "SELECT id FROM faculty WHERE collegeid = :collegeid LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':collegeid' => $collegeid]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }
    public function facultywemailcollege($collegeid) {
        $sql = "SELECT *, faculty.id AS facultyid, department.name AS departmentname 
                FROM faculty 
                JOIN department ON department.id = faculty.departmentid 
                WHERE department.collegeid = :collegeid AND email IS NOT NULL AND email != '';
                ";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['collegeid' => $collegeid]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function facultywemaildepartment($departmentid) {
        $sql = "SELECT *, faculty.id AS facultyid, department.name AS departmentname 
                FROM faculty 
                JOIN department ON department.id = faculty.departmentid 
                WHERE department.id = :departmentid AND email IS NOT NULL AND email != '';
                ";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['departmentid' => $departmentid]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getallfacultydepartment($departmentid) {
        $sql = "SELECT *, faculty.id AS facultyid, department.name AS departmentname 
                FROM faculty 
                JOIN department ON department.id = faculty.departmentid 
                WHERE department.id = :departmentid";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['departmentid' => $departmentid]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countallfaculty() {
        $sql = "SELECT count(*) from faculty";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchColumn();
    }
    public function getfacultysubjects($facultyid) {
        $sql = "SELECT * FROM facultysubject WHERE facultyid=:facultyid";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':facultyid', $facultyid, PDO::PARAM_INT); 
        $stmt->execute(); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    public function getfacultydaytime($facultyid) {
        $sql = "SELECT * FROM facultypreferences WHERE facultyid=:facultyid ORDER BY day";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':facultyid', $facultyid, PDO::PARAM_INT); 
        $stmt->execute(); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    public function collegefaculty($collegeid) {
        $sql = "SELECT *, faculty.id AS facultyid FROM faculty JOIN department ON department.id=faculty.departmentid WHERE department.collegeid=:collegeid";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':collegeid', $collegeid, PDO::PARAM_INT); 
        $stmt->execute(); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    public function departmentfaculty($departmentid) {
        $sql = "SELECT *, faculty.id AS facultyid FROM faculty JOIN department ON department.id=faculty.departmentid WHERE department.id=:departmentid";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':departmentid', $departmentid, PDO::PARAM_INT); 
        $stmt->execute(); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    public function changepass($facultyid, $oldpass, $newpass) {
       
    
        $sql = "SELECT password FROM faculty WHERE id = :facultyid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':facultyid', $facultyid, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $currenthashedpassword = $result['password'];

            if (password_verify($oldpass, $currenthashedpassword)) {
                $hashednewpass = password_hash($newpass, PASSWORD_DEFAULT);
    
                $updateSql = "UPDATE faculty SET password = :newpass WHERE id = :facultyid";
                $updateStmt = $this->pdo->prepare($updateSql);
                $updateStmt->bindParam(':newpass', $hashednewpass);
                $updateStmt->bindParam(':facultyid', $facultyid, PDO::PARAM_INT);
    
                if ($updateStmt->execute()) {
                    return true; 
                } else {
                    return false; 
                }
            } else {
                return false; 
            }
        } else {
            return false; 
        }
    }
    
    
}
?>
