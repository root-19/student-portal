<?php
require_once "./Controller/Middleware.php";
Middleware::guest();

require_once "./Database/Database.php";
require_once "./Controller/UserModel.php";
require_once "./Controller/Auth.php";
require_once "./vendor/autoload.php";

use Controller\Auth;

$db = new Database();
$conn = $db->connect();
$userModel = new UserModel($conn);
$auth = new Auth($userModel);

$message = ""; // Default message variable

// Handle registration request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Retrieve form inputs and use null as the default if not set
    $name = $_POST['name'] ?? null;
    $middle_initial = $_POST['middle_initial'] ?? null;
    $surname = $_POST['surname'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $scholar = $_POST['scholar'] ?? null;
    $lrn_number = $_POST['lrn_number'] ?? null;
    $school_id = $_POST['school_id'] ?? null;
    $date_of_birth = $_POST['date_of_birth'] ?? null;
    $grade = $_POST['grade'] ?? null;
    $section = $_POST['section'] ?? null;
    $strand = $_POST['strand'] ?? null;
    $phone_number = $_POST['phone_number'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    // Validate required fields
    if (!$gender || !$scholar) {
        $message = "Please select both gender and scholar type.";
    } else {
        // Call the registerUser method to handle user registration
        $result = $auth->registerUser(
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
            $adviser
        );

        if ($result === true) {
            $message = "Registration successful!";
        } else {
            $message = $result; // Display error message returned from registerUser
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./public/style.css" />
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">

    <div class="bg-gray-700 bg-opacity-50 fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white bg-opacity-80 p-8 rounded-lg shadow-lg w-full sm:w-96 md:w-1/2 lg:w-2/3 xl:w-1/2">
            <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
            <?php if ($message): ?>
                <div class="bg-green-200 p-2 text-red-600 text-center rounded mb-4"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <input type="text" name="name" placeholder="First Name" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <input type="text" name="middle_initial" placeholder="Middle Initial" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <input type="text" name="surname" placeholder="Last Name" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <select name="gender" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <select name="scholar" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled selected>Select Scholar Type</option>
                        <option value="scholar">Scholar</option>
                        <option value="pay">Pay</option>
                    </select>
                </div>
                <div>
                    <input type="text" name="lrn_number" placeholder="LRN Number" required  class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <input type="text" name="school_id" placeholder="School ID" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <input type="date" name="date_of_birth" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <input type="text" name="grade" placeholder="Grade Level" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <input type="text" name="section" placeholder="Section" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <input type="text" name="strand" placeholder="Strand" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <input type="text" name="phone_number" placeholder="Phone Number" required pattern="\d{10,15}" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <input type="email" name="email" placeholder="Email " required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-span-2">
                    <button type="submit" name="register" style="background-color:var(--maroon)" class="w-full text-white py-3 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Register</button>
                </div>
            </form>
            <p class="mt-4 text-center text-gray-600">Already have an account? <a href="./login.php" style="color:var(--yellow)" class="hover:underline">Login here</a></p>
        </div>
    </div>
</body>
</html>
