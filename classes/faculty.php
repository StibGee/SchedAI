<?php
require_once('db.php');

class Faculty {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addroom($name, $type, $departmentid, $timestart, $timeend) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM room WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $roomExists = $stmt ->fetchColumn();
    
        if ($roomExists) {
            header("Location: ../admin/room.php?room=exist");
            exit();
        } else {
            $sql = "INSERT INTO room (name, type, departmentid, timestart, timeend) VALUES (:name, :type, :departmentid, :timestart, :timeend)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':departmentid', $departmentid);
            $stmt->bindParam(':timestart', $timestart);
            $stmt->bindParam(':timeend', $timeend);
            return $stmt->execute();
        
        }
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
    public function deletetimepreference($facultyid, $day){
        $sql = "DELETE FROM facultypreferences WHERE facultyid =:facultyid AND day=:day";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':facultyid', $facultyid);
        $stmt->bindParam(':day', $day);
        return $stmt->execute();
    }
    public function editfacultyinfo($fname, $lname, $mname, $contactno, $bday, $gender, $type, $startdate, $teachinghours, $highestdegree, $facultyid) {
        $sql = "UPDATE faculty SET fname = :fname, lname = :lname, mname = :mname, contactno = :contactno, bday = :bday, gender = :gender, type = :type, startdate = :startdate, teachinghours = :teachinghours, rank = :rank WHERE id = :facultyid";
        $stmt = $this->pdo->prepare($sql);
    
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':mname', $mname);
        $stmt->bindParam(':contactno', $contactno);
        $stmt->bindParam(':bday', $bday);
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
        $sql = "SELECT * FROM facultypreferences WHERE facultyid=:facultyid";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':facultyid', $facultyid, PDO::PARAM_INT); 
        $stmt->execute(); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    
}
?>
