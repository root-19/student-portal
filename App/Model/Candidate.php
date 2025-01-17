<?php
class Candidate {
    private $conn;
    private $table = 'candidates';
    private $votesTable = 'votes';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fetch all candidates
    public function getCandidates() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cast a vote
    public function vote($userId, $position, $candidateId) {
        if (empty($position)) {
            throw new Exception("Position cannot be empty");
        }
    
        // Check if the user has already voted for this position
        $query = "SELECT * FROM {$this->votesTable} WHERE user_id = :user_id AND position = :position";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':position', $position);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            return false;  // User has already voted for this position
        }
    
        // Record the vote
        $query = "INSERT INTO {$this->votesTable} (user_id, position, candidate_id) VALUES (:user_id, :position, :candidate_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':candidate_id', $candidateId);
        if ($stmt->execute()) {
            // Increment the candidate's vote count
            $query = "UPDATE {$this->table} SET votes = votes + 1 WHERE id = :candidate_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':candidate_id', $candidateId);
            $stmt->execute();
            return true;
        }
        return false;
    }

    // Add a new candidate
    public function addCandidate($name, $position, $image) {
        $query = "INSERT INTO {$this->table} (name, position, image) VALUES (:name, :position, :image)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':image', $image);

        // Execute the query
        if ($stmt->execute()) {
            return true;  // Successfully added
        }
        return false;  // Error adding the candidate
    }
}
