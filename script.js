/**
 * Mobile Menu Toggle
 */
document.addEventListener("DOMContentLoaded", function() {
    let menuToggle = document.querySelector("#mobile-menu");
    let navMenu = document.querySelector(".navbar__menu");
    
    menuToggle.addEventListener("click", function() {
        menuToggle.classList.toggle("is-active");
        navMenu.classList.toggle("active");
    });
});

/**
 * Cookie Consent Banner
 */
document.addEventListener("DOMContentLoaded", () => {
    let cookieBanner = document.getElementById("cookie-consent-banner");
    var acceptButton = document.getElementById("accept-cookies");
    
    if (!localStorage.getItem("cookiesAccepted")) {
        setTimeout(() => {
            cookieBanner.style.display = "block";
        }, 2000);
    }
    
    acceptButton.addEventListener("click", () => {
        localStorage.setItem("cookiesAccepted", "true");
        cookieBanner.style.display = "none";
    });
});

/**
 * Navbar Scroll Effect
 */
let navbar = document.querySelector(".navbar");

window.addEventListener("scroll", () => {
    if (window.scrollY > 0) {
        navbar.classList.add("scrolled");
    } else {
        navbar.classList.remove("scrolled");
    }
});

/**
 * Contact Form Validation and Submission
 */
document.addEventListener("DOMContentLoaded", function() {
    let contactForm = document.getElementById("contactForm");
    let requiredFields = contactForm.querySelectorAll("input[required], textarea[required]");
    let submitButton = contactForm.querySelector('button[type="submit"]');
    let emailField = contactForm.querySelector("#email");

    /**
     * Validate all form fields
     */
    function validateForm() {
        let isValid = true;
        
        requiredFields.forEach(field => {
            field.classList.remove("error");
            
            if (field.value.trim()) {
                field.classList.add("filled");
            } else {
                field.classList.remove("filled");
            }
            
            // Check max length
            if (field.value.trim() && field.maxLength && field.value.length > field.maxLength) {
                isValid = false;
                field.classList.add("error");
                field.setCustomValidity(`Maxim ${field.maxLength} caractere sunt permise.`);
            } else {
                field.setCustomValidity("");
            }
            
            // Check validity
            if (field.value.trim() && !field.checkValidity()) {
                isValid = false;
                field.classList.add("error");
            }
        });
        
        // Validate email format
        if (emailField.value.trim()) {
            let emailValue = emailField.value;
            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailPattern.test(emailValue)) {
                isValid = false;
                emailField.classList.add("error");
            }
        }
        
        submitButton.disabled = !isValid;
    }

    /**
     * Debounce function for input validation
     */
    let debounce = ((func, delay = 300) => {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    })(validateForm);

    /**
     * Show error popup
     */
    function showErrorPopup(message) {
        var popup = document.getElementById("popup");
        
        if (popup.classList.contains("open-popup")) {
            popup.classList.remove("open-popup");
            popup.style.transform = "translate(-50%, -50%) scale(0.1)";
        }
        
        let errorPopup = document.createElement("div");
        errorPopup.id = "error-popup";
        errorPopup.style.position = "fixed";
        errorPopup.style.top = "50%";
        errorPopup.style.left = "50%";
        errorPopup.style.transform = "translate(-50%, -50%)";
        errorPopup.style.padding = "20px";
        errorPopup.style.backgroundColor = "#f44336";
        errorPopup.style.color = "#fff";
        errorPopup.style.borderRadius = "5px";
        errorPopup.style.zIndex = "1000";
        errorPopup.innerText = message;
        
        document.body.appendChild(errorPopup);
        
        setTimeout(() => {
            document.body.removeChild(errorPopup);
        }, 5000);
    }

    // Add input event listeners
    requiredFields.forEach(field => {
        field.addEventListener("input", debounce);
    });

    /**
     * Form submission handler
     */
    contactForm.addEventListener("submit", function(event) {
        event.preventDefault();
        
        if (!contactForm.checkValidity()) {
            return;
        }
        
        let formData = new FormData(contactForm);
        
        fetch("send_email_php.php", {
            method: "POST",
            body: formData,
            headers: {
                Accept: "application/json"
            }
        })
        .then(response => response.json().then(data => {
            if (response.ok && data.success) {
                let popup = document.getElementById("popup");
                let loadingBar = document.querySelector(".loading-bar");
                let popupMessage = document.getElementById("popup-message");
                let successIcon = document.getElementById("success-icon");
                let progress = 0;
                
                loadingBar.style.width = "0%";
                loadingBar.textContent = "0%";
                loadingBar.style.display = "flex";
                popupMessage.textContent = "Mesajul este în curs de expediere";
                successIcon.style.display = "none";
                loadingBar.className = "loading-bar";
                
                popup.classList.add("open-popup");
                popup.style.transform = "translate(-50%, -50%) scale(1)";
                
                sessionStorage.setItem("originalUrl", window.location.href);
                let newUrl = window.location.href.replace("contacts.html", "contacts/form_submission.html");
                window.history.replaceState(null, null, newUrl);
                
                let progressInterval = setInterval(() => {
                    progress += 1;
                    loadingBar.style.width = progress + "%";
                    loadingBar.textContent = progress + "%";
                    
                    if (progress >= 20 && progress < 40) {
                        loadingBar.classList.add("color-20");
                    } else if (progress >= 40 && progress < 60) {
                        loadingBar.classList.add("color-40");
                    } else if (progress >= 60 && progress < 80) {
                        loadingBar.classList.add("color-60");
                    } else if (progress >= 80 && progress < 100) {
                        loadingBar.classList.add("color-80");
                    } else if (progress >= 100) {
                        loadingBar.classList.add("color-100");
                        clearInterval(progressInterval);
                        
                        setTimeout(() => {
                            loadingBar.style.display = "none";
                            successIcon.style.display = "block";
                            popupMessage.textContent = "Expediat cu Succes";
                            popupMessage.style.marginTop = "20px";
                            
                            setTimeout(() => {
                                let popupElement = document.getElementById("popup");
                                popupElement.classList.remove("open-popup");
                                popupElement.style.transform = "translate(-50%, -50%) scale(0.1)";
                                
                                setTimeout(() => {
                                    window.location.href = "/contacts.html";
                                }, 500);
                            }, 3000);
                        }, 500);
                    }
                }, 40);
            } else {
                if (response.status === 500) {
                    showErrorPopup("A apărut o eroare de server. Încearcă din nou mai târziu.");
                } else {
                    showErrorPopup("A apărut o eroare neașteptată. Încearcă din nou mai târziu.");
                }
            }
        }))
        .catch(() => {
            showErrorPopup("A apărut o eroare de rețea. Încearcă din nou mai târziu.");
        });
    });

    validateForm();
});

