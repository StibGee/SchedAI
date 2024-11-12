<?php
require_once('db.php');

class Curriculum {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addcurriculum($academicyear, $semester, $curriculumplan, $collegeid) {
        $name = $academicyear . "-" . ($academicyear + 1); 

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM calendar WHERE year = :year AND sem = :sem AND collegeid=:collegeid");
        $stmt->bindParam(':year', $academicyear); 
        $stmt->bindParam(':sem', $semester); 
        $stmt->bindParam(':collegeid', $collegeid);         
        $stmt->execute();
        $curriculumexists = $stmt->fetchColumn();

        if ($curriculumexists) {
            $sql = "UPDATE calendar SET curriculumplan = :curriculumplan WHERE year = :year AND sem = :sem AND collegeid=:collegeid";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':year', $academicyear);
            $stmt->bindParam(':sem', $semester);
            $stmt->bindParam(':curriculumplan', $curriculumplan);
            $stmt->bindParam(':collegeid', $collegeid); 
            return $stmt->execute();
        } else {
            $sql = "INSERT INTO calendar (sem, year, name, curriculumplan,collegeid) VALUES (:sem, :year, :name, :curriculumplan, :collegeid)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':year', $academicyear);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':sem', $semester);
            $stmt->bindParam(':curriculumplan', $curriculumplan);
            $stmt->bindParam(':collegeid', $collegeid);  
            return $stmt->execute();
        }
    }
    public function addcalendar($academicyear, $semester, $curriculumplan, $collegeid) {
        $name = $academicyear . "-" . ($academicyear + 1); 

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM calendar WHERE year = :year AND sem = :sem AND collegeid=:collegeid");
        $stmt->bindParam(':year', $academicyear); 
        $stmt->bindParam(':sem', $semester); 
        $stmt->bindParam(':collegeid', $collegeid);         
        $stmt->execute();
        $curriculumexists = $stmt->fetchColumn();

        if ($curriculumexists) {
            header("Location: ../admin/schedule.php?calendar=exist");
            exit();
        } else {
            $sql = "INSERT INTO calendar (sem, year, name, curriculumplan,collegeid) VALUES (:sem, :year, :name, :curriculumplan, :collegeid)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':year', $academicyear);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':sem', $semester);
            $stmt->bindParam(':curriculumplan', $curriculumplan);
            $stmt->bindParam(':collegeid', $collegeid);  
            return $stmt->execute();
        }
    }

    
    
    public function getroombyid($id) {
        $sql = "SELECT * FROM rooms WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function editcalendar($calendarid, $academicyear, $endyear, $semester) {
        $name = $academicyear . "-" . $endyear; 
        $sql = "UPDATE calendar SET year = :year, name = :name, sem = :sem WHERE id = :calendarid";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':year' => $academicyear,
            ':name' => $name,
            ':sem' => $semester,
            ':calendarid' => $calendarid
        ]);
    }
   
    public function deletecurriculum($id) {
        try {
            // Begin a transaction
            $this->pdo->beginTransaction();
    
            // Delete from `subjectschedule`
            $sql2 = "DELETE FROM subjectschedule WHERE calendarid = :id";
            $stmt2 = $this->pdo->prepare($sql2);
            $stmt2->execute([':id' => $id]);
    
            // Delete from `subject`
            $sql3 = "DELETE FROM subject WHERE calendarid = :id";
            $stmt3 = $this->pdo->prepare($sql3);
            $stmt3->execute([':id' => $id]);
    
            // Finally, delete from `calendar`
            $sql1 = "DELETE FROM calendar WHERE id = :id";
            $stmt1 = $this->pdo->prepare($sql1);
            $stmt1->execute([':id' => $id]);
    
           
            $this->pdo->commit();
    
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    

    public function getallcurriculums() {
        $sqlcalendar = "SELECT * FROM calendar WHERE curriculumplan=1 ORDER BY year";
        $stmt = $this->pdo->query($sqlcalendar);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getcollegecurriculum($collegeid) {
        $sqlcalendar = "SELECT *,calendar.id as calendarid FROM calendar WHERE curriculumplan=1 AND collegeid=:collegeid ORDER BY year";
        $stmt = $this->pdo->prepare($sqlcalendar); 
        $stmt->execute([':collegeid' => $collegeid]); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    public function getcollegecalendar($collegeid) {
        $sqlcalendar = "SELECT *,calendar.id as calendarid FROM calendar WHERE collegeid=:collegeid ORDER BY year";
        $stmt = $this->pdo->prepare($sqlcalendar); 
        $stmt->execute([':collegeid' => $collegeid]); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    public function getallcurriculumsschedule() {
        $sqlcalendar = "SELECT * FROM calendar ORDER BY year";
        $stmt = $this->pdo->query($sqlcalendar);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getdistinctcurriculumsschedule() {
        $sqlcalendar = "SELECT DISTINCT year as year,name as name FROM calendar  WHERE curriculumplan=1 ORDER BY year";
        $stmt = $this->pdo->query($sqlcalendar);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getdistinctcurriculumsschedulecollege($collegeid) {
        $sqlcalendar = "SELECT DISTINCT year AS year, name AS name 
                        FROM calendar 
                        WHERE collegeid = :collegeid 
                        ORDER BY year";
        $stmt = $this->pdo->prepare($sqlcalendar);
        $stmt->bindParam(':collegeid', $collegeid, PDO::PARAM_INT); 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getdistinctcurriculumsscheduleall() {
        $sqlcalendar = "SELECT DISTINCT year as year,name as name FROM calendar ORDER BY year";
        $stmt = $this->pdo->query($sqlcalendar);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findcurriculumid($academicyear, $semester) {
        $query = "SELECT id FROM calendar WHERE year = :academicyear AND sem = :semester LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':academicyear', $academicyear);
        $stmt->bindParam(':semester', $semester);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function findcurriculumidcollege($academicyear, $semester, $collegeid){
        $query = "SELECT id FROM calendar WHERE year = :academicyear AND collegeid=:collegeid AND sem = :semester LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':academicyear', $academicyear);
        $stmt->bindParam(':semester', $semester);
        $stmt->bindParam(':collegeid', $collegeid);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function calendarinfo($collegelatestyear) {
        $query = "SELECT * FROM calendar WHERE id = :calendarid";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':calendarid', $collegelatestyear);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    
}
?>
