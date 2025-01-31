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

$message = ""; 

// Handle registration request
/**
 * Handles the registration request for a new student.
 * This code is executed when the 'register' button is submitted in the create_student.php form.
 * It retrieves the form inputs, validates the required fields, and calls the `registerUser` method
 * of the `Auth` class to handle the user registration process.
 * If the registration is successful, a success message is set in the `$message` variable.
 * If there are any validation errors, an error message is set in the `$message` variable.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {

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
    $semester = $_POST['semester'] ?? null;
    $adviser = $_POST['adviser'] ?? null;

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
            $adviser,
            $semester
        );

        if ($result === true) {
            $message = "Registration successful!";
        } else {
            $message = $result;
    }
}
}
?>
<?php include "./layout/sidebar.php"; ?>

<div class=" flex items-center justify-center mr-40 mt-10 sm:ml-50">
    <div class="bg-white bg-opacity-90 p-6 rounded-lg shadow-lg w-full max-w-md md:max-w-lg lg:max-w-xl">
        <h2 class="text-xl md:text-2xl font-bold mb-4 text-center  text-black"> Student Register</h2>
        <?php if ($message): ?>
            <div class="bg-green-200 p-2 text-red-600 text-center rounded mb-3"><?php echo $message; ?></div>
        <?php endif; ?>
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
            <select name="scholar" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="" disabled selected>Select Scholar Type</option>
                <option value="scholar">Scholar</option>
                <option value="pay">Pay</option>
            </select>
            <input type="text" name="lrn_number" placeholder="LRN Number" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="text" name="school_id" placeholder="School ID" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="date" name="date_of_birth" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="text" name="grade" placeholder="Grade Level" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="text" name="section" placeholder="Section" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="adviser" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    <option value="" disabled selected>Select Adviser</option>
    <option value="adviser1">Adviser 1</option>
    <option value="adviser2">Adviser 2</option>
    <option value="adviser3">Adviser 3</option>
    <option value="adviser4">Adviser 4</option>
    <option value="adviser5">Adviser 5</option>
    <option value="adviser6">Adviser 6</option>
    <option value="adviser7">Adviser 7</option>
    <option value="adviser8">Adviser 8</option>
    <option value="adviser9">Adviser 9</option>
    <option value="adviser10">Adviser 10</option>
</select>

            <input type="text" name="strand" placeholder="Strand" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="text" name="phone_number" placeholder="Phone Number" required pattern="\d{10,15}" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="email" name="email" placeholder="Email" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="password" name="password" placeholder="Password" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="text" name="semester" placeholder="semester" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div class="col-span-2">
                <button type="submit" name="register"  class="w-full text-white bg-blue-700 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Register</button>
            </div>
        </form>
      
    </div>
</div>
