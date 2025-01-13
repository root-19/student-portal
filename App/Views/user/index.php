<?php
require_once "../../Controller/Middleware.php";
Middleware::auth('user');
?>



<div class="p-6 max-w-3xl mx-auto text-center">
    <h1 class="text-3xl font-bold mt-40" id="welcome-text">Welcome to Admin account of Liceo de Cagayan University!</h1>
    <p class="text-gray-700 mb-6">Hello, <strong id="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>!</p>
    <!-- Logout Button -->
    <a href="./logout.php" class="mt-4 inline-block px-6 py-2 text-white bg-red-500 rounded-md hover:bg-red-600">
        Logout
    </a>
</div>
