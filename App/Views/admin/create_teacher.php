<?php
require_once "../../Database/Database.php";
require_once "../../Controller/Usermodel.php";
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
    $scholar = $_POST['scholar'] ?? '';
    $lrn_number = $_POST['lrn_number'] ?? '';
    $school_id = $_POST['school_id'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? '';
    $grade = $_POST['grade'] ?? '';
    $section = $_POST['section'] ?? '';
    $strand = $_POST['strand'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $semester = $_POST['semester'] ?? '';
    $adviser = $_POST['adviser'] ?? '';

    // Role is automatically set to "teacher"
    $role = 'teacher';

    // Initialize UserModel and register the teacher
    $registrationSuccess = $userModel->register(
        $name,
        $middle_initial,
        $surname,
        $gender,
        $scholar,
        $lrn_number,
        $school_id,
        $date_of_birth,
        $grade,
        $section,
        $strand,
        $phone_number,
        $email,
        $password,
        $adviser,
        $semester,
        $role
    );

    if ($registrationSuccess) {
        // echo "<p>Teacher registration successful! An email has been sent to {$email}.</p>";
    } else {
        echo "<p>Registration failed. Please try again or contact support.</p>";
    }
}
?>

<!-- Include Sidebar -->
<?php include "./layout/sidebar.php"; ?>

<div class="bg-white shadow-md rounded-lg p-6 w-full max-w-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Register Teacher</h1>
    <form method="POST" action="" class="space-y-4 ext-black">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-black">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" required class="mt-2 block w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="middle_initial" class="block text-sm font-medium text-gray-700">Middle Initial</label>
                <input type="text" id="middle_initial" name="middle_initial" class="mt-2 block w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="surname" class="block text-sm font-medium text-gray-700">Surname</label>
                <input type="text" id="surname" name="surname" required class="mt-2 block w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                <select id="gender" name="gender" required class="mt-2 block w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div>
                <label for="school_id" class="block text-sm font-medium text-gray-700">School ID</label>
                <input type="text" id="school_id" name="school_id" required class="mt-2 block w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" required class="mt-2 block w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" required class="mt-2 block w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" required class="mt-2 block w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="md:col-span-2">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required class="mt-2 block w-full px-4 py-3 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
        </div>
        <div class="pt-6 text-center">
            <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg shadow-lg transition duration-200">
                Register Teacher
            </button>
        </div>
    </form>
</div>
