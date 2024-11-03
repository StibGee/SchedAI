<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'schedai';
    private $username = 'root';
    private $password = '';
    private $pdo;

    public function connect() {
        if ($this->pdo === null) {
            try {
                $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
                exit();
            }
        }
        return $this->pdo;
    }
}
?>
