<?php
require_once('db.php');

class Curriculum {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addcurriculum($academicyear, $semester) {
        $name = $academicyear . "-" . ($academicyear + 1); 
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM room WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $curriculumexists = $stmt->fetchColumn();
    
        if ($curriculumexists) {
            header("Location: ../admin/room.php?room=exist");
            exit();
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

    public function deletecurriculum($id) {
        $sql = "DELETE FROM calendar WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getallcurriculums() {
        $sqlcalendar = "SELECT * FROM calendar WHERE curriculumplan=1 ORDER BY year";
        $stmt = $this->pdo->query($sqlcalendar);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
