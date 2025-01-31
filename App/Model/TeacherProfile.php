<?php
class TeacherProfile {

    private PDO $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getTeacherById(int $teacherId): ?array {
        // ✅ Ensure you're only fetching teachers (assuming you have a `role` column)
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id AND role = 'teacher'");
        $stmt->bindParam(':id', $teacherId, PDO::PARAM_INT);
        $stmt->execute();

        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
        return $teacher ?: null;
    }
}
?>
