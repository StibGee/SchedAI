<?php
require_once('db.php');

class Subject {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addsubjectlec($subjectcode, $subjectname, $lecunit, $focus, $masters, $calendarid, $departmentid, $yearlvl){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM room WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $subjectExists = $stmt ->fetchColumn();
    
        if ($subjectExists) {
            header("Location: ../adminacademicplan-view.php?subject=exist");
            exit();
        } else {
            $sql="INSERT INTO subject (subjectcode, name, unit, hours, type, masters, focus, calendarid, departmentid, yearlvl) VALUES (:subjectcode, :name, :unit, :hours, 'Lec', :masters,  :focus, :calendarid, :departmentid, :yearlvl)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':subjectcode', $subjectcode);
            $stmt->bindParam(':name', $subjectname);
            $stmt->bindParam(':unit', $lecunit);
            $stmt->bindParam(':hours', $lecunit);
            $stmt->bindParam(':masters', $masters);
            $stmt->bindParam(':focus', $focus);
            $stmt->bindParam(':calendarid', $calendarid);
            $stmt->bindParam(':departmentid', $departmentid);
            $stmt->bindParam(':yearlvl', $yearlvl);
            return $stmt->execute();        
        }
    }
    public function addsubjectlab($subjectcode, $labname, $labunit, $focus, $masters, $calendarid, $departmentid, $yearlvl){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM room WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $subjectExists = $stmt ->fetchColumn();
    
        if ($subjectExists) {
            header("Location: ../adminacademicplan-view.php?subject=exist");
            exit();
        } else {
            $sql="INSERT INTO subject (subjectcode, name, unit, hours, type, masters, focus, calendarid, departmentid, yearlvl) VALUES (:subjectcode, :name, :unit, 3, 'Lab', :masters,  :focus, :calendarid, :departmentid, :yearlvl)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':subjectcode', $subjectcode);
            $stmt->bindParam(':name', $labname);
            $stmt->bindParam(':unit', $labunit);
            $stmt->bindParam(':masters', $masters);
            $stmt->bindParam(':focus', $focus);
            $stmt->bindParam(':calendarid', $calendarid);
            $stmt->bindParam(':departmentid', $departmentid);
            $stmt->bindParam(':yearlvl', $yearlvl);
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

    public function updatesubject($id, $name, $capacity) {
        $sql = "UPDATE rooms SET name = :name, capacity = :capacity WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':capacity' => $capacity,
            ':id' => $id
        ]);
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
}
?>
