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
        $adviser, $semester, $schedule, $role
    ) {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email address.";
        }
    
        $lrn_number = !empty($lrn_number) ? $lrn_number : null;
    
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
    
        $query = "INSERT INTO " . $this->accounts . " 
        (name, middle_initial, surname, gender, scholar, lrn_number, 
         school_id, date_of_birth, grade, section, strand, 
         phone_number, email, password, adviser, semester, schedule, role) 
        VALUES (:name, :middle_initial, :surname, :gender, :scholar, 
                :lrn_number, :school_id, :date_of_birth, :grade, 
                :section, :strand, :phone_number, :email, :password, 
                :adviser, :semester, :schedule, :role)";
    
        try {
            $stmt = $this->conn->prepare($query);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':middle_initial', $middle_initial, PDO::PARAM_STR);
$stmt->bindValue(':surname', $surname, PDO::PARAM_STR);
$stmt->bindValue(':gender', $gender, PDO::PARAM_STR);
$stmt->bindValue(':scholar', $scholar, PDO::PARAM_STR);
$stmt->bindValue(':lrn_number', !empty($lrn_number) ? $lrn_number : null, !empty($lrn_number) ? PDO::PARAM_STR : PDO::PARAM_NULL);
$stmt->bindValue(':school_id', $school_id, PDO::PARAM_STR);
$stmt->bindValue(':date_of_birth', $date_of_birth, PDO::PARAM_STR);
$stmt->bindValue(':grade', $grade, PDO::PARAM_STR);
$stmt->bindValue(':section', $section, PDO::PARAM_STR);
$stmt->bindValue(':strand', $strand, PDO::PARAM_STR);
$stmt->bindValue(':phone_number', $phone_number, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
$stmt->bindValue(':adviser', $adviser, PDO::PARAM_STR);
$stmt->bindValue(':semester', $semester, PDO::PARAM_STR);
$stmt->bindValue(':schedule', $schedule, PDO::PARAM_STR);
$stmt->bindValue(':role', $role, PDO::PARAM_STR);
    
            if ($stmt->execute()) {
                if (!empty($phone_number)) {
                    // error_log("SQL Error: " . implode(", ", $stmt->errorInfo()));
                    // return "Database error: " . implode(", ", $stmt->errorInfo());
                    $smsSent = $this->sendSMSNotification($phone_number, $schedule, $school_id, $password);
    
                    if (!$smsSent) {
                        error_log("SMS failed for: " . $phone_number);
                        return "Registration successful, but SMS failed.";
                    }
                }
                return true;
            } else {
                error_log("Insert failed: " . implode(", ", $stmt->errorInfo()));
                return "Database error.";
            }
        } catch (Exception $e) {
            error_log("Exception: " . $e->getMessage());
            return "An error occurred.";
        }
    }
    
    // Method to delete user if SMS is not sent
    private function deleteUserByPhone($phone_number) {
        $query = "DELETE FROM " . $this->accounts . " WHERE phone_number = :phone_number";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->execute();
    }
    
    // SMS Notification Method using SMS API
    public function sendSMSNotification($phone_number, $schedule, $school_id, $password) {
        $url = 'https://sms.iprogtech.com/api/v1/sms_messages';
        $message = "Welcome to Gateways Institute of Science and Technology! " . PHP_EOL .
        "Your School ID: {$school_id}" . PHP_EOL .
        "Password: {$password}" . PHP_EOL . PHP_EOL .
        "your schedule: {$schedule}" . PHP_EOL . PHP_EOL .
        "Please keep this information secure. If you have any questions, feel free to contact the school administration. ";
        // Format the phone number properly
        if (strpos($phone_number, '0') === 0) {
            $phone_number = '+63' . substr($phone_number, 1);
        }

        $data = [
            'api_token'    => '8ef0db8234c4f5c817ad067342f011f987c45c7e',
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

        $query = "SELECT school_id, name, surname  FROM " . $this->accounts . " WHERE role = :role";
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
                    '1st semester' => [
                        'Comprog 1 & 2', 'Oral Communication', 'PE 1', 'PR 1', 'Komunikasyon at Pananaliksik',
                        'General Mathematics', 'Earth & Life Science', 'Empowerment Technology', '21st Century',
                    ],
                    '2nd semester' => [
                        'Comprog 3 & 4', 'PE 2', 'Reading and Writing', 'Pagbasa at Pagsusuri', 'Statistics and Probabilities',
                        'Personal Development', 'EAPP', 'Phy Sci', 'TechVoc',
                    ],
                ],
                'ABM' => [
                    '1st semester' => [
                        'Oral Com', 'Komunikasyon', 'Gen Math', 'Earth & Life', '21st Century', 'PE 1', 'PR 1',
                        'Empowerment Technology', 'Business Math', 'Organization & Management',
                    ],
                    '2nd semester' => [
                        'Reading & Writing', 'Pagbasa', 'Stats & Prob', 'PE 2', 'Personal Development', 'Physical Science',
                        'EAPP', 'Filipino sa Larang ng Akademik', 'FABM 1', 'Principles Of Marketing',
                    ],
                ],
                'HE' => [
                    '1st semester' => [
                        'Oral Communication', 'General Mathematics', 'Cookery 1 & 2', 'Empowerment Technology', 
                        'Practical Research 1', 'Komunikasyon', 'Earth and Life Science', 'PE 1', '21st Century',
                    ],
                    '2nd semester' => [
                        'Reading and Writing', 'Statistics and Probability', 'TechVoc', 'PE 2', 'Physical Science', 
                        'Cookery 3 & 4', 'Pagbasa', 'Personal Development', 'EAPP',
                    ],
                ],
            ],
            '12' => [
                
                'HUMMS' => [
                    '1st semester' => ['UCSP', 'Entrepreneurship', 'PE 3', 'Business Ethics', 'Business Finance', 'FABM 2'],
                    '2nd Semester' => ['Research in Daily Life', 'EAPP', 'Introduction to World Religions', 'PE 4', 'Capstone Project'],
                ],
                'ABM' => [
                    '1st semester' => ['UCSP', 'CPAR', 'Philosophy', 'PE 3', 'PR 2', 'Entrepreneurship', 'FABM 2', 'Applied Economics'],
                    '2nd semester' => ['Business Ethics', 'Business Finance', 'MIL', 'Business Enterprise', 'I.I.I', 'PE 4'],
                ],
                'ict' => [
                    '1st semester' => ['CPAR', 'Intro to Philo', 'CSS 1 & 2', 'Entrepreneurship', 'PE 3', 'PR 2', 'UCSP'],
                    '2nd semester' => ['MIL', 'PE 4', 'I.I.I', 'CHS 3 & 4', 'Work Immersion'],
                ],
                'HE' => [
                    '1st semester' => ['Entrepreneurship', 'Practical Research 2', 'PE 3', 'FBS', 'CPAR', 'UCSP', 'Intro to Philosophy'],
                    '2nd semester' => ['Work Immersion', 'Capstone Project', 'MIL', 'EAPP', 'PE 4', 'Cookery'],
                ],
            ],
        ]; 
        
    }
 
    public function getSubjects($grade, $strand, $semester) {
        return $this->subjects[$grade][$strand][$semester] ?? [];
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
    public function getUserGradesWithAdviser($user_id) {
        $query = "
            SELECT 
                u.surname, 
                u.user_grades, 
                ug.grade,
                u.adviser_name
            FROM 
                users u
            JOIN 
                user_grades ug ON u.user_id = ug.user_id
            WHERE 
                u.user_id = :user_id
        ";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt;
    }
    
    
    
    

}

?>