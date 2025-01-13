<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal Landing Page</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./public/css/style.css">
    <!-- Intro.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intro.js/minified/introjs.min.css">
</head>
<body class="bg-gray-100">

    <!-- Header Section -->
    <header class="bg-amber-600 text-white py-4 px-6 flex justify-between items-center" id="header">
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
                <li><a href="#login" class="hover:text-blue-300">Login</a></li>
                <li><a href="#about" class="hover:text-blue-300">About</a></li>
                <li><a href="#contact" class="hover:text-blue-300">Contact</a></li>
                <li><a href="#features" class="hover:text-blue-300">Features</a></li>
                <li><a href="#courses" class="hover:text-blue-300">Courses</a></li>
            </ul>
        </nav>
    </header>

    <!-- Mobile Menu (shown when hamburger is clicked) -->
    <div id="mobileMenu" class="lg:hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 absolute top-0 right-0 w-3/4 h-full">
            <button id="closeMobileMenu" class="text-black">Close</button>
            <nav class="mt-4">
                <ul class="space-y-6">
                    <li><a href="#home" class="block text-black hover:text-blue-300">Home</a></li>
                    <li><a href="./" class="block text-black hover:text-blue-300">Login</a></li>
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
                    <img src="https://via.placeholder.com/150" alt="Course 1" class="w-full h-32 object-cover rounded mb-4">
                    <h3 class="text-xl font-semibold mb-2">Course 1: Web Development</h3>
                    <p>Learn the basics of web development with HTML, CSS, and JavaScript.</p>
                </div>
                <div class="bg-gray-200 p-6 rounded-lg">
                    <img src="https://via.placeholder.com/150" alt="Course 2" class="w-full h-32 object-cover rounded mb-4">
                    <h3 class="text-xl font-semibold mb-2">Course 2: Data Science</h3>
                    <p>Understand the fundamentals of data analysis and machine learning.</p>
                </div>
                <div class="bg-gray-200 p-6 rounded-lg">
                    <img src="https://via.placeholder.com/150" alt="Course 3" class="w-full h-32 object-cover rounded mb-4">
                    <h3 class="text-xl font-semibold mb-2">Course 3: Digital Marketing</h3>
                    <p>Explore the world of online marketing and social media strategies.</p>
                </div>
            </div>
        </section>

    </main>

    <!-- Intro.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/intro.js/minified/intro.min.js"></script>
    <script>
        const hamburger = document.getElementById("hamburger");
        const mobileMenu = document.getElementById("mobileMenu");
        const closeMobileMenu = document.getElementById("closeMobileMenu");

        hamburger.addEventListener("click", function() {
            mobileMenu.classList.remove("hidden");
        });

        closeMobileMenu.addEventListener("click", function() {
            mobileMenu.classList.add("hidden");
        });

        document.addEventListener('DOMContentLoaded', function() {
            introJs().setOptions({
                steps: [
                    { 
                        element: document.querySelector("#header"),
                        intro: "Welcome to the Student Portal header section. This is where you navigate through the site." 
                    },
                    { 
                        element: document.querySelector("#home"),
                        intro: "This is the homepage. You can explore your courses, grades, and other features here." 
                    },
                    { 
                        element: document.querySelector("#login"),
                        intro: "Click here to log in to your account and access the portal."
                    },
                    { 
                        element: document.querySelector("#about"),
                        intro: "Learn more about the features and services of our portal."
                    },
                    { 
                        element: document.querySelector("#contact"),
                        intro: "Get in touch with us through this section."
                    },
                    { 
                        element: document.querySelector("#features"),
                        intro: "Explore the features of the portal, such as grade tracking and notifications."
                    },
                    { 
                        element: document.querySelector("#courses"),
                        intro: "Check out available courses you can take through the portal."
                    }
                ]
            }).start();
        });
    </script>

</body>
</html>
