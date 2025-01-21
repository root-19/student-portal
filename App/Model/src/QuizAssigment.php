
<?php
 
require_once __DIR__  . '/../../Database/Database.php';

class QuizAssigment {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    public function assignQuizToSection($quiz_id, $strand, $section) {
        $stmt = $this->conn->prepare("INSERT INTO quiz_assignments (quiz_id, strand, section) VALUES (?, ?, ?)");
        $stmt->execute([$quiz_id, $strand, $section]);
    }

    public function getAssignedQuizzes() {
        $stmt = $this->conn->prepare("SELECT * FROM quiz_assignments");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}