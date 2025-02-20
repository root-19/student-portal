<?php

use PHPMailer\PHPMailer\PHPMailer;
include_once __DIR__ . '/../vendor/autoload.php';




class UserModel {
    private $conn;
    private $accounts = "users";
    private $gradesTable = 'user_grades';

    public function __construct($db) {
        $this->conn = $db;
    }
  
   
    public function register(
        $name, $middle_initial, $surname, $gender, $scholar, 
        $lrn_number, $school_id, $date_of_birth, $grade, 
        $section, $strand, $phone_number, $email, $password, 
        $adviser, $semester, $role
    ) {
        // Validate email before proceeding
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email address.";
        }
    
        // Convert empty LRN to NULL
        $lrn_number = !empty($lrn_number) ? $lrn_number : null;
    
        // Check if the LRN number already exists (only if LRN is provided)
        if (!empty($lrn_number)) {
            $checkQuery = "SELECT COUNT(*) FROM " . $this->accounts . " WHERE lrn_number = :lrn_number";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':lrn_number', $lrn_number);
            $checkStmt->execute();
            $count = $checkStmt->fetchColumn();
    
            if ($count > 0) {
                return "LRN number already exists. Please use a different one.";
            }
        }
    
        // Insert query for registering the user
        $query = "INSERT INTO " . $this->accounts . " 
        (name, middle_initial, surname, gender, scholar, lrn_number, 
         school_id, date_of_birth, grade, section, strand, 
         phone_number, email, password, adviser, semester, role) 
        VALUES (:name, :middle_initial, :surname, :gender, :scholar, 
                :lrn_number, :school_id, :date_of_birth, :grade, 
                :section, :strand, :phone_number, :email, :password, 
                :adviser, :semester, :role)";
    
        $stmt = $this->conn->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':middle_initial', $middle_initial);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':scholar', $scholar);
        $stmt->bindParam(':lrn_number', $lrn_number, PDO::PARAM_NULL); // Allow NULL values
        $stmt->bindParam(':school_id', $school_id);
        $stmt->bindParam(':date_of_birth', $date_of_birth);
        $stmt->bindParam(':grade', $grade);
        $stmt->bindParam(':section', $section);
        $stmt->bindParam(':strand', $strand);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':adviser', $adviser);
        $stmt->bindParam(':semester', $semester);
        $stmt->bindParam(':role', $role);
    
        if ($stmt->execute()) {
            // Once registration is successful, send SMS notification to the registered user
            if (!empty($phone_number)) {
                $smsSent = $this->sendSMSNotification($phone_number, $school_id, $password);
    
                if ($smsSent) {
                    return true; // Registration and SMS sending successful
                } else {
                    // If SMS sending fails, delete the user and return false
                    $this->deleteUserByPhone($phone_number);
                    return false; 
                }
            }
        }
        
        return false; 
    }
    
    // Method to delete user if SMS is not sent
    private function deleteUserByPhone($phone_number) {
        $query = "DELETE FROM " . $this->accounts . " WHERE phone_number = :phone_number";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->execute();
    }
    
    // SMS Notification Method using SMS API
    public function sendSMSNotification($phone_number, $school_id, $password) {
        $url = 'https://sms.iprogtech.com/api/v1/sms_messages';
        $message = "Welcome to Gateways Institute of Science and Technology! " . PHP_EOL .
        "Your School ID: {$school_id}" . PHP_EOL .
        "Password: {$password}" . PHP_EOL . PHP_EOL .
        "Please keep this information secure. If you have any questions, feel free to contact the school administration. ";
        // Format the phone number properly
        if (strpos($phone_number, '0') === 0) {
            $phone_number = '+63' . substr($phone_number, 1);
        }

        $data = [
            'api_token'    => '',
            'message'      => $message,
            'phone_number' => $phone_number,
            'sms_provider' => 1 
        ];
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        
        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        error_log("SMS API Response: " . $response);
    
        if ($response && !$curlError) {
            error_log("SMS sent successfully to: " . $phone_number);
            return true; 
        } else {
            error_log("SMS failed for: " . $phone_number . " - Error: " . $curlError);
            return false; 
        }
    }

    public function login($school_id, $password) {

        $stmt = $this->conn->prepare("SELECT id, name, school_id, role, password FROM " . $this->accounts . " WHERE school_id = ?");
        $stmt->bindParam(1, $school_id, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {   
     if (password_verify($password, $user['password'])) {
       return $user;  
            }
        }
        return false;  
    }

   
    public function getUsersByRole($role) {

        $query = "SELECT school_id, name, surname,  FROM " . $this->accounts . " WHERE role = :role";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsersByRoleAndSearch($role, $searchTerm = '') {
        $query = "SELECT school_id, name, surname, email, grade,scholar, section FROM " . $this->accounts . " WHERE role = :role";
        if (!empty($searchTerm)) {
            $query .= " AND (school_id LIKE :searchTerm OR name LIKE :searchTerm OR surname LIKE :searchTerm)";
        }
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role', $role);
        
        if (!empty($searchTerm)) {
            $searchTerm = "%" . $searchTerm . "%";
            $stmt->bindParam(':searchTerm', $searchTerm);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // update the password sana all INA UPDATE
    public function updatePassword($school_id, $new_password) {
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE " . $this->accounts . " SET password = :password WHERE school_id = :school_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':school_id', $school_id);

        return $stmt->execute();
    }

    public function getUsersWithAdvisers() {
        $query = "
            SELECT 
                u.school_id AS user_school_id, 
                u.name AS user_name, 
                u.surname AS user_surname, 
                u.grade AS user_grade, 
                u.strand AS user_grade_strand,
                u.semester AS user_semester,
                u.adviser AS adviser_name, 
                t.school_id AS teacher_school_id, 
                t.name AS teacher_name, 
                t.surname AS teacher_surname,
                u.strand AS user_strand, 
                u.section AS user_section
            FROM " . $this->accounts . " u
            INNER JOIN " . $this->accounts . " t
            ON u.adviser = t.name
            WHERE u.role = 'user' AND t.role = 'teacher'
            ORDER BY u.strand, u.section;
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUserById($userId) {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getUsersById($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //gradesubehhlfkasw
  
    public function UserById($userId) {
        // Fix the typo in the prepare method
        $stmt = $this->conn->prepare("SELECT grade, strand, semester FROM users WHERE id = :id"); 
        $stmt->execute([':id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    // Save grade for a subject
    public function addGrade($userId, $subject, $grade) {
        $query = "INSERT INTO " . $this->gradesTable . " (user_id, subject, grade) VALUES (:user_id, :subject, :grade)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':grade', $grade);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }



    public function UsersWithAdvisers() {
        // Query to fetch user and adviser data, including the user ID
        $query = "
            SELECT 
                u.id AS user_school_id,        -- Fetch user id
                u.name AS user_name, 
                u.surname AS user_surname, 
                u.grade AS user_grade, 
                u.strand AS user_strand, 
                u.semester AS user_semester,
                u.adviser AS adviser_name, 
                t.name AS teacher_school_id, 
                t.name AS teacher_name, 
                t.surname AS teacher_surname
            FROM " . $this->accounts . " u
            INNER JOIN " . $this->accounts . " t
            ON u.adviser = t.name
            WHERE u.role = 'user' AND t.role = 'teacher'
            ORDER BY u.strand, u.section;
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Fetch subjects based on grade, strand, and semester
        $predefinedSubjects = $this->getPredefinedSubjects();
    
        foreach ($users as &$user) {
            $grade = $user['user_grade'];
            $strand = strtolower($user['user_strand']); // Match array keys
            $semester = $user['user_semester'];
    
            $user['subjects'] = $predefinedSubjects[$grade][$strand][$semester] ?? [];
        }
    
        return $users;
    }
    
    // Define predefined subjects as an array
   public function getPredefinedSubjects() {
        return  [ 
            '11' => [
                'ICT' => [
                    '1st Semester' => [
                        'Comprog 1 & 2', 'Oral Communication', 'PE 1', 'PR 1', 'Komunikasyon at Pananaliksik',
                        'General Mathematics', 'Earth & Life Science', 'Empowerment Technology', '21st Century',
                    ],
                    '2nd Semester' => [
                        'Comprog 3 & 4', 'PE 2', 'Reading and Writing', 'Pagbasa at Pagsusuri', 'Statistics and Probabilities',
                        'Personal Development', 'EAPP', 'Phy Sci', 'TechVoc',
                    ],
                ],
                'ABM' => [
                    '1st Semester' => [
                        'Oral Com', 'Komunikasyon', 'Gen Math', 'Earth & Life', '21st Century', 'PE 1', 'PR 1',
                        'Empowerment Technology', 'Business Math', 'Organization & Management',
                    ],
                    '2nd Semester' => [
                        'Reading & Writing', 'Pagbasa', 'Stats & Prob', 'PE 2', 'Personal Development', 'Physical Science',
                        'EAPP', 'Filipino sa Larang ng Akademik', 'FABM 1', 'Principles Of Marketing',
                    ],
                ],
                'HE' => [
                    '1st Semester' => [
                        'Oral Communication', 'General Mathematics', 'Cookery 1 & 2', 'Empowerment Technology', 
                        'Practical Research 1', 'Komunikasyon', 'Earth and Life Science', 'PE 1', '21st Century',
                    ],
                    '2nd Semester' => [
                        'Reading and Writing', 'Statistics and Probability', 'TechVoc', 'PE 2', 'Physical Science', 
                        'Cookery 3 & 4', 'Pagbasa', 'Personal Development', 'EAPP',
                    ],
                ],
            ],
            '12' => [
                
                'HUMMS' => [
                    '1st Semester' => ['UCSP', 'Entrepreneurship', 'PE 3', 'Business Ethics', 'Business Finance', 'FABM 2'],
                    '2nd Semester' => ['Research in Daily Life', 'EAPP', 'Introduction to World Religions', 'PE 4', 'Capstone Project'],
                ],
                'ABM' => [
                    '1st Semester' => ['UCSP', 'CPAR', 'Philosophy', 'PE 3', 'PR 2', 'Entrepreneurship', 'FABM 2', 'Applied Economics'],
                    '2nd Semester' => ['Business Ethics', 'Business Finance', 'MIL', 'Business Enterprise', 'I.I.I', 'PE 4'],
                ],
                'ict' => [
                    '1st Semester' => ['CPAR', 'Intro to Philo', 'CSS 1 & 2', 'Entrepreneurship', 'PE 3', 'PR 2', 'UCSP'],
                    '2nd Semester' => ['MIL', 'PE 4', 'I.I.I', 'CHS 3 & 4', 'Work Immersion'],
                ],
                'HE' => [
                    '1st Semester' => ['Entrepreneurship', 'Practical Research 2', 'PE 3', 'FBS', 'CPAR', 'UCSP', 'Intro to Philosophy'],
                    '2nd Semester' => ['Work Immersion', 'Capstone Project', 'MIL', 'EAPP', 'PE 4', 'Cookery'],
                ],
            ],
        ]; 
        
    }
 

     // Fetch user details with grades
     public function getUserGrades($user_id) {
        $query = "SELECT users.id, users.name, users.surname, users.grade AS user_grade, users.semester, users.scholar, users.school_id, user_grades.grade AS grade_from_user_grades
                  FROM users 
                  JOIN user_grades ON users.id = user_grades.user_id
                  WHERE users.id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        return $stmt;
    }
    
    public function getUserDetails($user_id) {
        $query = "SELECT surname, grade, semester FROM users WHERE id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getUsersByGradeSemesterAndSearch($grade, $semester, $searchTerm) {
        $query = "SELECT * FROM users WHERE grade = :grade AND semester = :semester";
        
        if (!empty($searchTerm)) {
            $query .= " AND (name LIKE :search OR surname LIKE :search OR school_id LIKE :search)";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':grade', $grade);
        $stmt->bindParam(':semester', $semester);
        
        if (!empty($searchTerm)) {
            $searchTerm = "%$searchTerm%";
            $stmt->bindParam(':search', $searchTerm);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

}

?>