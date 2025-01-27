<?php
require_once "../../Controller/Middleware.php";
Middleware::auth('teacher');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>USER || side</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-white font-sans">

  <!-- Main Container -->
  <div class="flex h-screen overflow-hidden relative">
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-white transform -translate-x-full transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shadow-lg">
      <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-4 border-b border-orange-400">
          <h1 class="text-xl font-bold"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
          <button id="closeSidebar" class="lg:hidden text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <!-- Menu -->
        <ul class="flex-grow p-4 space-y-4">
          <li>
            <a href="#" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105 shadow hover:shadow-lg">
              Dashboard
            </a>
          </li>
          <li>
            <a href="#" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105 shadow hover:shadow-lg">
              Profile
            </a>
          </li>
          <li>
            <a href="./listStudent.php" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105 shadow hover:shadow-lg">
              Student List
            </a>
          </li>
          <li>

            <a href="./announcement.php" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105 shadow hover:shadow-lg">
              Announcement
            </a>
          </li>
          <li>

            <a href="./create_quiz.php" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105 shadow hover:shadow-lg">
              Create quiz
            </a>
          </li>
          
          <li>

            <a href="./users_score.php" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105 shadow hover:shadow-lg">
              Users score
            </a>
          </li>
          <li>

            <a href="./grading.php" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105 shadow hover:shadow-lg">

              Grading
            </a>
          </li>
          <li>
          <a href="./ai.php" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105 shadow hover:shadow-lg">

              chatbot
            </a>
          </li>
          <li>
            <a href="./logout.php" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105 shadow hover:shadow-lg">
              Logout
            </a>
          </li>
        </ul>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-2 flex flex-col overflow-hidden lg:pl-64">
      <!-- Mobile Navbar -->
      <header class="bg-black shadow-md p-4 lg:hidden flex items-center justify-between">
        <button id="openSidebar" class="text-orange-500">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
        <h1 class="text-lg font-bold text-orange-500">Student Portal</h1>
      </header>
    


  <!-- Background Overlay -->
  <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden lg:hidden z-30"></div>

      <!-- Content Area -->
      <!-- <main class="p-6">
        <h1 class="text-3xl font-bold text-orange-500 mb-4">Welcome to My App</h1>
        <p class="text-gray-700">This is the main content area. Resize the screen to see the responsive sidebar in action!</p>
      </main> -->
    <!-- </div>
  </div> -->


  <!-- JavaScript -->
  <script>
    const sidebar = document.getElementById('sidebar');
    const openSidebar = document.getElementById('openSidebar');
    const closeSidebar = document.getElementById('closeSidebar');
    const overlay = document.getElementById('overlay');

    openSidebar.addEventListener('click', () => {
      sidebar.classList.remove('-translate-x-full');
      overlay.classList.remove('hidden');
    });

    closeSidebar.addEventListener('click', () => {
      sidebar.classList.add('-translate-x-full');
      overlay.classList.add('hidden');
    });

    overlay.addEventListener('click', () => {
      sidebar.classList.add('-translate-x-full');
      overlay.classList.add('hidden');
    });
  </script>

