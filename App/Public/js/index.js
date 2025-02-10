const hamburger = document.getElementById("hamburger");
const mobileMenu = document.getElementById("mobileMenu");
const closeMobileMenu = document.getElementById("closeMobileMenu");

hamburger.addEventListener("click", function() {
    mobileMenu.classList.remove("hidden");
});

closeMobileMenu.addEventListener("click", function() {
    mobileMenu.classList.add("hidden");
});

// Function to handle text-to-speech
function speakText(text) {
    const speech = new SpeechSynthesisUtterance(text);
    speech.lang = "en-US"; // Set the language
    speech.rate = 1; // Adjust the speed
    speech.pitch = 1; // Adjust the pitch
    window.speechSynthesis.speak(speech);
}

document.addEventListener('DOMContentLoaded', function() {
    introJs().setOptions({
        steps: [
            { 
                element: document.querySelector("#header"),
                intro: "Welcome to the Student Portal header section. This is where you navigate through the site.",
                position: "bottom"
            },
            { 
                element: document.querySelector("#home"),
                intro: "This is the homepage. You can explore your courses, grades, and other features here.",
                position: "top"
            },
            { 
                element: document.querySelector("#login"),
                intro: "Click here to log in to your account and access the portal.",
                position: "right"
            },
            { 
                element: document.querySelector("#about"),
                intro: "Learn more about the features and services of our portal.",
                position: "left"
            },
            { 
                element: document.querySelector("#contact"),
                intro: "Get in touch with us through this section.",
                position: "top"
            },
            { 
                element: document.querySelector("#features"),
                intro: "Explore the features of the portal, such as grade tracking and notifications.",
                position: "bottom"
            },
            { 
                element: document.querySelector("#courses"),
                intro: "Check out available courses you can take through the portal.",
                position: "top"
            }
        ]
    }).onbeforechange(function(targetElement) {
        // Speak the intro text when a step is activated
        speakText(this._options.steps[this._currentStep].intro);
    }).start();
});


let deferredPrompt;

window.addEventListener("beforeinstallprompt", (event) => {
  event.preventDefault(); // Prevent default install banner
  deferredPrompt = event;

  // Check if the app was already installed (Prevent pop-up from showing again)
  if (localStorage.getItem("appInstalled")) {
    return; // Stop execution if already installed
  }

  // Show the install popup after 3 seconds
  setTimeout(() => {
    document.getElementById("installPopup").style.display = "block";
  }, 3000);
});

// When user clicks the "Install" button
document.getElementById("installBtn").addEventListener("click", async () => {
  if (deferredPrompt) {
    // Update UI to show "Installing..."
    document.getElementById("installText").innerText = "Installing...";
    document.getElementById("installButtons").classList.add("hidden");

    // Show the install prompt
    deferredPrompt.prompt();
    const choiceResult = await deferredPrompt.userChoice;

    if (choiceResult.outcome === "accepted") {
      console.log("User accepted the install");
      localStorage.setItem("appInstalled", "true"); // Save that app was installed
    } else {
      console.log("User dismissed the install");
    }

    // Hide the popup completely
    document.getElementById("installPopup").style.display = "none";
    deferredPrompt = null;
  }
});

// Close the popup if user clicks "Cancel"
document.getElementById("cancelBtn").addEventListener("click", () => {
  document.getElementById("installPopup").style.display = "none";
});

// Detect if the app has been installed and prevent future popups
window.addEventListener("appinstalled", () => {
  console.log("App has been installed");
  localStorage.setItem("appInstalled", "true"); // Store in localStorage
  document.getElementById("installPopup").style.display = "none";
});