<?php
require_once "../../Controller/Middleware.php";
Middleware::auth('admin');
?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.tailwindcss.com"></script>

<div class="p-6 max-w-3xl mx-auto text-center">
    <h1 class="text-3xl font-bold mt-40" id="welcome-text">Welcome to Admin account of Liceo de Cagayan University!</h1>
    <p class="text-gray-700 mb-6">Hello, <strong id="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>!</p>
    <!-- Logout Button -->
    <!-- <a href="#" id="logout" class="block px-4 py-2 text-gray-300  hover:text-white">Logout</a> -->
</div>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>admin || side</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../../public/style.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body class="flex min-h-screen bg-gray-100">

  <!-- Sidebar -->
  <div id="sidebar" style="background-color: var(--maroon);" class="fixed inset-y-0 left-0 w-64 text-white transition-transform transform -translate-x-full md:translate-x-0 md:relative z-20">
    <div class="p-4 flex items-center justify-between">
      <!-- Logo -->
      <div class="flex flex-col items-center">
      <img src="../../../Storage/image/background.jpg" alt="Logo" class="logo">
  <h2 class="text-lg font-bold mt-2 ml-5">Liceo de Cagayan University</h2>
</div>

      <!-- Hide Sidebar Button -->
      <button id="closeSidebar" class="text-gray-300 hover:text-white md:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    <nav class="mt-6">
    <a href="index.php" class="block px-4 py-2 text-gray-300  hover:text-white">Home</a>
      <a href="Student.php" class="block px-4 py-2 text-gray-300  hover:text-white">Search student</a>
      <a href="Message.php" class="block px-4 py-2 text-gray-300 hover:text-white">Message</a>
      <a href="profile.php" class="block px-4 py-2 text-gray-300  hover:text-white">Profile managament</a>
      <a href="create_request.php" class="block px-4 py-2 text-gray-300  hover:text-white">Request documents</a>
      <a href="" class="block px-4 py-2 text-gray-300  hover:text-white">Notifications</a>
      <a href="documents.php" class="block px-4 py-2 text-gray-300  hover:text-white">Documents
      <a href="#" id="logout" class="block px-4 py-2 text-gray-300  hover:text-white">Logout</a>
    </nav>
  </div>

  <!-- Main content -->
  <!-- <div class="flex-1 ml-0 md:ml-64 transition-all"> -->
    <!-- Mobile Header -->
    <header class="flex items-center justify-between bg-white shadow-md p-4 md:hidden">
      <h1 class="text-xl font-bold" style="color:var(--maroon)">Liceo de Cagayan University</h1>
      <!-- Show Sidebar Button -->
      <button id="openSidebar" class="text-gray-500 hover:text-gray-800">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </header>



  <script>
    const sidebar = document.getElementById("sidebar");
    const openSidebar = document.getElementById("openSidebar");
    const closeSidebar = document.getElementById("closeSidebar");

    openSidebar.addEventListener("click", () => {
      sidebar.classList.remove("-translate-x-full");
    });

    closeSidebar.addEventListener("click", () => {
      sidebar.classList.add("-translate-x-full");
    });

    // Add click event listener to the logout link
  document.getElementById('logout').addEventListener('click', function (event) {
    event.preventDefault(); // Prevent the default link behavior

    // Trigger SweetAlert confirmation
    Swal.fire({
      title: 'Are you sure?',
      text: "You are about to log out.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#991b1b',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, log out!'
    }).then((result) => {
      if (result.isConfirmed) {
        // Redirect to logout.php if confirmed
        window.location.href = 'logout.php';
      }
    });
  });
  </script>
</body>
</html>
