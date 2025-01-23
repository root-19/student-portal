<?php

class Quiz {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Fetch all distinct sections
    public function getSections() {
        $stmt = $this->db->query("SELECT DISTINCT section FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch all distinct strands
    public function getStrands() {
        $stmt = $this->db->query("SELECT DISTINCT strand FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  
    public function addAnswer($questionId, $answerText, $isCorrect) {
        $stmt = $this->db->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");
        $stmt->execute([$questionId, $answerText, $isCorrect]);
    }
    // Create a new quiz and return its ID
    public function createQuiz($title) {
        $stmt = $this->db->prepare("INSERT INTO quizzes (title) VALUES (?)");
        $stmt->execute([$title]);
        return $this->db->lastInsertId();
    }

    // Add a target section and strand for a quiz
    public function addQuizTarget($quizId, $section, $strand) {
        $stmt = $this->db->prepare("INSERT INTO quiz_targets (quiz_id, section, strand) VALUES (?, ?, ?)");
        $stmt->execute([$quizId, $section, $strand]);
    }

    // Add a question to a quiz and return its ID
    public function addQuestion($quizId, $questionText) {
        $stmt = $this->db->prepare("INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)");
        $stmt->execute([$quizId, $questionText]);
        return $this->db->lastInsertId();
    }

    // Add an answer to a question
    public function saveFullQuiz($title, $section, $strand, $questions, $answers, $correctAnswers) {
        try {
            $this->db->beginTransaction();
    
            // Create quiz
            $quizId = $this->createQuiz($title);
            error_log("Quiz created with ID: $quizId");
    
            // Add quiz targets
            $this->addQuizTarget($quizId, $section, $strand);
            error_log("Quiz targets added for section: $section, strand: $strand");
    
            // Add questions and answers
            foreach ($questions as $index => $questionText) {
                $questionId = $this->addQuestion($quizId, $questionText);
                error_log("Added question: $questionText with ID: $questionId");
    
                // Ensure $correctAnswers[$index + 1] is always an array
                $correctAnswersForQuestion = isset($correctAnswers[$index + 1]) && is_array($correctAnswers[$index + 1]) 
                    ? $correctAnswers[$index + 1] 
                    : [];
    
                foreach ($answers[$index + 1] as $answerIndex => $answerText) {
                    $isCorrect = in_array($answerIndex, $correctAnswersForQuestion) ? 1 : 0;
                    $this->addAnswer($questionId, $answerText, $isCorrect);
                    error_log("Added answer: $answerText for question ID: $questionId");
                }
            }
    
            $this->db->commit();
            return $quizId;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error during quiz save: " . $e->getMessage());
            throw $e;
        }    
    }
}
?>
