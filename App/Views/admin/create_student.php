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

// Fetch teachers from the database
$teachers = [];
$query = "SELECT id, name, surname FROM users WHERE role = 'teacher'";

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->execute();

// Fetch results
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($teachers)) {
    echo "No teachers found."; // Debugging: Check if no results are returned
}
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
    $schedule = $_POST['schedule'] ?? null;

    // Validate required fields
    if (!$gender || !$scholar || !$lrn_number) {
        $message = "Please fill in all required fields.";
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
            $semester,
            $schedule
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
            <div class="bg-green-200 p-2 text-green-600 text-center rounded mb-3"><?php echo $message; ?></div>
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
            <select name="schedule" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="" disabled selected>Select schedule</option>
                <option value="pm session 1pm - 7:15pm">Pm session 1pm - 7:15pm</option>
                <option value="am session 7am - 1pm">AM session 7am - 1pm</option>
            </select>
            <select name="scholar" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="" disabled selected>Select Scholar Type</option>
                <option value="qvr-voucher">qvr-voucher</option>
                <option value="esc-50%-off">esc-50% off</option>
                <option value="Als/balik">Als/balik </option>
            </select>
            <input type="text" name="lrn_number" placeholder="LRN Number" required 
       pattern="\d{12}" 
       title="LRN should be exactly 12 digits" 
       class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="text" name="school_id" placeholder="School ID" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="date" name="date_of_birth" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="grade" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    <option value="" disabled selected>Select Grade Level</option>
    <option value="11">11</option>
    <option value="12">12</option>
</select>

            <input type="text" name="section" placeholder="Section" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="adviser" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    <option value="" disabled selected>Select Adviser</option>
    <?php if (!empty($teachers)): ?>
        <?php foreach ($teachers as $teacher): ?>
            <option value="<?= htmlspecialchars($teacher['name']);?>">
    <?= htmlspecialchars($teacher['name'] . " " . $teacher['surname']); ?>
</option>
        <?php endforeach; ?>
    <?php else: ?>
        <option value="" disabled>No Teachers Available</option>
    <?php endif; ?>
</select>


<select name="strand" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    <option value="" disabled selected>Select Strand</option>
    <option value="ICT">ICT</option>
    <option value="ABM">ABM</option>
    <option value="HUMS">HUMS</option>
    <option value="HE">HE</option>
</select>

            <input type="text" name="phone_number" placeholder="Phone Number" required pattern="\d{10,15}" class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="email" name="email" placeholder="Email" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="password" name="password" placeholder="Password" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="text" name="semester" placeholder="semester" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div class="col-span-2">
                <button type="submit" name="register"  class="w-full text-white bg-green-800 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">Register</button>
            </div>
        </form>
      
    </div>
</div>
