<?php

namespace Controller;

class Auth {
    private $userModel;

    public function __construct($userModel) {
        $this->userModel = $userModel;
    }

   
    
    public function registerUser(
        $name, $middle_initial, $surname, $gender, $scholar, 
        $lrn_number, $school_id, $date_of_birth, $grade, 
        $section, $strand, $phone_number, $email, $password
    ) {
        $role = 'user'; // Set the default role
        
        // Pass parameters in the correct order
        return $this->userModel->register(
            $name, $middle_initial, $surname, $gender, $scholar, 
            $lrn_number, $school_id, $date_of_birth, $grade, 
            $section, $strand, $phone_number, $email, $password, $role
        );
    }
    
    

    public function loginUser($school_id, $password) {
        // Attempt to get user details from the database
        $user = $this->userModel->login($school_id, $password);
    
        if ($user) {
            // Start the session and regenerate session ID for security
            session_start();
            session_regenerate_id(true);  // Prevent session fixation
    
            // Store user details in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['name'];
            // $_SESSION['user_surname'] = $user['surname']; // Optional
            
            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    header("Location: ../Views/admin/index.php");
                    exit();
                case 'teacher':
                    header("Location: ../Views/teacher/index.php");
                    exit();
                default: // For any other role, assume user
                    header("Location: ../Views/user/index.php");
                    exit();
            }
        }
        
        // If login fails, return false
        return false;
    }
    
}
?>