/**
 * Cookie Consent Banner (duplicate listener for other pages)
 */
document.addEventListener("DOMContentLoaded", () => {
    let cookieBanner = document.getElementById("cookie-consent-banner");
    var acceptButton = document.getElementById("accept-cookies");
    
    if (!localStorage.getItem("cookiesAccepted")) {
        cookieBanner.style.display = "block";
    }
    
    acceptButton.addEventListener("click", () => {
        localStorage.setItem("cookiesAccepted", "true");
        cookieBanner.style.display = "none";
    });
});

/**
 * Phone Link Click Handler (Mobile devices)
 */
document.addEventListener("DOMContentLoaded", function() {
    let isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    
    document.querySelectorAll(".telefon-link, .phone_number").forEach(element => {
        element.addEventListener("click", function() {
            var phoneNumber = document.querySelector(".phone_number").textContent.trim();
            
            if (isMobile) {
                window.location.href = "tel:" + phoneNumber;
            }
        });
    });
});

/**
 * Image Slideshow
 */
let slideIndex = 1;

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    
    if (n > slides.length) {
        slideIndex = 1;
    }
    
    if (n < 1) {
        slideIndex = slides.length;
    }
    
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
}

showSlides(slideIndex);

/**
 * Touch Swipe Gestures for Slideshow
 */
let touchStartX = 0;
let touchEndX = 0;
let sliderContainer = document.querySelector(".slideshow-container");

function handleSwipeGesture() {
    if (touchEndX < touchStartX) {
        plusSlides(1);
    }
    
    if (touchEndX > touchStartX) {
        plusSlides(-1);
    }
}

sliderContainer.addEventListener("touchstart", function(event) {
    touchStartX = event.changedTouches[0].screenX;
});

sliderContainer.addEventListener("touchend", function(event) {
    touchEndX = event.changedTouches[0].screenX;
    handleSwipeGesture();
});

/**
 * Services Menu Smooth Scroll
 */
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".services-menu nav ul li a").forEach(link => {
        link.addEventListener("click", function(event) {
            event.preventDefault();
            
            let targetId = this.getAttribute("href");
            let targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            }
        });
    });
});
