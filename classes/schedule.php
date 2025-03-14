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
    public function addschedule($yrlvl, $academicyear, $departmentid, $semester, $sectionnum, $curriculumyrlvl, $calendarid, $yrlvlreference, $collegeid) {
        $curriculum = new Curriculum($this->pdo);
        $calendaridsub = $curriculum->findcurriculumidcollege ($curriculumyrlvl, $semester, $collegeid);
        
        $sections = generateSectionLetters($sectionnum);
        
        $this->pdo->beginTransaction();
        
       
        try {
            foreach ($sections as $section) {
                
                $stmt = $this->pdo->prepare("
                    INSERT INTO subjectschedule (subjectid, calendarid, yearlvl, section, departmentid)
                    SELECT id, :calendarid, :yrlvl, :section, :departmentid
                    FROM subject
                    WHERE calendarid = :calendaridsub AND departmentid = :departmentid AND yearlvl = :yrlvlreference
                ");
               
                $stmt->bindValue(':section', $section, PDO::PARAM_STR);  
                $stmt->bindValue(':calendarid', $calendarid, PDO::PARAM_INT); 
                $stmt->bindValue(':calendaridsub', $calendaridsub, PDO::PARAM_INT); 
                $stmt->bindValue(':departmentid', $departmentid, PDO::PARAM_INT);
                $stmt->bindValue(':yrlvl', $yrlvl, PDO::PARAM_STR); 
                $stmt->bindValue(':yrlvlreference', $yrlvlreference, PDO::PARAM_INT);  

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
    
    
    public function updateminor($subjectscheduleid, $day, $timestart, $timeend) {
      
        $sql = "UPDATE subjectschedule SET day = :day, timestart = :timestart, timeend = :timeend WHERE id = :subjectscheduleid";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':subjectscheduleid' => $subjectscheduleid,
            ':day' => $day,
            ':timestart' => $timestart,
            ':timeend' => $timeend
        ]);
    }
    public function subjectscheduleinfo($subjectscheduleid){
        $sql = "SELECT * FROM subjectschedule WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $subjectscheduleid]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function swapschedule($draggedsubjectid, $draggedsubjectday, $draggedsubjectstarttime, $draggedsubjectendtime, $droppedsubjectid, $droppedsubjectday, $droppedsubjectstarttime, $droppedsubjectendtime) {
        
        $sql = "UPDATE subjectschedule SET day = :day, timestart = :timestart, timeend = :timeend WHERE id = :subjectscheduleid";
        $stmt = $this->pdo->prepare($sql);
        $result1 = $stmt->execute([
            ':subjectscheduleid' => $droppedsubjectid,
            ':day' => $draggedsubjectday,
            ':timestart' => $draggedsubjectstarttime,
            ':timeend' => $draggedsubjectendtime
        ]);
    
        $stmt = $this->pdo->prepare($sql);
        $result2 = $stmt->execute([
            ':subjectscheduleid' => $draggedsubjectid,
            ':day' => $droppedsubjectday,
            ':timestart' => $droppedsubjectstarttime,
            ':timeend' => $droppedsubjectendtime
        ]);

        return $result1 && $result2;
    }
    public function moveschedule($draggedsubjectid, $day, $starttime, $endtime) {
        
        $sql = "UPDATE subjectschedule SET day = :day, timestart = :timestart, timeend = :timeend WHERE id = :subjectscheduleid";
        $stmt = $this->pdo->prepare($sql);
        $result1 = $stmt->execute([
            ':subjectscheduleid' => $draggedsubjectid,
            ':day' => $day,
            ':timestart' => $starttime,
            ':timeend' => $endtime
        ]);
    
        $stmt = $this->pdo->prepare($sql);

        return $result1;
    }
    public function splitDays($days) {
        return preg_split('/(?=[A-Z])/', $days, -1, PREG_SPLIT_NO_EMPTY);
    }
    public function hasConflictFaculty($calendarid,$day, $starttime, $endtime, $excludeSubjectId, $facultyid) {
        $sql = "
            SELECT COUNT(*) 
            FROM subjectschedule 
            WHERE
            (
                (day LIKE CONCAT(:day, '%') AND LENGTH(day) > LENGTH(:day) AND day != 'Th')
                OR day = :day
                OR (day = 'MTh' AND :day IN ('M', 'Th'))
                OR (day = 'WS' AND :day IN ('W', 'S'))
                OR (day = 'TF' AND :day IN ('T', 'F'))
            )
            AND CAST(timestart AS TIME) < :endtime
            AND CAST(timeend AS TIME) > :starttime
            AND id != :subjectscheduleid AND subjectschedule.facultyid=:facultyid AND subjectschedule.calendarid=:calendarid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':day' => $day,
            ':starttime' => $starttime,
            ':endtime' => $endtime,
            ':subjectscheduleid' => $excludeSubjectId,
            ':facultyid' => $facultyid,
            ':calendarid' => $calendarid 
        ]);
        return $stmt->fetchColumn() > 0;
    }
    public function hasConflictSection($calendarid,$day, $starttime, $endtime, $excludeSubjectId, $departmentid, $yearlvl, $section) {
        $sql = "
            SELECT COUNT(*)
            FROM subjectschedule
            WHERE
            (
                (day LIKE CONCAT(:day, '%') AND LENGTH(day) > LENGTH(:day) AND day != 'Th')
                OR day = :day
                OR (day = 'MTh' AND :day IN ('M', 'Th'))
                OR (day = 'WS' AND :day IN ('W', 'S'))
                OR (day = 'TF' AND :day IN ('T', 'F'))
            )

            AND CAST(timestart AS TIME) < :endtime
            AND CAST(timeend AS TIME) > :starttime
            AND id != :subjectscheduleid AND subjectschedule.departmentid=:departmentid AND subjectschedule.yearlvl=:yearlvl AND subjectschedule.section=:section AND subjectschedule.calendarid=:calendarid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':day' => $day,
            ':starttime' => $starttime,
            ':endtime' => $endtime,
            ':subjectscheduleid' => $excludeSubjectId,
            ':departmentid' => $departmentid,
            ':calendarid' => $calendarid,
            ':yearlvl' => $yearlvl,
            ':section' => $section,
        ]);
        return $stmt->fetchColumn() > 0;
    }
    public function hasConflictRoom($calendarid ,$day, $starttime, $endtime, $excludeSubjectId, $roomid) {
        $sql = "
        SELECT COUNT(*)
        FROM subjectschedule
        WHERE
        (
            (day LIKE CONCAT(:day, '%') AND LENGTH(day) > LENGTH(:day) AND day != 'Th')
            OR day = :day
            OR (day = 'MTh' AND :day IN ('M', 'Th'))
            OR (day = 'WS' AND :day IN ('W', 'S'))
            OR (day = 'TF' AND :day IN ('T', 'F'))
        )

        AND CAST(timestart AS TIME) < :endtime
        AND CAST(timeend AS TIME) > :starttime
        AND id != :subjectscheduleid AND subjectschedule.calendarid=:calendarid and subjectschedule.roomid=:roomid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':calendarid' => $calendarid,
            ':day' => $day,
            ':starttime' => $starttime,
            ':endtime' => $endtime,
            ':subjectscheduleid' => $excludeSubjectId,
            ':roomid' => $roomid,
        ]);
        return $stmt->fetchColumn() > 0;
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
    public function deleteschedulecollege($calendarid, $collegeid) {
        $sql = "DELETE subjectschedule FROM subjectschedule 
                JOIN department ON subjectschedule.departmentid = department.id 
                WHERE subjectschedule.calendarid = :calendarid 
                AND department.collegeid = :collegeid";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':calendarid', $calendarid, PDO::PARAM_INT);
        $stmt->bindParam(':collegeid', $collegeid, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function deletescheduledepartment($calendarid, $departmentid) {
       
        $sql = "DELETE subjectschedule FROM subjectschedule 
                JOIN department ON subjectschedule.departmentid = department.id 
                WHERE subjectschedule.calendarid = :calendarid 
                AND department.id = :departmentid";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':calendarid', $calendarid, PDO::PARAM_INT);
        $stmt->bindParam(':departmentid', $departmentid, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    
    public function filteredschedule($calendarid, $departmentid) {
        $sql = "SELECT subject.name as subjectname, subjectcode, subject.type as subjecttype, subject.unit as subjectunit, room.name as roomname, subjectschedule.timestart as starttime,subjectschedule.timeend as endtime,day, subjectschedule.yearlvl as yearlvl, section, faculty.fname as facultyfname, faculty.mname as facultymname, faculty.lname as facultylname, department.abbreviation as abbreviation FROM subjectschedule LEFT JOIN faculty ON subjectschedule.facultyid = faculty.id JOIN department ON department.id=subjectschedule.departmentid JOIN subject ON subject.id=subjectschedule.subjectid LEFT JOIN room ON room.id=subjectschedule.roomid WHERE subjectschedule.calendarid=:calendarid and department.id=:departmentid ORDER BY subjectcode,subjectschedule.departmentid ASC, yearlvl ASC, section asc, subjectunit DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':calendarid', $calendarid, PDO::PARAM_INT);
        $stmt->bindParam(':departmentid', $departmentid, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function filteredschedulecollege($calendarid, $collegeid) {
        $sql = "SELECT subject.name as subjectname, subjectcode, subject.type as subjecttype, subject.unit as subjectunit, room.name as roomname, subjectschedule.timestart as starttime,subjectschedule.timeend as endtime,day, subjectschedule.yearlvl as yearlvl, section, faculty.fname as facultyfname, faculty.mname as facultymname, faculty.lname as facultylname, department.abbreviation as abbreviation FROM subjectschedule LEFT JOIN faculty ON subjectschedule.facultyid = faculty.id JOIN department ON department.id=subjectschedule.departmentid JOIN subject ON subject.id=subjectschedule.subjectid LEFT JOIN room ON room.id=subjectschedule.roomid WHERE subjectschedule.calendarid=:calendarid and department.collegeid=:collegeid  AND subjectschedule.day!='N/A' AND subjectschedule.timestart!= '' AND subjectschedule.timeend!= '' ORDER BY subjectcode,subjectschedule.departmentid ASC, yearlvl ASC, section asc, subjectunit DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':calendarid', $calendarid, PDO::PARAM_INT);
        $stmt->bindParam(':collegeid', $collegeid, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function getcollegesection($collegeid, $calendarid) {
        $sql = "SELECT DISTINCT departmentid, subjectschedule.yearlvl, section, department.abbreviation 
                FROM subjectschedule 
                JOIN department ON department.id = subjectschedule.departmentid
                WHERE department.collegeid = :collegeid 
                AND subjectschedule.calendarid = :calendarid
                AND subjectschedule.section IS NOT NULL AND subjectschedule.section != '' 
                AND subjectschedule.yearlvl IS NOT NULL AND subjectschedule.yearlvl != '' 
                ORDER BY departmentid, subjectschedule.yearlvl, section";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':collegeid' => $collegeid, ':calendarid' => $calendarid]); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getdepartmentsection($departmentid, $calendarid) {
        $sql = "SELECT DISTINCT departmentid, subjectschedule.yearlvl, section, department.abbreviation 
                FROM subjectschedule 
                JOIN department ON department.id = subjectschedule.departmentid
                WHERE department.id = :departmentid 
                AND subjectschedule.calendarid = :calendarid
                AND subjectschedule.section IS NOT NULL AND subjectschedule.section != '' 
                AND subjectschedule.yearlvl IS NOT NULL AND subjectschedule.yearlvl != '' 
                ORDER BY departmentid, subjectschedule.yearlvl, section";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':departmentid' => $departmentid, ':calendarid' => $calendarid]); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function filteredschedulesfaculty($facultyid, $calendarid) {
        $sql = "SELECT 
    subject.name AS subjectname, 
    subject.subjectcode, 
    subject.type AS subjecttype, 
    subject.unit AS subjectunit, 
    room.name AS roomname, 
    subjectschedule.timestart AS starttime,
    subjectschedule.timeend AS endtime,
    subjectschedule.day, 
    subjectschedule.yearlvl AS yearlvl, 
    subjectschedule.section, 
    faculty.fname AS facultyfname, 
    faculty.mname AS facultymname, 
    faculty.lname AS facultylname, 
    department.abbreviation AS abbreviation 
    FROM 
        subjectschedule 
    LEFT JOIN 
        faculty ON subjectschedule.facultyid = faculty.id 
    JOIN 
        department ON department.id = subjectschedule.departmentid 
    JOIN 
        subject ON subject.id = subjectschedule.subjectid 
    LEFT JOIN 
        room ON room.id = subjectschedule.roomid 
    WHERE 
        subjectschedule.calendarid = :calendarid 
        AND subjectschedule.facultyid = :facultyid 
    ORDER BY 
        subject.subjectcode, 
        FIELD(subject.type, 'Lec', 'Lab'), 
        subject.unit DESC, 
        subjectschedule.section ASC;
    ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':calendarid', $calendarid, PDO::PARAM_INT);
        $stmt->bindParam(':facultyid', $facultyid, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //fetch minor subject data filtered by sem, year, and department
    public function getminorsubjects($academicyear, $semester, $yrlvl, $departmentid) {
        $sql = "SELECT *, department.name AS departmentname, room.name AS roomname, room.id as roomid FROM room JOIN department ON department.id = room.departmentid";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getminorsubjectscollege($collegeid, $calendarid) {
        $sql = "SELECT *, subjectschedule.id AS subjectscheduleid FROM `subjectschedule` 
                JOIN department ON subjectschedule.departmentid = department.id 
                JOIN subject ON subject.id=subjectschedule.subjectid
                WHERE department.collegeid = :collegeid 
                AND subjectschedule.calendarid = :calendarid 
                AND subject.focus='Minor'
                ORDER BY department.collegeid";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':collegeid', $collegeid, PDO::PARAM_INT);
        $stmt->bindParam(':calendarid', $calendarid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getminorsubjectsdepartment($departmentid, $calendarid) {
        $sql = "SELECT *, subjectschedule.id AS subjectscheduleid FROM `subjectschedule` 
                JOIN department ON subjectschedule.departmentid = department.id 
                JOIN subject ON subject.id=subjectschedule.subjectid
                WHERE department.id = :departmentid 
                AND subjectschedule.calendarid = :calendarid 
                AND subject.focus='Minor'
                ORDER BY department.collegeid";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':departmentid', $departmentid, PDO::PARAM_INT);
        $stmt->bindParam(':calendarid', $calendarid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countminorsubjectdepartment($departmentid, $calendarid, $yearlvl) {
        $sql = "SELECT COUNT(*) FROM `subjectschedule` 
                JOIN department ON subjectschedule.departmentid = department.id 
                JOIN subject ON subject.id = subjectschedule.subjectid
                WHERE subjectschedule.departmentid = :departmentid 
                AND subjectschedule.calendarid = :calendarid 
                AND subjectschedule.yearlvl=:yearlvl
                AND subject.focus = 'Minor'";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':departmentid', $departmentid, PDO::PARAM_INT);
        $stmt->bindParam(':calendarid', $calendarid, PDO::PARAM_INT);
        $stmt->bindParam(':yearlvl', $yearlvl, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }
    public function getcollegeminoryearlvl($collegeid) {
        $sql = "SELECT DISTINCT subjectschedule.yearlvl AS minoryearlvl FROM subjectschedule JOIN subject ON subject.id=subjectschedule.subjectid JOIN department ON department.id=subjectschedule.departmentid WHERE collegeid=:collegeid AND subject.focus='Minor' ORDER BY subjectschedule.yearlvl ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':collegeid' => $collegeid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getdepartmentminoryearlvl($departmentid) {
        $sql = "SELECT DISTINCT subjectschedule.yearlvl AS minoryearlvl FROM subjectschedule JOIN subject ON subject.id=subjectschedule.subjectid JOIN department ON department.id=subjectschedule.departmentid WHERE department.id=:departmentid AND subject.focus='Minor' ORDER BY subjectschedule.yearlvl ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':departmentid' => $departmentid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
   
    public function minorfacultycountcollege($collegeid, $calendarid) {
        $sql = "SELECT COUNT(*)
                FROM `subjectschedule` 
                JOIN department ON subjectschedule.departmentid = department.id 
                JOIN subject ON subject.id = subjectschedule.subjectid
                LEFT JOIN facultysubject ON subject.commonname = facultysubject.subjectname 
                AND subject.type = facultysubject.subjecttype
                AND department.id = facultysubject.departmentid
                WHERE department.collegeid = :collegeid 
                AND subjectschedule.calendarid = :calendarid 
                AND subject.focus != 'Minor' 
                AND facultysubject.subjectname IS NULL AND facultysubject.subjecttype IS NULL AND facultysubject.departmentid IS NULL";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':collegeid' => $collegeid, ':calendarid' => $calendarid]);
        return $stmt->fetchColumn();
    }
    public function minorfacultycountdepartment($departmentid, $calendarid) {
        $sql = "SELECT COUNT(*)
                FROM `subjectschedule` 
                JOIN department ON subjectschedule.departmentid = department.id 
                JOIN subject ON subject.id = subjectschedule.subjectid
                LEFT JOIN facultysubject ON subject.commonname = facultysubject.subjectname 
                WHERE department.id = :departmentid 
                AND subjectschedule.calendarid = :calendarid 
                AND subject.focus != 'Minor' 
                AND facultysubject.subjectname IS NULL";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':departmentid' => $departmentid, ':calendarid' => $calendarid]);
        return $stmt->fetchColumn();
    }
    public function minornofacultycollege($collegeid, $calendarid) {
        $sql = "SELECT DISTINCT 
                    subject.commonname AS commonname, subject.type as subjecttype,
                    department.abbreviation AS departmentabbreviation,
                    department.id AS departmentid
                FROM `subjectschedule` 
                JOIN department ON subjectschedule.departmentid = department.id 
                JOIN subject ON subject.id = subjectschedule.subjectid
                LEFT JOIN facultysubject ON subject.commonname = facultysubject.subjectname 
                AND subject.type = facultysubject.subjecttype
                AND department.id = facultysubject.departmentid
                WHERE department.collegeid = :collegeid 
                AND subjectschedule.calendarid = :calendarid 
                AND subject.focus != 'Minor' 
                AND facultysubject.subjectname IS NULL AND facultysubject.subjecttype IS NULL AND facultysubject.departmentid IS NULL";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':collegeid' => $collegeid, ':calendarid' => $calendarid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function minornofacultydepartment($departmentid, $calendarid) {
        $sql = "SELECT DISTINCT 
                    subject.commonname AS commonname, 
                    department.abbreviation AS departmentabbreviation 
                FROM `subjectschedule` 
                JOIN department ON subjectschedule.departmentid = department.id 
                JOIN subject ON subject.id = subjectschedule.subjectid
                LEFT JOIN facultysubject ON subject.commonname = facultysubject.subjectname 
                WHERE department.id = :departmentid 
                AND subjectschedule.calendarid = :calendarid 
                AND subject.focus != 'Minor' 
                AND facultysubject.subjectname IS NULL";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':departmentid' => $departmentid, ':calendarid' => $calendarid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findcollegelatestyear($collegeid){
        $sql = "SELECT subjectschedule.calendarid
                FROM `subjectschedule`
                JOIN department ON subjectschedule.departmentid = department.id
                WHERE department.collegeid = :collegeid
                ORDER BY subjectschedule.id DESC
                LIMIT 1;

                ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':collegeid' => $collegeid]);
        return $stmt->fetchColumn();
    }
    public function countsubjecthourscollege($collegeid, $calendarid){
        $sql = "SELECT SUM(subject.hours)
                FROM subjectschedule 
                JOIN subject ON subjectschedule.subjectid=subject.id
                JOIN department ON department.id=subjectschedule.departmentid
                WHERE department.collegeid = :collegeid AND subjectschedule.calendarid=:calendarid AND subject.focus!='Minor' AND subject.focus!='Major1';
                ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':collegeid' => $collegeid, ':calendarid' => $calendarid]);
        return $stmt->fetchColumn();
    }
    public function countsubjecthoursdepartment($departmentid, $calendarid){
        $sql = "SELECT SUM(subject.hours)
                FROM subjectschedule 
                JOIN subject ON subjectschedule.subjectid=subject.id
                JOIN department ON department.id=subjectschedule.departmentid
                WHERE department.id = :departmentid AND subjectschedule.calendarid=:calendarid AND subject.focus!='Minor' AND subject.focus!='Major1';
                ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':departmentid' => $departmentid, ':calendarid' => $calendarid]);
        return $stmt->fetchColumn();
    }
}
?>
