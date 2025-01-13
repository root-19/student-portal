<?php

use PHPMailer\PHPMailer\PHPMailer;
include_once __DIR__ . '/../vendor/autoload.php';




class UserModel {
    private $conn;
    private $accounts = "users";

    public function __construct($db) {
        $this->conn = $db;
    }
  
   

    public function register(
        $name, $middle_initial, $surname, $gender, $scholar, 
        $lrn_number, $school_id, $date_of_birth, $grade, 
        $section, $strand, $phone_number, $email, $password, $role
    ) {
        // Insert query for registering the user
        $query = "INSERT INTO " . $this->accounts . " 
            (name, middle_initial, surname, gender, scholar, lrn_number, 
             school_id, date_of_birth, grade, section, strand, 
             phone_number, email, password, role) 
            VALUES (:name, :middle_initial, :surname, :gender, :scholar, 
                    :lrn_number, :school_id, :date_of_birth, :grade, 
                    :section, :strand, :phone_number, :email, :password, :role)";
        
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
                return true; // Email sent successfully
            } else {
                error_log("Mailer Error: {$mail->ErrorInfo}");
                return false; // Return false if email sending fails
            }
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log("Mailer Error: {$e->getMessage()}");
            return false; // Return false if an exception occurs
        }
    }
    

    public function login($school_id, $password) {
        // Prepare SQL query to get the user by email
        $stmt = $this->conn->prepare("SELECT id, name, school_id, role, password FROM " . $this->accounts . " WHERE school_id = ?");
        
        // Bind the parameter using PDO's bindParam method
        $stmt->bindParam(1, $school_id, PDO::PARAM_STR);
        
        // Execute the statement
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        // Check if a user is found
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


    // Fetch users with role and search term (optional)
    public function getUsersByRoleAndSearch($role, $searchTerm = '') {
        $query = "SELECT school_id, name, surname, email FROM " . $this->accounts . " WHERE role = :role";
        
        // Add search filter if a search term is provided
        if (!empty($searchTerm)) {
            $query .= " AND school_id LIKE :searchTerm";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role', $role);
        
        if (!empty($searchTerm)) {
            $searchTerm = "%" . $searchTerm . "%";
            $stmt->bindParam(':searchTerm', $searchTerm);
        }

        // Execute query
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
}
    

?>