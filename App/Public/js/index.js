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

if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("/service-worker.js")
      .then(() => console.log("Service Worker Registered"))
      .catch(error => console.log("Service Worker Failed", error));
  }

  let deferredPrompt;

window.addEventListener("beforeinstallprompt", (event) => {
  event.preventDefault(); // Prevent default install banner
  deferredPrompt = event;

  // Show the install popup after 3 seconds
  setTimeout(() => {
    document.getElementById("installPopup").style.display = "block";
  }, 3000);
});

// When user clicks the "Install" button
document.getElementById("installBtn").addEventListener("click", () => {
  if (deferredPrompt) {
    deferredPrompt.prompt();
    deferredPrompt.userChoice.then((choiceResult) => {
      if (choiceResult.outcome === "accepted") {
        console.log("User accepted the install");
      } else {
        console.log("User dismissed the install");
      }
      deferredPrompt = null;
    });

    // Hide popup after clicking install
    document.getElementById("installPopup").style.display = "none";
  }
});

// Close the popup if user clicks "Cancel"
document.getElementById("cancelBtn").addEventListener("click", () => {
  document.getElementById("installPopup").style.display = "none";
});
