<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal Landing Page</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/svg+xml" href="./Storage/image/logo.png" />
    <link rel="stylesheet" href="./public/css/style.css">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="/favicon.svg" />
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
<!-- <link rel="manifest" href="/site.webmanifest" /> -->
<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#16A34A">
<link rel="manifest" href="./manifest.json">


    <!-- Intro.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intro.js/minified/introjs.min.css">
</head>
<body class="bg-gray-100">




    <!-- Header Section -->
    <header class="bg-green-600 text-white py-4 px-6 flex justify-between items-center" id="header">
        <div class="flex items-center">
            <img src="./Storage/image/logo.png" alt="Logo" class="w-15 h-12">
            <h1 class="ml-3 text-xl font-bold">Student Portal</h1>
        </div>

        <!-- Hamburger Icon (hidden on large screens) -->
        <button id="hamburger" class="lg:hidden flex items-center p-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Navigation Links (hidden on small screens) -->
        <nav id="navLinks" class="hidden lg:flex space-x-6">
            <ul class="flex space-x-6">
                <li><a href="#home" class="hover:text-blue-300">Home</a></li>
                <li><a href="./login.php" class="hover:text-blue-300">Login</a></li>
                <li><a href="#about" class="hover:text-blue-300">About</a></li>
                <li><a href="#contact" class="hover:text-blue-300">Contact</a></li>
                <li><a href="#features" class="hover:text-blue-300">Features</a></li>
                <li><a href="#courses" class="hover:text-blue-300">Courses</a></li>
            </ul>
        </nav>
        <!-- Install Popup (Centered in Header) -->
<!-- Install Popup (Centered in Header with Logo) -->
<div id="installPopup" class="absolute top-16 left-1/2 -translate-x-1/2 bg-white text-black p-4 shadow-lg rounded-lg hidden w-72">
    <div class="flex flex-col items-center">
        <!-- Logo Image -->
        <img src="./icons/web-app-manifest-512x512.png" alt="App Logo" class="w-12 h-12 mb-2">
        
        <p id="installText" class="text-sm font-semibold text-center">Install this app for a better experience!</p>
        <div id="installButtons" class="mt-2 flex justify-center space-x-2">
            <button id="installBtn" class="bg-green-500 text-white px-3 py-1 rounded">Install</button>
            <button id="cancelBtn" class="bg-gray-300 px-3 py-1 rounded">Cancel</button>
        </div>
    </div>
</div>

    </header>

    <!-- Mobile Menu (shown when hamburger is clicked) -->
    <div id="mobileMenu" class="lg:hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 absolute top-0 right-0 w-3/4 h-full">
            <button id="closeMobileMenu" class="text-black">Close</button>
            <nav class="mt-4">
                <ul class="space-y-6">
                    <li><a href="#home" class="block text-black hover:text-blue-300">Home</a></li>
                    <li><a href="./login.php" class="block text-black hover:text-blue-300">Login</a></li>
                    <li><a href="#about" class="block text-black hover:text-blue-300">About</a></li>
                    <li><a href="#contact" class="block text-black hover:text-blue-300">Contact</a></li>
                    <li><a href="#features" class="block text-black hover:text-blue-300">Features</a></li>
                    <li><a href="#courses" class="block text-black hover:text-blue-300">Courses</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Main Section -->
    <main class="px-6 py-10">

        <!-- Welcome Section -->
        <section id="home" class="bg-white shadow-lg rounded-lg p-8 mb-8" data-intro="Welcome to the Student Portal!">
            <h2 class="text-3xl font-semibold text-green-800 text-center mb-4">GATEWAYS INSTITUTE OF SCIENCE & TECHNOLOGY</h2>
            <p class="mb-4 text-center">Welcome to our official website! GIST is a TESDA and DEPED registered technical-vocational institution and senior high school dedicated to the preparation of the country’s young men and women towards lucrative employment within the shortest time
            possible and at the least cost.</p>
        </section>

        <!-- Login Section -->
        <section id="login" class="bg-white shadow-lg rounded-lg p-8 mb-8" data-intro="Access your account here">
            <h2 class="text-3xl font-semibold mb-4">Login</h2>
            <p class="mb-4">Access your account to manage your portal.</p>
        </section>

        <!-- About Section -->
        <section id="about" class="bg-white shadow-lg rounded-lg p-8 mb-8" data-intro="Learn more about our portal and services">
            <h2 class="text-3xl font-semibold mb-4">About</h2>
            <p class="mb-4">Get to know the features and services we provide.</p>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="bg-white shadow-lg rounded-lg p-8 mb-8" data-intro="Get in touch with us for support">
            <h2 class="text-3xl font-semibold mb-4">Contact</h2>
            <p class="mb-4">Reach out to us if you need assistance.</p>
        </section>

        <!-- Features Section -->
        <section id="features" class="bg-white shadow-lg rounded-lg p-8 mb-8" data-intro="Explore the features of the Student Portal">
            <h2 class="text-3xl font-semibold mb-4">Features</h2>
            <p class="mb-4">Our portal offers the following features to enhance your student experience:</p>
            <ul class="list-disc pl-6">
                <li>Track your academic progress and grades.</li>
                <li>Access course materials and schedules.</li>
                <li>Interact with professors and peers.</li>
                <li>Receive timely notifications and updates.</li>
            </ul>
        </section>

        <!-- Courses Section -->
        <section id="courses" class="bg-white shadow-lg rounded-lg p-8 mb-8" data-intro="Discover available courses in the portal">
            <h2 class="text-3xl font-semibold mb-4">Courses</h2>
            <p class="mb-4">Explore various courses offered through the portal. Here are a few examples:</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gray-200 p-6 rounded-lg">
                    <img src="./Storage/image/abm.jpg" alt="Course 1" class="w-full h-40 object-cover rounded mb-4">
                    <h3 class="text-xl font-semibold mb-2">Accountancy, Business, and Management (ABM)</h3>
                    <!-- <p>Learn the basics of web development with HTML, CSS, and JavaScript.</p> -->
                </div>
                <div class="bg-gray-200 p-6 rounded-lg">
                    <img src="./Storage/image/ict.jpg" alt="Course 2" class="w-full h-40 object-cover rounded mb-4">
                    <h3 class="text-xl font-semibold mb-2">Information and Communication Technology (ICT)</h3>
                    <!-- <p>Understand the fundamentals of data analysis and machine learning.</p> -->
                </div>
                <div class="bg-gray-200 p-6 rounded-lg">
                    <img src="./Storage//image/he.jpg" alt="Course 3" class="w-full h-40 object-cover rounded mb-4">
                    <h3 class="text-xl font-semibold mb-2">Home Economics (HE)</h3>
                    <!-- <p>Explore the world of online marketing and social media strategies.</p> -->
                </div>
            </div>
        </section>

    </main>

    <!-- Intro.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/intro.js/minified/intro.min.js"></script>
    <script src="./Public/js/index.js"></script>

</body>
</html>
