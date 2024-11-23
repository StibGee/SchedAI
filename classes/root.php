<?php
require_once('db.php');

class Root {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addroot($collegeid, $departmentid, $role, $contactno, $fname, $lname, $mname, $username, $hashedpassword){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM root WHERE fname = :fname");
        $stmt->bindParam(':fname', $fname);
        $stmt->execute();
        $rootexists = $stmt ->fetchColumn();
    
        if ($rootexists) {
            header("Location: ../superadmin/users.php?root=exist");
            exit();
        } else {
            $sql = "INSERT INTO root (fname, lname, mname, contactno, username, password, collegeid, departmentid, role) VALUES (:fname, :lname, :mname, :contactno, :username, :password, :collegeid, :departmentid, :role)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':mname', $mname);
            $stmt->bindParam(':contactno', $contactno);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedpassword);
            $stmt->bindParam(':collegeid', $collegeid);
            $stmt->bindParam(':departmentid', $departmentid);
            $stmt->bindParam(':role', $role);
            return $stmt->execute();
        
        }
    }
    
    public function addroot($collegeid, $departmentid, $role, $contactno, $fname, $lname, $mname, $username, $hashedpassword){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM root WHERE fname = :fname");
        $stmt->bindParam(':fname', $fname);
        $stmt->execute();
        $rootexists = $stmt ->fetchColumn();
    
        if ($rootexists) {
            header("Location: ../superadmin/users.php?root=exist");
            exit();
        } else {
            $sql = "INSERT INTO root (fname, lname, mname, contactno, username, password, collegeid, departmentid, role) VALUES (:fname, :lname, :mname, :contactno, :username, :password, :collegeid, :departmentid, :role)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':mname', $mname);
            $stmt->bindParam(':contactno', $contactno);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedpassword);
            $stmt->bindParam(':collegeid', $collegeid);
            $stmt->bindParam(':departmentid', $departmentid);
            $stmt->bindParam(':role', $role);
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

    public function getallrooms() {
        $sql = "SELECT *, department.name AS departmentname, room.name AS roomname, room.id as roomid FROM room JOIN department ON department.id = room.departmentid";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
