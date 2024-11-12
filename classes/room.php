<?php
require_once('db.php');

class Room {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addroom($name, $type, $departmentid, $timestart, $timeend, $isexclusive ,$collegeid ,$yearlvl) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM room WHERE name = :name");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $roomExists = $stmt ->fetchColumn();
    
        if ($roomExists) {
            header("Location: ../admin/room.php?room=exist");
            exit();
        } else {
            $sql = "INSERT INTO room (name, type, departmentid, timestart, timeend, isexclusive,collegeid ,yearlvl) VALUES (:name, :type, :departmentid, :timestart, :timeend, :isexclusive, :collegeid ,:yearlvl)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':departmentid', $departmentid);
            $stmt->bindParam(':timestart', $timestart);
            $stmt->bindParam(':timeend', $timeend);
            $stmt->bindParam(':isexclusive', $isexclusive);
            $stmt->bindParam(':collegeid', $collegeid);
            $stmt->bindParam(':yearlvl', $yearlvl);
            return $stmt->execute();
        
        }
    }
    

    public function getroombyid($id) {
        $sql = "SELECT * FROM rooms WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateroom($roomid, $name, $type, $departmentid, $isexclusive){
        $sql = "UPDATE room SET name = :name, type = :type, departmentid=:departmentid, isexclusive=:isexclusive WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':type' => $type,
            ':departmentid' => $departmentid,
            ':isexclusive' => $isexclusive,
            ':id' => $roomid
        ]);
    }

    public function deleteroom($id) {
        $sql = "DELETE FROM room WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getallrooms() {
        $sql = "SELECT *, department.name AS departmentname, room.name AS roomname, room.id as roomid FROM room JOIN department ON department.id = room.departmentid";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getcollegerooms($collegeid) {
        $sql = "SELECT *, department.name AS departmentname, room.name AS roomname, room.id as roomid 
                FROM room 
                JOIN department ON department.id = room.departmentid 
                WHERE department.collegeid = :collegeid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':collegeid', $collegeid, PDO::PARAM_INT); 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getdepartmentrooms($department) {
        $sql = "SELECT *, department.name AS departmentname, room.name AS roomname, room.id as roomid 
                FROM room 
                JOIN department ON department.id = room.departmentid 
                WHERE department.id = :departmentid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':departmentid', $department, PDO::PARAM_INT); 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getinitialcollegeroom($collegeid) {
        $sql = "SELECT id FROM room WHERE collegeid = :collegeid LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':collegeid' => $collegeid]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }
    /*public function getcollegerooms($collegeid) {
        $sql = "SELECT * FROM room WHERE collegeid = :collegeid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':collegeid' => $collegeid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    */
}
?>
