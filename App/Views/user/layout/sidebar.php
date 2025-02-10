<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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
  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-amber-600 text-white font-bold transform -translate-x-full transition-transform duration-300 lg:translate-x-0 lg:static lg:shadow-lg">
      <div class="flex flex-col h-full">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between px-4 py-4 border-b border-orange-400">
          <h1 class="text-xl text-white font-bold"><?php echo htmlspecialchars($_SESSION['user_name']);?> </h1>
          <button id="closeSidebar" class="lg:hidden text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <!-- Sidebar Menu -->
        <ul class="flex-grow p-4 space-y-2">
          <li>
            <a href="./profile.php" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105">
              Profile
            </a>
          </li>
          <li>
            <a href="./announcement.php" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105">
              Announcement
            </a>
          </li>
          
          <li>
            <a href="./module.php" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105">
            Module
            </a>
          </li>
          <li>
            <a href="./view-score.php" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105">
           View Score
            </a>
          </li>
          <li>
            <a href="./subject.php" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105">
              Subject
            </a>
          </li>
          <li>
            <a href="./vote.php" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105">
            Vote events
            </a>
          </li>
          <li>
            <a href="./quiz.php" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105">
           Quiz
            </a>
          </li>
          <li>
            <a href="./grading.php" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105">
            Grading Card
            </a>
          </li>
          <li>
            <a href="./logout.php" class="block px-4 py-2 rounded-md hover:bg-gray-700 hover:text-orange-400 transition-all duration-300 transform hover:scale-105">
              Logout
            </a>
          </li>
        </ul>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-2 flex flex-col overflow-hidden lg:pl-20">
      <!-- Mobile Navbar -->
      <header class="bg-black shadow-md p-4 lg:hidden flex items-center justify-between">
        <button id="openSidebar" class="text-orange-500">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
        <h1 class="text-lg font-bold text-orange-500">Student portal</h1>
      </header>

      <!-- Content Area -->
      <!-- <main class="p-6">
        <h1 class="text-3xl font-bold text-orange-500 mb-4">Welcome to My App</h1>
        <p class="text-gray-700">This is the main content area. Resize the screen to see the responsive sidebar in action!</p>
      </main> -->
    <!-- </div>
  </div> -->
    <!-- Main Content Area -->
    <main class="p-6 mt-12">
        <!-- <h1 class="text-3xl font-bold text-orange-500 mb-4">Welcome to the Student Portal</h1>
        <p class="text-gray-700">Navigate using the sidebar to access your profile, grades, and announcements.</p>
    -->

  <!-- JavaScript -->
  <script>
    const sidebar = document.getElementById('sidebar');
    const openSidebar = document.getElementById('openSidebar');
    const closeSidebar = document.getElementById('closeSidebar');

    openSidebar.addEventListener('click', () => {
      sidebar.classList.remove('-translate-x-full');
    });

    closeSidebar.addEventListener('click', () => {
      sidebar.classList.add('-translate-x-full');
    });
  </script>
</body>
</html>
