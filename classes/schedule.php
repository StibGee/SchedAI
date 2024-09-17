<?php
require_once('db.php');

class Schedule {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addyear($academicyear, $semester) {
        $name = $academicyear . "-" . ($academicyear + 1); 
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM calendar WHERE year = :academicyear and sem = :semester");
        $stmt->bindParam(':sem', $academicyear);
        $stmt->bindParam(':year', $semester);
        $stmt->execute();
        $curriculumexists = $stmt->fetchColumn();
    
        if ($curriculumexists) {
            
        } else {
            $sql = "INSERT INTO calendar (sem, year, name) VALUES (:sem, :year, :name)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':year', $academicyear);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':sem', $semester);
            return $stmt->execute();
        }
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

    public function deleteroom($id) {
        $sql = "DELETE FROM room WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function filteredschedule($calendarid, $departmentid) {
        $sql = "SELECT subject.name as subjectname, subjectcode, subject.type as subjecttype, subject.unit as subjectunit, room.name as roomname, subjectschedule.timestart as starttime,subjectschedule.timeend as endtime,day, subjectschedule.yearlvl as yearlvl, section, faculty.fname as facultyfname, faculty.mname as facultymname, faculty.lname as facultylname FROM subjectschedule LEFT JOIN faculty ON subjectschedule.facultyid = faculty.id JOIN department ON department.id=subjectschedule.departmentid JOIN subject ON subject.id=subjectschedule.subjectid JOIN room ON room.id=subjectschedule.roomid WHERE subjectschedule.calendarid=:calendarid and subjectschedule.departmentid=:departmentid ORDER BY subjectcode,FIELD(subjecttype, 'Lec', 'Lab'),subjectunit DESC, section asc";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':calendarid', $calendarid, PDO::PARAM_INT);
        $stmt->bindParam(':departmentid', $departmentid, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
