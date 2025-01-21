
<?php
 
require_once __DIR__  . '/../../Database/Database.php';

class Qiuz {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    public function createQuiz($title, $description, $end_date) {
        $stmt = $this->conn->prepare("INSERT INTO quizzes (title, description, end_date) VALUES (?, ?, ?)");
        $stmt->execute([$title, $description, $end_date]);
        return $this->conn->lastInsertId();
    }

    public function closeQuiz($quiz_id) {
        $stmt = $this->conn->prepare("UPDATE quizzes SET status = 'closed' WHERE id = ?");
        $stmt->execute([$quiz_id]);
    }

    public function getQuiz($quiz_id) {
        $stmt = $this->conn->prepare("SELECT * FROM quizzes WHERE id = ?");
        $stmt->execute([$quiz_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getActiveQuizzes() {
        $stmt = $this->conn->prepare("SELECT * FROM quizzes WHERE status = 'active'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}