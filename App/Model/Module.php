<?php

class Module {
    private $db;

    // Constructor expects a PDO object for the database connection
    public function __construct($db) {
        $this->db = $db;
    }

    // Save module with title and images
    public function createModule($title, $imagePaths) {
        $query = "INSERT INTO modules (title, images) VALUES (:title, :images)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':images', json_encode($imagePaths)); // Save as JSON
        return $stmt->execute();
    }

    // Fetch all modules
    public function getModules() {
        $query = "SELECT * FROM modules ORDER BY created_at DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getModuleById($id) {
        $query = "SELECT * FROM modules WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllModules() {
        $query = "SELECT * FROM modules"; // Replace 'modules' with your actual table name
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}
