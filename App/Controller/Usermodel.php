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
        $section, $strand, $phone_number, $email, $password, $adviser,$semester, $role
    ) {
        // Insert query for registering the user
        $query = "INSERT INTO " . $this->accounts . " 
            (name, middle_initial, surname, gender, scholar, lrn_number, 
             school_id, date_of_birth, grade, section, strand, 
             phone_number, email, password, adviser, role) 
            VALUES (:name, :middle_initial, :surname, :gender, :scholar, 
                    :lrn_number, :school_id, :date_of_birth, :grade, 
                    :section, :strand, :phone_number, :email, :password, :adviser,:semester, :role)";
        
        $stmt = $this->conn->prepare($query);
    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':middle_initial', $middle_initial);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':scholar', $scholar);
        $stmt->bindParam(':lrn_number', $lrn_number);
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
            // Once registration is successful, send email with credentials
            $emailSent = $this->sendEmailNotification($email, $school_id, $password);
    
            // If email is successfully sent, return true, otherwise return false
            if ($emailSent) {
                return true; // Registration and email sending successful
            } else {
                // If email sending fails, delete the user and return false
                $this->deleteUserByEmail($email);
                return false; // Registration successful, but email sending failed
            }
        }
        
        return false; // Registration failed
    }
    
    // Method to delete user if email is not sent
    private function deleteUserByEmail($email) {
        $query = "DELETE FROM " . $this->accounts . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    }
    
    // Email Notification Method
    public function sendEmailNotification($email, $school_id, $password) {
        $mail = new \PHPMailer\PHPMailer\PHPMailer();
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'hperformanceexhaust@gmail.com';
            $mail->Password = 'wolv wvyy chhl rvvm';  
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            // Recipients
            $mail->setFrom('hperformanceexhaust@gmail.com', 'GIST');
            $mail->addAddress($email);
    
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your Registration Details';
            $mail->Body = "
                <h1>Welcome to Your School</h1>
                <p>Dear Student,</p>
                <p>Thank you for registering! Here are your credentials:</p>
                <ul>
                    <li><strong>School ID:</strong> {$school_id}</li>
                    <li><strong>Password:</strong> {$password}</li>
                </ul>
                <p>Please keep this information safe and do not share it with anyone.</p>
                <p>Best regards,<br>Your School Team</p>
            ";
    
            // Debugging (optional)
            // $mail->SMTPDebug = 2;
    
            if ($mail->send()) {
                return true; 
            } else {
                error_log("Mailer Error: {$mail->ErrorInfo}");
                return false; 
            }
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("Mailer Error: {$e->getMessage()}");
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
    
    // Check if email exists in the users table
    // public function checkEmailExists($email) {

    //     if (!preg_match('/@edu\.ph$/', $email)) {
    //         return false;
    //     }

    // // prepare the query
    //     $stmt = $this->conn->prepare("SELECT * FROM " . $this->accounts . " WHERE email = :email");
    //     $stmt->bindParam(':email', $email);
    //     $stmt->execute();
        
    //     return $stmt->rowCount() > 0;
    // }

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
                'ict' => [
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
                'STEM' => [
                    '1st Semester' => ['UCSP', 'Philosophy', 'PE 3', 'Physical Science', 'EAPP', 'FABM 2'],
                    '2nd Semester' => ['Work Immersion', 'Applied Economics', 'Research in Daily Life', 'PE 4', 'Capstone Project'],
                ],
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
    // public function saveGrades($userId, $subject, $grade) {
    //     $query = "INSERT INTO user_grades (user_id, subject, grade) VALUES (:user_id, :subject, :grade)";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bindParam(':user_id', $userId);
    //     $stmt->bindParam(':subject', $subject);
    //     $stmt->bindParam(':grade', $grade);
    //     return $stmt->execute();
    // }
    
}

    

?>