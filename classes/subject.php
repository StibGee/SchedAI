<?php
require_once('db.php');

class Subject {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addsubjectlec($subjectcode, $subjectname, $lecunit, $focus, $masters, $calendarid, $departmentid, $yearlvl){
        if ($lecunit==1){
            $lechours=3;
        }else{
            $lechours=$lecunit;
        }
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM room WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $subjectExists = $stmt ->fetchColumn();
    
        if ($subjectExists) {
            header("Location: ../adminacademicplan-view.php?subject=exist");
            exit();
        } else {
            $sql="INSERT INTO subject (subjectcode, name, unit, hours, type, masters, focus, calendarid, departmentid, yearlvl, commonname) VALUES (:subjectcode, :name, :unit, :hours, 'Lec', :masters,  :focus, :calendarid, :departmentid, :yearlvl, :commonname)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':subjectcode', $subjectcode);
            $stmt->bindParam(':name', $subjectname);
            $stmt->bindParam(':unit', $lecunit);
            $stmt->bindParam(':hours', $lechours);
            $stmt->bindParam(':masters', $masters);
            $stmt->bindParam(':focus', $focus);
            $stmt->bindParam(':calendarid', $calendarid);
            $stmt->bindParam(':departmentid', $departmentid);
            $stmt->bindParam(':yearlvl', $yearlvl);
            $stmt->bindParam(':commonname', $subjectname);
            return $stmt->execute();        
        }
    }
    public function addsubjectlab($subjectcode, $labname, $labunit, $focus, $masters, $calendarid, $departmentid, $yearlvl,$subjectname){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM room WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $subjectExists = $stmt ->fetchColumn();
    
        if ($subjectExists) {
            header("Location: ../adminacademicplan-view.php?subject=exist");
            exit();
        } else {
                                                                                                                                                    
            $sql="INSERT INTO subject (subjectcode, name, unit, hours, type, masters, focus, calendarid, departmentid, yearlvl, commonname) VALUES (:subjectcode, :subname, :unit, 3, 'Lab', :masters,  :focus, :calendarid, :departmentid, :yearlvl, :commonname)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':subjectcode', $subjectcode);
            $stmt->bindParam(':subname', $labname);
            $stmt->bindParam(':unit', $labunit);
            $stmt->bindParam(':masters', $masters);
            $stmt->bindParam(':focus', $focus);
            $stmt->bindParam(':calendarid', $calendarid);
            $stmt->bindParam(':departmentid', $departmentid);
            $stmt->bindParam(':yearlvl', $yearlvl);
            $stmt->bindParam(':commonname', $subjectname);
            return $stmt->execute(); 
        }
    }
    //get subject data filtered by sem, yearlvl, departmentid
    public function filteredsubjects($calendarid, $departmentid, $yearlvl) {
        $sql = "SELECT *,subject.name as subjectname, subject.id as subjectid, subject.name AS subjectname FROM subject WHERE calendarid = :calendarid and departmentid =:departmentid and  yearlvl=:yearlvl";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':calendarid', $calendarid, PDO::PARAM_INT);
        $stmt->bindParam(':departmentid', $departmentid, PDO::PARAM_INT);
        $stmt->bindParam(':yearlvl', $yearlvl, PDO::PARAM_INT);
        $stmt->execute();   
      
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updatesubject($subjectid, $subjectcode, $subjectname, $type, $unit,$hours, $focus, $labroom, $masters, $commonname) {
       
        $stmt = $this->pdo->prepare("UPDATE subject 
            SET subjectcode = :subjectcode, 
                name = :subjectname, 
                type = :type, 
                unit = :unit, 
                hours = :hours, 
                focus = :focus, 
                requirelabroom = :labroom, 
                masters = :masters, 
                commonname = :commonname 
            WHERE id = :id");

        $stmt->bindParam(':subjectcode', $subjectcode);
        $stmt->bindParam(':subjectname', $subjectname);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':unit', $unit);
        $stmt->bindParam(':hours', $hours);
        $stmt->bindParam(':focus', $focus);
        $stmt->bindParam(':labroom', $labroom);
        $stmt->bindParam(':masters', $masters);
        $stmt->bindParam(':commonname', $commonname);
        $stmt->bindParam(':id', $subjectid); 

        
        if ($stmt->execute()) {
            return true;
        } else {
            return false; 
        }
    }

    public function deletesubject($id) {
        $sql = "DELETE FROM subject WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getallsubject() {
        $sql = "SELECT *, department.name AS departmentname, room.name AS roomname, room.id as roomid FROM room JOIN department ON department.id = room.departmentid";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    //fetch subject data filtered by sem, year, and department
    public function getsubjectfilter() {
        $sql = "SELECT *, department.name AS departmentname, room.name AS roomname, room.id as roomid FROM room JOIN department ON department.id = room.departmentid";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
   
    //fetch distinct subjects
    public function getdistinctsubjects() {
        $sql = "SELECT DISTINCT commonname AS name FROM subject WHERE focus != 'Minor'";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getdistinctsubjectsdepartment($departmentId) {
        $stmt = $this->pdo->prepare("SELECT DISTINCT commonname AS name FROM subject WHERE departmentid = :departmentid AND focus='Major'");
        $stmt->bindParam(':departmentid', $departmentId, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $results;
    }
    public function getdistinctsubjectscollege($collegeid) {
        $stmt = $this->pdo->prepare("SELECT DISTINCT commonname AS name FROM subject JOIN department ON department.id=subject.departmentid WHERE collegeid = :collegeid AND focus='Major'");
        $stmt->bindParam(':collegeid', $collegeid, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $results;
    }

   

    public function addfacultysubject($facultyid, $subjectname){
        $sql="INSERT INTO facultysubject (facultyid, subjectname) VALUES (:facultyid, :subjectname)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':facultyid', $facultyid);
        $stmt->bindParam(':subjectname', $subjectname);
        return $stmt->execute(); 
    }
    
    
}
?>
