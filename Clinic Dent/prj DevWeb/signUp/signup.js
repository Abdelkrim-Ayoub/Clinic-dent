// Validate form data before submitting
document.getElementById('signupForm').addEventListener('submit', function(event) {
    let nom = document.getElementById('nom').value;
    let prenom = document.getElementById('prenom').value;
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;

    // Basic validation
    if (nom === "" || prenom === "" || email === "" || password === "") {
        alert("All fields must be filled out.");
        event.preventDefault();
    } else {
        // Email validation
        let emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailPattern.test(email)) {
            alert("Please enter a valid email.");
            event.preventDefault();
        }
    }
});