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
    public function addfacultysubject($subjectname, $facultyid) {
        foreach($subjectname as $subjectnames){
            $stmt = "INSERT INTO facultysubject (facultyid, subjectname) VALUES (:facultyid,:subjectname)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':facultyid', $facultyid);
            $stmt->bindParam(':subjectname', $subjectnames);
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

    public function getfacultyinfo($id) {
        $sql = "SELECT * FROM faculty WHERE id=:id";
        $stmt = $this->pdo->prepare($sql); 
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute(); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    
}
?>
