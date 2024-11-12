<?php
require_once('db.php');

class College {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addcollege($abbreviation, $collegename, $year){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM college WHERE abbreviation = :abbreviation");
        $stmt->bindParam(':abbreviation', $abbreviation);
        $stmt->execute();
        $collegeexists = $stmt ->fetchColumn();
    
        if ($collegeexists) {
            header("Location: ../superadmin/colleges.php?college=exist");
            exit();
        } else {
            $sql="INSERT INTO college (abbreviation, name, year) VALUES (:abbreviation, :collegename, :year)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':abbreviation', $abbreviation);
            $stmt->bindParam(':collegename', $collegename);
            $stmt->bindParam(':year', $year);
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

    public function editcollege($collegeid, $collegename, $abbreviation){
        $sql = "UPDATE college SET name = :name, abbreviation = :abbreviation WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $collegename,
            ':abbreviation' => $abbreviation,
            ':id' => $collegeid
        ]);
    }
    public function deletecollege($id) {
        $sql = "DELETE FROM college WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getallcollege() {
        $sql = "SELECT * from college";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getcollegemaxyearlvl($collegeid) {
        $sql = "SELECT MAX(yearlvl) from department WHERE collegeid=:collegeid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':collegeid' => $collegeid]);
        return $stmt->fetchColumn();
    }
    public function getcollegeinfo($collegeid) {
        $sql = "SELECT * FROM college WHERE id = :collegeid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':collegeid' => $collegeid]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function countallcollege() {
        $sql = "SELECT count(*) FROM college";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchColumn(); 
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
}
?>
