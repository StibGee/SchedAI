<?php
require_once('db.php');
require_once '../classes/curriculum.php'; 





function generateSectionLetters($numSections) {
    if ($numSections > 26) {
        throw new Exception('Number of sections exceeds the available letters (A-Z).');
    }
    return array_slice(range('A', 'Z'), 0, $numSections);
}

class Schedule {
    private $pdo;
    

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
   
    public function addrequest($departmentid, $calendarid) {
        $sql = "INSERT INTO request (departmentid, calendarid) VALUES (:departmentid, :calendarid)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':departmentid', $departmentid);
        $stmt->bindParam(':calendarid', $calendarid);
        return $stmt->execute();
    }
    public function addschedule($yrlvl, $academicyear, $departmentid, $semester, $sectionnum, $curriculumyrlvl, $calendarid) {
        $curriculum = new Curriculum($this->pdo);
        $calendaridsub = $curriculum->findcurriculumid($curriculumyrlvl, $semester);
        
        $sections = generateSectionLetters($sectionnum);
      
        $this->pdo->beginTransaction();
        
        try {
            foreach ($sections as $section) {
                $stmt = $this->pdo->prepare("
                    INSERT INTO subjectschedule (subjectid, calendarid, yearlvl, section, departmentid)
                    SELECT id, :calendarid, :yearlvl, :section, :departmentid
                    FROM subject
                    WHERE calendarid = :calendaridsub AND departmentid = :departmentid AND yearlvl = :yearlvl
                ");
                
                $stmt->bindValue(':section', $section, PDO::PARAM_STR);  
                $stmt->bindValue(':calendarid', $calendarid, PDO::PARAM_INT); 
                $stmt->bindValue(':calendaridsub', $calendaridsub, PDO::PARAM_INT); 
                $stmt->bindValue(':departmentid', $departmentid, PDO::PARAM_INT);
                $stmt->bindValue(':yearlvl', $yrlvl, PDO::PARAM_INT);  

                if (!$stmt->execute()) {
                    $errorInfo = $stmt->errorInfo();
                    throw new Exception("error adding subject: " . $errorInfo[2]);
                }
            }
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo "Failed to add schedule: " . $e->getMessage();
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
