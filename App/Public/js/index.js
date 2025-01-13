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