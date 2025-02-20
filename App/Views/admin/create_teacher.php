<?php
require_once "../../Database/Database.php";
require_once "../../Controller/UserModel.php";
require_once "../../Controller/Auth.php";
require_once "../../vendor/autoload.php";

use Controller\Auth;

$db = new Database();
$conn = $db->connect();
$userModel = new UserModel($conn);
$auth = new Auth($userModel);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'] ?? '';
    $middle_initial = $_POST['middle_initial'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $school_id = $_POST['school_id'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Role is automatically set to "teacher"
    $role = 'teacher';

    // Register the teacher
    $registrationSuccess = $userModel->register(
        $name,
        $middle_initial,
        $surname,
        $gender,
        '', // Scholar not required
        '', // LRN Number not required
        $school_id,
        $date_of_birth,
        '', // Grade not required
        '', // Section not required
        '', // Strand not required
        $phone_number,
        $email,
        $password,
        '', // Adviser not required
        '', // Semester not required
        $role
    );

    if ($registrationSuccess) {
        error_log("Teacher registration successful! An SMS has been sent to {$phone_number}.");
    } else {
        error_log("Registration failed. Please try again or contact support.");
    }
}
?>

<!-- Include Sidebar -->
<?php include "./layout/sidebar.php"; ?>

<div class=" flex items-center justify-center mr-40 mt-10 sm:ml-50">
    <div class="bg-white bg-opacity-90 p-6 rounded-lg shadow-lg w-full max-w-md md:max-w-lg lg:max-w-xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Register Teacher</h1>
    <form method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-black">
    <input type="text" name="name" placeholder="First Name" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="text" name="middle_initial" placeholder="Middle Initial" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="text" name="surname" placeholder="Last Name" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="gender" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="" disabled selected>Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            <input type="text" name="school_id" placeholder="School ID" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="date" name="date_of_birth" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="text" name="phone_number" placeholder="Phone Number" required pattern="\d{10,15}" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="email" name="email" placeholder="Email" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
           
            <input type="password" name="password" placeholder="Password" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">

        <div class="col-span-2">
            <button type="submit" class="w-full text-white bg-green-800 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                Register Teacher
            </button>
        </div>
    </form>
</div>
</div>