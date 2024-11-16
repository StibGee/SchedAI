<?php
require_once('db.php');

class Department {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function adddepartment($abbreviation, $departmentname, $yearlvl, $collegeid){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM department WHERE abbreviation = :departmentname");
        $stmt->bindParam(':departmentname', $departmentname);
        $stmt->execute();
        $roomExists = $stmt ->fetchColumn();
    
        if ($roomExists) {
            header("Location: ../superadmin/department.php?department=exist");
            exit();
        } else {
            $sql = "INSERT INTO department (name, yearlvl, collegeid, abbreviation) VALUES (:name, :yearlvl, :collegeid, :abbreviation)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $departmentname);
            $stmt->bindParam(':yearlvl', $yearlvl);
            $stmt->bindParam(':collegeid', $collegeid);
            $stmt->bindParam(':abbreviation', $abbreviation);
            return $stmt->execute();
        
        }
    }
    
    public function getcollegedepartment($collegeid) {
        $sql = "SELECT * FROM department WHERE collegeid = :collegeid ORDER BY yearlvl DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':collegeid' => $collegeid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    public function getinitialcollegedepartment($collegeid) {
        $sql = "SELECT id FROM department WHERE collegeid = :collegeid LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':collegeid' => $collegeid]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }
    
    public function getdepartmentinfo($departmentid) {
        $sql = "SELECT * FROM department WHERE id = :departmentid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':departmentid' => $departmentid]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    
    public function editdepartment($departmentid, $departmentname, $abbreviation, $yearlvl){
        $sql = "UPDATE department SET name = :name, abbreviation = :abbreviation, yearlvl=:yearlvl  WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':abbreviation' => $abbreviation,
            ':yearlvl' => $yearlvl,
            ':name' => $departmentname,
            ':id' => $departmentid
        ]);
    }

    public function deletedepartment($id){
        $sql = "DELETE FROM department WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getalldepartment() {
        $sql = "SELECT * FROM department";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
